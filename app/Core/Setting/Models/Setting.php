<?php

namespace App\Core\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function setValue(string $key, mixed $value): void
    {
        $setting = static::firstOrCreate(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->save();

        Cache::forget("setting_{$key}");
    }

    public static function getGroup(string $group): array
    {
        $settings = static::where('group', $group)->get()->pluck('value', 'key')->toArray();

        return collect($settings)->mapWithKeys(function ($value, $key) {
            $setting = static::where('key', $key)->first();
            return [$key => static::castValue($value, $setting?->type ?? 'text')];
        })->toArray();
    }

    public static function getPublicSettings(): array
    {
        return static::where('is_public', true)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    private static function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
