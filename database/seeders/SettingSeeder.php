<?php

namespace Database\Seeders;

use App\Core\Setting\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'Base Admin', 'group' => 'general', 'type' => 'text', 'description' => 'Site name', 'is_public' => true],
            ['key' => 'site_logo', 'value' => null, 'group' => 'general', 'type' => 'text', 'description' => 'Site logo path', 'is_public' => true],
            ['key' => 'site_description', 'value' => 'Admin Platform for TINOTECH', 'group' => 'general', 'type' => 'text', 'description' => 'Site description', 'is_public' => true],
            ['key' => 'support_email', 'value' => 'support@tinotech.vn', 'group' => 'general', 'type' => 'text', 'description' => 'Support email', 'is_public' => false],
            ['key' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh', 'group' => 'general', 'type' => 'text', 'description' => 'Default timezone', 'is_public' => false],
            ['key' => 'default_language', 'value' => 'vi', 'group' => 'general', 'type' => 'text', 'description' => 'Default language', 'is_public' => true],
            ['key' => 'default_currency', 'value' => 'VND', 'group' => 'general', 'type' => 'text', 'description' => 'Default currency', 'is_public' => true],

            // Email
            ['key' => 'email_from_address', 'value' => 'noreply@tinotech.vn', 'group' => 'email', 'type' => 'text', 'description' => 'From address', 'is_public' => false],
            ['key' => 'email_from_name', 'value' => 'Base Admin', 'group' => 'email', 'type' => 'text', 'description' => 'From name', 'is_public' => false],

            // Security
            ['key' => 'max_login_attempts', 'value' => '5', 'group' => 'security', 'type' => 'integer', 'description' => 'Max login attempts before lockout', 'is_public' => false],
            ['key' => 'lockout_duration', 'value' => '15', 'group' => 'security', 'type' => 'integer', 'description' => 'Lockout duration in minutes', 'is_public' => false],
            ['key' => 'require_2fa', 'value' => 'false', 'group' => 'security', 'type' => 'boolean', 'description' => 'Require 2FA for all users', 'is_public' => false],
            ['key' => 'session_lifetime', 'value' => '120', 'group' => 'security', 'type' => 'integer', 'description' => 'Session lifetime in minutes', 'is_public' => false],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'maintenance', 'type' => 'boolean', 'description' => 'Enable maintenance mode', 'is_public' => false],
            ['key' => 'maintenance_message', 'value' => 'We are currently performing maintenance. Please try again later.', 'group' => 'maintenance', 'type' => 'text', 'description' => 'Maintenance message', 'is_public' => true],

            // Registration
            ['key' => 'registration_enabled', 'value' => 'true', 'group' => 'general', 'type' => 'boolean', 'description' => 'Enable user registration', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
