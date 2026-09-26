<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
