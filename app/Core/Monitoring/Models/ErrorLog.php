<?php

namespace App\Core\Monitoring\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'stack_trace',
        'url',
        'method',
        'user_id',
        'ip_address',
        'user_agent',
        'request_id',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function scopeOfLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeForRequest($query, string $requestId)
    {
        return $query->where('request_id', $requestId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getLevelColorAttribute(): string
    {
        return match ($this->level) {
            'critical', 'emergency' => 'red',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            'debug' => 'gray',
            default => 'gray',
        };
    }
}
