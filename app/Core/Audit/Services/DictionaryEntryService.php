<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DictionaryEntryService
{
    public static function normalizePinyin(string $keyword): string
    {
        $text = mb_strtolower(trim($keyword));

        // Remove tone numbers
        $text = preg_replace('/\d+/', '', $text);

        // Remove tone marks / accents
        $text = Str::ascii($text);

        // Remove spaces
        return preg_replace('/\s+/', '', $text);
    }

    public static function searchPinyin(string $keyword, int $limit = 15): Collection
    {
        $raw = trim($keyword);
        $clean = self::normalizePinyin($raw);
        $rawNoSpace = preg_replace('/\s+/', '', $raw);

        return DB::table('dictionary_entries')
            ->where(function ($query) use ($raw, $clean, $rawNoSpace) {
                $query
                    ->where('pinyin_clean', 'ILIKE', "%{$clean}%")
                    ->orWhere('pinyin', 'ILIKE', "%{$raw}%")
                    ->orWhereRaw(
                        "REPLACE(pinyin, ' ', '') ILIKE ?",
                        ["%{$rawNoSpace}%"]
                    )
                    ->orWhere('pinyin_accented', 'ILIKE', "%{$raw}%")
                    ->orWhereRaw(
                        "REPLACE(pinyin_accented, ' ', '') ILIKE ?",
                        ["%{$rawNoSpace}%"]
                    );
            })
            ->limit($limit)
            ->get();
    }

    public static function searchChinese(string $keyword, int $limit = 15): Collection
    {
        $keyword = trim($keyword);

        return DB::table('dictionary_entries')
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('simplified', 'LIKE', "%{$keyword}%")
                    ->orWhere('traditional', 'LIKE', "%{$keyword}%");
            })
            ->limit($limit)
            ->get();
    }

    public static function searchVietnamese(string $keyword, int $limit = 15): Collection
    {
        $raw = trim($keyword);
        $clean = Str::ascii(mb_strtolower($raw));

        return DB::table('dictionary_entries')
            ->where(function ($query) use ($raw, $clean) {
                $query
                    ->where('vietnamese', 'ILIKE', "%{$raw}%")
                    ->orWhere(
                        'vietnamese_unaccented',
                        'ILIKE',
                        "%{$clean}%"
                    );
            })
            ->limit($limit)
            ->get();
    }
}
