<?php

namespace App\Core\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'name',
        'channel',
        'subject',
        'body',
        'variables',
        'language',
        'status',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeOfLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function render(array $data = []): string
    {
        $body = $this->body;

        foreach ($data as $key => $value) {
            $body = str_replace("{{{$key}}}", (string) $value, $body);
        }

        return $body;
    }

    public function renderSubject(array $data = []): ?string
    {
        if (!$this->subject) {
            return null;
        }

        $subject = $this->subject;

        foreach ($data as $key => $value) {
            $subject = str_replace("{{{$key}}}", (string) $value, $subject);
        }

        return $subject;
    }
}
