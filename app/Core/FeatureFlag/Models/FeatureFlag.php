<?php

namespace App\Core\FeatureFlag\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = [
        'name',
        'key',
        'description',
        'enabled',
        'rollout_percentage',
        'conditions',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'rollout_percentage' => 'integer',
        'conditions' => 'array',
    ];

    public function isEnabled(): bool
    {
        return $this->enabled && $this->rollout_percentage > 0;
    }

    public function isAvailableFor($user = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        if ($this->rollout_percentage === 100) {
            return true;
        }

        if (!$user) {
            return false;
        }

        $hash = hash('crc32', $user->id . $this->key);
        $bucket = abs($hash) % 100;

        return $bucket < $this->rollout_percentage;
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeAvailableFor($query, $user)
    {
        return $query->enabled()->where('rollout_percentage', '>', 0);
    }
}
