<?php

namespace App\Core\Audit\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DictionaryEntryService
{
    public static function search(string $keyword, string $type = 'all', int $limit = 15): Collection
    {
        return match ($type) {
            'chinese'    => self::searchChinese($keyword, $limit),
            'pinyin'     => self::searchPinyin($keyword, $limit),
            'vietnamese' => self::searchVietnamese($keyword, $limit),
            default      => self::searchAll($keyword, $limit),
        };
    }

    private static function searchAll(string $keyword, int $limit): Collection
    {
        $raw = trim($keyword);
        $cleanPinyin = self::normalizePinyin($raw);
        $cleanVietnamese = Str::ascii(mb_strtolower($raw));
        $rawNoSpace = preg_replace('/\s+/', '', $raw);

        return DB::table('dictionary_entries')
            ->select('*')
            ->selectRaw("
                CASE
                    -- 1. Exact Chinese Match
                    WHEN simplified = ? OR traditional = ? THEN 100

                    -- 2. Exact Accented Pinyin Match (Matches Exact Tones)
                    WHEN pinyin_accented = ? OR REPLACE(pinyin_accented, ' ', '') = ? THEN 95

                    -- 3. Exact Raw / Numbered Pinyin Match
                    WHEN pinyin = ? OR REPLACE(pinyin, ' ', '') = ? THEN 90

                    -- 4. Exact Vietnamese Match
                    WHEN vietnamese ILIKE ? OR vietnamese_unaccented ILIKE ? THEN 85

                    -- 5. Pinyin Prefix / Starts-with Matches (Prefers tone match first)
                    WHEN pinyin_accented ILIKE ? THEN 75
                    WHEN pinyin_clean LIKE ? THEN 65

                    -- 6. Chinese Contains Match
                    WHEN simplified LIKE ? OR traditional LIKE ? THEN 55

                    -- 7. Substring Contains Match
                    WHEN pinyin_accented ILIKE ? THEN 45
                    WHEN pinyin_clean LIKE ? THEN 35
                    WHEN vietnamese ILIKE ? OR vietnamese_unaccented ILIKE ? THEN 25

                    ELSE 10
                END AS relevance_score
            ", [
                // Exact checks
                $raw, $raw,
                $raw, $rawNoSpace,
                $raw, $rawNoSpace,
                $raw, $cleanVietnamese,

                // Starts-With checks
                "{$raw}%", "{$cleanPinyin}%",

                // Chinese Contains
                "%{$raw}%", "%{$raw}%",

                // Contains checks
                "%{$raw}%", "%{$cleanPinyin}%",
                "%{$raw}%", "%{$cleanVietnamese}%",
            ])
            ->where(function ($query) use ($raw, $cleanPinyin, $cleanVietnamese, $rawNoSpace) {
                $query->where('simplified', 'LIKE', "%{$raw}%")
                    ->orWhere('traditional', 'LIKE', "%{$raw}%")
                    ->orWhere('pinyin_accented', 'ILIKE', "%{$raw}%")
                    ->orWhereRaw("REPLACE(pinyin_accented, ' ', '') ILIKE ?", ["%{$rawNoSpace}%"])
                    ->orWhere('pinyin_clean', 'LIKE', "%{$cleanPinyin}%")
                    ->orWhere('vietnamese', 'ILIKE', "%{$raw}%")
                    ->orWhere('vietnamese_unaccented', 'ILIKE', "%{$cleanVietnamese}%");
            })
            // Sort by relevance score first, then shorter string length for closest fit
            ->orderByDesc('relevance_score')
            ->orderByRaw('LENGTH(simplified) ASC')
            ->orderByRaw('LENGTH(pinyin_accented) ASC')
            ->limit($limit)
            ->get();
    }

    public static function searchPinyin(string $keyword, int $limit = 15): Collection
    {
        $raw = trim($keyword);
        $clean = self::normalizePinyin($raw);
        $rawNoSpace = preg_replace('/\s+/', '', $raw);

        return DB::table('dictionary_entries')
            ->select('*')
            ->selectRaw("
                CASE
                    -- Highest priority: Exact tone-marked Pinyin match
                    WHEN pinyin_accented = ? OR REPLACE(pinyin_accented, ' ', '') = ? THEN 100

                    -- Exact numbered/raw Pinyin match
                    WHEN pinyin = ? OR REPLACE(pinyin, ' ', '') = ? THEN 90

                    -- Starts with exact tone
                    WHEN pinyin_accented ILIKE ? THEN 80

                    -- Exact unaccented search match
                    WHEN pinyin_clean = ? THEN 70

                    -- Clean starts with
                    WHEN pinyin_clean LIKE ? THEN 60

                    -- Partial tone match
                    WHEN pinyin_accented ILIKE ? THEN 50

                    ELSE 30
                END AS relevance_score
            ", [
                $raw, $rawNoSpace,
                $raw, $rawNoSpace,
                "{$raw}%",
                $clean,
                "{$clean}%",
                "%{$raw}%",
            ])
            ->where(function ($query) use ($raw, $clean, $rawNoSpace) {
                $query->where('pinyin_clean', 'LIKE', "%{$clean}%")
                    ->orWhere('pinyin', 'ILIKE', "%{$raw}%")
                    ->orWhereRaw("REPLACE(pinyin, ' ', '') ILIKE ?", ["%{$rawNoSpace}%"])
                    ->orWhere('pinyin_accented', 'ILIKE', "%{$raw}%")
                    ->orWhereRaw("REPLACE(pinyin_accented, ' ', '') ILIKE ?", ["%{$rawNoSpace}%"]);
            })
            ->orderByDesc('relevance_score')
            ->orderByRaw('LENGTH(pinyin_accented) ASC')
            ->limit($limit)
            ->get();
    }

    public static function searchChinese(string $keyword, int $limit = 15): Collection
    {
        $keyword = trim($keyword);

        return DB::table('dictionary_entries')
            ->select('*')
            ->selectRaw("
                CASE
                    WHEN simplified = ? OR traditional = ? THEN 100
                    WHEN simplified LIKE ? OR traditional LIKE ? THEN 80
                    ELSE 50
                END AS relevance_score
            ", [$keyword, $keyword, "{$keyword}%", "{$keyword}%"])
            ->where(function ($query) use ($keyword) {
                $query->where('simplified', 'LIKE', "%{$keyword}%")
                    ->orWhere('traditional', 'LIKE', "%{$keyword}%");
            })
            ->orderByDesc('relevance_score')
            ->orderByRaw('LENGTH(simplified) ASC')
            ->limit($limit)
            ->get();
    }

    public static function searchVietnamese(string $keyword, int $limit = 15): Collection
    {
        $raw = trim($keyword);
        $clean = Str::ascii(mb_strtolower($raw));

        return DB::table('dictionary_entries')
            ->select('*')
            ->selectRaw("
                CASE
                    WHEN vietnamese ILIKE ? THEN 100
                    WHEN vietnamese_unaccented = ? THEN 90
                    WHEN vietnamese ILIKE ? THEN 70
                    WHEN vietnamese_unaccented ILIKE ? THEN 60
                    ELSE 40
                END AS relevance_score
            ", [$raw, $clean, "{$raw}%", "{$clean}%"])
            ->where(function ($query) use ($raw, $clean) {
                $query->where('vietnamese', 'ILIKE', "%{$raw}%")
                    ->orWhere('vietnamese_unaccented', 'ILIKE', "%{$clean}%");
            })
            ->orderByDesc('relevance_score')
            ->orderByRaw('LENGTH(vietnamese) ASC')
            ->limit($limit)
            ->get();
    }

    public static function normalizePinyin(string $keyword): string
    {
        $text = mb_strtolower(trim($keyword));
        $text = preg_replace('/\d+/', '', $text);
        $text = Str::ascii($text);
        return preg_replace('/\s+/', '', $text);
    }
}
