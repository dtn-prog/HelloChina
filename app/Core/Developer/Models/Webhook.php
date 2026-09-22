<?php

namespace App\Core\Developer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends Model
{
    protected $fillable = [
        'name',
        'url',
        'events',
        'secret',
        'status',
        'retry_count',
        'last_delivery_at',
    ];

    protected $casts = [
        'events' => 'array',
        'last_delivery_at' => 'datetime',
    ];

    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function listensTo(string $event): bool
    {
        return in_array('*', $this->events ?? []) || in_array($event, $this->events ?? []);
    }

    public function generateSecret(): string
    {
        $secret = bin2hex(random_bytes(32));
        $this->update(['secret' => $secret]);
        return $secret;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeListeningTo($query, string $event)
    {
        return $query->whereJsonContains('events', $event)
            ->orWhereJsonContains('events', '*');
    }
}
