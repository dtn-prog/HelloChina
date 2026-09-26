<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hanzi extends Model
{
    protected $fillable = [
        'character',
        'definition',
        'pinyin',
        'decomposition',
        'radical',
        'etymology',
        'matches',
        'strokes',
        'medians',
        'animated_svg_path',
        'still_svg_path',
    ];

    protected $casts = [
        'pinyin' => 'array',
        'etymology' => 'array',
        'matches' => 'array',
        'strokes' => 'array',
        'medians' => 'array',
    ];

    /**
     * Get full public URLs for the SVG assets
     */
    public function getAnimatedSvgUrlAttribute(): ?string
    {
        return $this->animated_svg_path ? Storage::url($this->animated_svg_path) : null;
    }

    public function getStillSvgUrlAttribute(): ?string
    {
        return $this->still_svg_path ? Storage::url($this->still_svg_path) : null;
    }
}
