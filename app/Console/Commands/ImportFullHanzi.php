<?php

namespace App\Console\Commands;

use App\Models\Hanzi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportFullHanzi extends Command
{
    protected $signature = 'import:hanzi-full
                            {dictionary? : Path to dictionary.txt}
                            {graphics? : Path to graphics.txt}';

    protected $description = 'Import complete Make Me a Hanzi dataset into the hanzis table';

    public function handle()
    {
        // Default to database/dictionary.txt and database/graphics.txt if not provided
        $dictPath = $this->argument('dictionary') ?? database_path('/hanzis/dictionary.txt');
        $graphPath = $this->argument('graphics') ?? database_path('/hanzis/graphics.txt');

        if (! file_exists($dictPath)) {
            $this->error("Dictionary file not found at: {$dictPath}");

            return 1;
        }

        if (! file_exists($graphPath)) {
            $this->error("Graphics file not found at: {$graphPath}");

            return 1;
        }

        $this->info("Importing dictionary from: {$dictPath}");
        $this->importDictionary($dictPath);

        $this->info("Importing graphics from: {$graphPath}");
        $this->importGraphics($graphPath);

        $this->info('Full Hanzi dataset import completed successfully!');

        return 0;
    }

    private function importDictionary(string $filePath): void
    {
        $handle = fopen($filePath, 'r');
        $batch = [];
        $batchSize = 500;

        DB::beginTransaction();

        while (($line = fgets($handle)) !== false) {
            $data = json_decode(trim($line), true);

            if (! $data || ! isset($data['character'])) {
                continue;
            }

            $codePoint = mb_ord($data['character']);

            $batch[] = [
                'character' => $data['character'],
                'definition' => $data['definition'] ?? null,
                'pinyin' => json_encode($data['pinyin'] ?? []),
                'decomposition' => $data['decomposition'] ?? null,
                'etymology' => isset($data['etymology']) ? json_encode($data['etymology']) : null,
                'radical' => $data['radical'] ?? null,
                'matches' => isset($data['matches']) ? json_encode($data['matches']) : null,
                'animated_svg_path' => "hanzi-svgs/animated/{$codePoint}.svg",
                'still_svg_path' => "hanzi-svgs/still/{$codePoint}.svg",
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                Hanzi::upsert($batch, ['character'], [
                    'definition', 'pinyin', 'decomposition', 'etymology',
                    'radical', 'matches', 'animated_svg_path', 'still_svg_path', 'updated_at',
                ]);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            Hanzi::upsert($batch, ['character'], [
                'definition', 'pinyin', 'decomposition', 'etymology',
                'radical', 'matches', 'animated_svg_path', 'still_svg_path', 'updated_at',
            ]);
        }

        DB::commit();
        fclose($handle);
    }

    private function importGraphics(string $filePath): void
    {
        $handle = fopen($filePath, 'r');
        $batch = [];
        $batchSize = 500;

        DB::beginTransaction();

        while (($line = fgets($handle)) !== false) {
            $data = json_decode(trim($line), true);

            if (! $data || ! isset($data['character'])) {
                continue;
            }

            $batch[] = [
                'character' => $data['character'],
                'strokes' => json_encode($data['strokes'] ?? []),
                'medians' => json_encode($data['medians'] ?? []),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                Hanzi::upsert($batch, ['character'], ['strokes', 'medians', 'updated_at']);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            Hanzi::upsert($batch, ['character'], ['strokes', 'medians', 'updated_at']);
        }

        DB::commit();
        fclose($handle);
    }
}
