<?php

use Illuminate\Support\Facades\function;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Core\Setting\Models\Setting::getValue($key, $default);
    }
}

if (!function_exists('feature_flag')) {
    function feature_flag(string $key, ?\App\Models\User $user = null): bool
    {
        return app(\App\Core\FeatureFlag\Services\FeatureFlagService::class)->isEnabled($key, $user);
    }
}
