<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedDictionaryEntries extends Command
{
    protected $signature = 'import:dictionary {file? : Path to the CVDICT2.u8 file}';
    protected $description = 'Import CC-CEDICT / CVDICT dictionary entries into the database';

    private array $toneMap = [
        'a' => ['a', 'ā', 'á', 'ǎ', 'à'],
        'e' => ['e', 'ē', 'é', 'ě', 'è'],
        'o' => ['o', 'ō', 'ó', 'ǒ', 'ò'],
        'i' => ['i', 'ī', 'í', 'ǐ', 'ì'],
        'u' => ['u', 'ū', 'ú', 'ǔ', 'ù'],
        'v' => ['ü', 'ǖ', 'ǘ', 'ǚ', 'ǜ'],
        'ü' => ['ü', 'ǖ', 'ǘ', 'ǚ', 'ǜ'],
    ];

    public function handle(): int
    {
        ini_set('memory_limit', '512M');
        DB::disableQueryLog();
        $filePath = $this->argument('file') ?? database_path('CVDICT2.u8');

        if (!file_exists($filePath)) {
            $this->error("Error: Could not find dictionary file at '{$filePath}'.");
            return 1;
        }

        $this->info("Opening dictionary file: {$filePath}...");

        $handle = fopen($filePath, 'r');
        $batch = [];
        $batchSize = 2000;
        $totalCount = 0;
        $startTime = microtime(true);

        if ($this->confirm('Do you want to truncate the dictionary_entries table first?', true)) {
            DB::table('dictionary_entries')->truncate();
            $this->info('Table dictionary_entries truncated.');
        }

        $this->info('Parsing and bulk inserting records...');

        DB::beginTransaction();

        try {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);

                if (empty($line) || str_starts_with($line, '#') || str_starts_with($line, '%')) {
                    continue;
                }

                // Regex matching CC-CEDICT format: Traditional Simplified [pinyin] /definition/
                if (preg_match('/^(\S+)\s+(\S+)\s+\[(.*?)\]\s+\/(.*)\/$/u', $line, $matches)) {
                    $traditional = $matches[1];
                    $simplified  = $matches[2];
                    $pinyinRaw   = $matches[3];
                    $rawDef      = $matches[4];

                    $vietnamese           = str_replace('/', '; ', $rawDef);
                    $vietnameseUnaccented = $this->removeVietnameseAccents($vietnamese);

                    $pinyinAccented = $this->pinyinToAccented($pinyinRaw);
                    $pinyinClean    = $this->pinyinToClean($pinyinRaw);

                    $batch[] = [
                        'traditional'           => $traditional,
                        'simplified'            => $simplified,
                        'pinyin_accented'       => $pinyinAccented,
                        'pinyin'                => $pinyinRaw,
                        'pinyin_clean'          => $pinyinClean,
                        'vietnamese'            => $vietnamese,
                        'vietnamese_unaccented' => $vietnameseUnaccented,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ];

                    $totalCount++;

                    if (count($batch) >= $batchSize) {
                        DB::table('dictionary_entries')->insert($batch);
                        $batch = [];
                    }
                }
            }

            if (!empty($batch)) {
                DB::table('dictionary_entries')->insert($batch);
            }

            DB::commit();
            fclose($handle);

            $elapsed = round(microtime(true) - $startTime, 2);
            $this->info("Successfully seeded {$totalCount} entries in {$elapsed} seconds!");

            return 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            $this->error("Database Error during ingestion: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Converts numbered Pinyin (e.g. 'lao3 shi1') to tone-marked Pinyin (e.g. 'lǎo shī').
     */
    private function pinyinToAccented(string $pinyinRaw): string
    {
        $words = explode(' ', $pinyinRaw);
        $converted = [];

        foreach ($words as $word) {
            if (!preg_match('/^([a-zA-VüÜ]+)([1-5])$/u', $word, $match)) {
                $converted[] = $word;
                continue;
            }

            $syllable = $match[1];
            $toneNum  = (int) $match[2];

            if ($toneNum === 5) {
                $converted[] = $syllable;
                continue;
            }

            $targetVowel = null;
            foreach (['a', 'e', 'o'] as $v) {
                if (str_contains($syllable, $v)) {
                    $targetVowel = $v;
                    break;
                }
            }

            if (!$targetVowel) {
                $chars = mb_str_split($syllable);
                for ($i = count($chars) - 1; $i >= 0; $i--) {
                    if (isset($this->toneMap[$chars[$i]])) {
                        $targetVowel = $chars[$i];
                        break;
                    }
                }
            }

            if ($targetVowel && isset($this->toneMap[$targetVowel])) {
                $markedVowel = $this->toneMap[$targetVowel][$toneNum];

                // Replace only the first occurrence of the target vowel
                $pos = mb_strpos($syllable, $targetVowel);
                if ($pos !== false) {
                    $syllable = mb_substr($syllable, 0, $pos) . $markedVowel . mb_substr($syllable, $pos + mb_strlen($targetVowel));
                }
            }

            $converted[] = $syllable;
        }

        return implode(' ', $converted);
    }

    /**
     * Normalizes raw Pinyin into a seamless search string.
     * Examples: 'Q tan2' -> 'qtan', 'èr shí yī' -> 'ershiyi'
     */
    private function pinyinToClean(string $pinyinRaw): string
    {
        $text = preg_replace('/\d+/u', '', mb_strtolower($pinyinRaw));
        $normalized = \Normalizer::normalize($text, \Normalizer::FORM_D);
        $clean = preg_replace('/[\x{0300}-\x{036F}]/u', '', $normalized);
        $clean = \Normalizer::normalize($clean, \Normalizer::FORM_C);

        return preg_replace('/\s+/u', '', $clean);
    }

    /**
     * Strips diacritics and replaces special characters ('đ' -> 'd').
     */
    private function removeVietnameseAccents(string $text): string
    {
        $normalized = \Normalizer::normalize($text, \Normalizer::FORM_D);
        $clean = preg_replace('/[\x{0300}-\x{036F}]/u', '', $normalized);
        $clean = \Normalizer::normalize($clean, \Normalizer::FORM_C);

        $clean = str_replace(['đ', 'Đ'], ['d', 'D'], $clean);

        return trim(mb_strtolower($clean));
    }
}
