<?php

namespace App\Core\Setting\Services;

use App\Core\Setting\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::getValue($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        Setting::setValue($key, $value);
    }

    public function getGroup(string $group): array
    {
        return Setting::getGroup($group);
    }

    public function setGroup(string $group, array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'group' => $group,
                ]
            );
            Cache::forget("setting_{$key}");
        }
    }

    public function getPublicSettings(): array
    {
        return Setting::getPublicSettings();
    }

    public function forget(string $key): void
    {
        Cache::forget("setting_{$key}");
    }

    public function clearCache(): void
    {
        $settings = Setting::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
    }
}
