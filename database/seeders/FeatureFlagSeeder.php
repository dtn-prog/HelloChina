<?php

namespace Database\Seeders;

use App\Core\FeatureFlag\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $flags = [
            ['name' => 'New Dashboard', 'key' => 'new_dashboard', 'description' => 'Enable the new dashboard UI', 'enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'AI Features', 'key' => 'ai_features', 'description' => 'Enable AI-powered features', 'enabled' => false, 'rollout_percentage' => 0],
            ['name' => 'Wallet System', 'key' => 'wallet', 'description' => 'Enable wallet functionality', 'enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'Offerwall', 'key' => 'offerwall', 'description' => 'Enable offerwall feature', 'enabled' => false, 'rollout_percentage' => 0],
            ['name' => 'Maintenance Payment', 'key' => 'maintenance_payment', 'description' => 'Enable payment maintenance mode', 'enabled' => false, 'rollout_percentage' => 0],
            ['name' => 'New Booking Flow', 'key' => 'new_booking_flow', 'description' => 'Enable new booking flow', 'enabled' => true, 'rollout_percentage' => 50],
            ['name' => 'Push Notifications', 'key' => 'push_notifications', 'description' => 'Enable push notifications', 'enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'Real-time Updates', 'key' => 'realtime_updates', 'description' => 'Enable real-time updates via WebSocket', 'enabled' => false, 'rollout_percentage' => 0],
        ];

        foreach ($flags as $flag) {
            FeatureFlag::updateOrCreate(
                ['key' => $flag['key']],
                $flag
            );
        }
    }
}
