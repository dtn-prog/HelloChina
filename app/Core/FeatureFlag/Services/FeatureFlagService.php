<?php

namespace App\Core\FeatureFlag\Services;

use App\Core\FeatureFlag\Models\FeatureFlag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FeatureFlagService
{
    public function isEnabled(string $key, ?User $user = null): bool
    {
        $flag = $this->getFlag($key);

        if (!$flag) {
            return false;
        }

        if ($user) {
            return $flag->isAvailableFor($user);
        }

        return $flag->isEnabled();
    }

    public function getFlag(string $key): ?FeatureFlag
    {
        return Cache::remember("feature_flag_{$key}", 300, function () use ($key) {
            return FeatureFlag::where('key', $key)->first();
        });
    }

    public function getAll(): array
    {
        return FeatureFlag::all()->toArray();
    }

    public function getEnabled(): array
    {
        return FeatureFlag::enabled()->get()->toArray();
    }

    public function enable(string $key): void
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->update(['enabled' => true]);
            Cache::forget("feature_flag_{$key}");
        }
    }

    public function disable(string $key): void
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->update(['enabled' => false]);
            Cache::forget("feature_flag_{$key}");
        }
    }

    public function setRollout(string $key, int $percentage): void
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->update(['rollout_percentage' => max(0, min(100, $percentage))]);
            Cache::forget("feature_flag_{$key}");
        }
    }

    public function create(array $data): FeatureFlag
    {
        $flag = FeatureFlag::create($data);
        Cache::forget("feature_flag_{$flag->key}");
        return $flag;
    }

    public function update(string $key, array $data): FeatureFlag
    {
        $flag = FeatureFlag::where('key', $key)->firstOrFail();
        $flag->update($data);
        Cache::forget("feature_flag_{$key}");
        return $flag;
    }

    public function delete(string $key): void
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->delete();
            Cache::forget("feature_flag_{$key}");
        }
    }

    public function clearCache(): void
    {
        $flags = FeatureFlag::all();
        foreach ($flags as $flag) {
            Cache::forget("feature_flag_{$flag->key}");
        }
    }
}
