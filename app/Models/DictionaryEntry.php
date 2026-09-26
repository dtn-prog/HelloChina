<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DictionaryEntry extends Model
{
    protected $fillable = [
        'traditional',
        'simplified',
        'pinyin_accented',
        'pinyin',
        'pinyin_clean',
        'vietnamese',
        'vietnamese_unaccented',
        'audio_url',
    ];

    /**
     * converts a numbered or accented keyword to clean ASCII without numbers or tone marks.
     *   "lao3 shi1" -> "laoshi"
     *   "lǎo shī"   -> "laoshi"
     */
    public static function normalizeKeyword(string $keyword): string
    {
        $text = mb_strtolower(trim($keyword));
        $text = preg_replace('/\d+/', '', $text);      // Strip numbers
        $text = Str::ascii($text);                     // Strip diacritics/tone marks
        return preg_replace('/\s+/', '', $text);       // Strip spaces
    }

    public function scopeSearchPinyin(Builder $query, string $keyword): Builder
    {
        $rawKw = trim($keyword);
        $cleanKw = self::normalizeKeyword($rawKw);

        $rawNoSpace = preg_replace('/\s+/', '', $rawKw);

        return $query->where(function (Builder $q) use ($rawKw, $cleanKw, $rawNoSpace) {
            $q->where('pinyin_clean', 'ILIKE', "%{$cleanKw}%")

              ->orWhere('pinyin', 'ILIKE', "%{$rawKw}%")
              ->orWhereRaw("REPLACE(pinyin, ' ', '') ILIKE ?", ["%{$rawNoSpace}%"])

              ->orWhere('pinyin_accented', 'ILIKE', "%{$rawKw}%")
              ->orWhereRaw("REPLACE(pinyin_accented, ' ', '') ILIKE ?", ["%{$rawNoSpace}%"]);
        });
    }

    public function scopeSearchChinese(Builder $query, string $keyword): Builder
    {
        $rawKw = trim($keyword);

        return $query->where(function (Builder $q) use ($rawKw) {
            $q->where('simplified', 'LIKE', "%{$rawKw}%")
              ->orWhere('traditional', 'LIKE', "%{$rawKw}%");
        });
    }
    public function scopeSearchVietnamese(Builder $query, string $keyword): Builder
    {
        $rawKw = trim($keyword);
        $cleanKw = Str::ascii(mb_strtolower($rawKw));

        return $query->where(function (Builder $q) use ($rawKw, $cleanKw) {
            $q->where('vietnamese', 'ILIKE', "%{$rawKw}%")
              ->orWhere('vietnamese_unaccented', 'ILIKE', "%{$cleanKw}%");
        });
    }
}