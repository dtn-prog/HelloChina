<?php

namespace Database\Seeders;

use App\Core\Level\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $maxLevel = 50;

        for ($level = 1; $level <= $maxLevel; $level++) {
            Level::updateOrCreate(
                ['level' => $level],
                ['required_xp' => $level * 100]
            );
        }
    }
}
