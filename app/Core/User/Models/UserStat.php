<?php

namespace App\Core\User\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStat extends Model
{
    protected $table = 'user_stats';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'total_xp',
        'current_level',
        'gems',
        'streak',
        'longest_streak',
        'last_activity_date',
    ];

    protected function casts(): array
    {
        return [
            'total_xp' => 'integer',
            'current_level' => 'integer',
            'gems' => 'integer',
            'streak' => 'integer',
            'longest_streak' => 'integer',
            'last_activity_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
