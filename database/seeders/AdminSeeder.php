<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@tinotech.vn'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('super_admin');

        // Create other default users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin.user@tinotech.vn',
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@tinotech.vn',
                'role' => 'manager',
            ],
            [
                'name' => 'Support User',
                'email' => 'support@tinotech.vn',
                'role' => 'support',
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@tinotech.vn',
                'role' => 'viewer',
            ],
            [
                'name' => 'Learner User',
                'email' => 'learner@tinotech.vn',
                'role' => 'learner',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ])
            );

            $user->assignRole($role);

            if ($role === 'learner') {
                $user->ensureStats();
            }
        }
    }
}
