<?php

namespace App\Core\Level\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $primaryKey = 'level';

    public $incrementing = false;

    protected $fillable = [
        'level',
        'required_xp',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'required_xp' => 'integer',
        ];
    }

    public static function forXp(int $totalXp): ?self
    {
        return static::query()
            ->where('required_xp', '<=', $totalXp)
            ->orderByDesc('level')
            ->first();
    }
}
