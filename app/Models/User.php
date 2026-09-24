<?php

namespace App\Models;

use App\Core\Gem\Models\GemTransaction;
use App\Core\User\Models\UserStat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'status',
        'two_factor_secret',
        'two_factor_enabled',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'status'])
            ->logOnlyDirty()
            ->useLogName('auth');
    }

    public function loginHistory()
    {
        return $this->hasMany(\App\Core\Auth\Models\LoginHistory::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(UserStat::class);
    }

    public function gemTransactions(): HasMany
    {
        return $this->hasMany(GemTransaction::class);
    }

    public function apiKeys()
    {
        return $this->hasMany(\App\Core\Developer\Models\ApiKey::class);
    }

    public function ensureStats(): UserStat
    {
        return $this->stats()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'total_xp' => 0,
                'current_level' => 1,
                'gems' => 0,
                'streak' => 0,
                'longest_streak' => 0,
                'last_activity_date' => null,
            ]
        );
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }
}
