<?php

namespace App\Core\Dashboard\Widgets;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KPIWidget implements WidgetContract
{
    public function getId(): string
    {
        return 'kpi';
    }

    public function getName(): string
    {
        return 'Key Performance Indicators';
    }

    public function getDescription(): string
    {
        return 'Overview of key metrics';
    }

    public function getGroup(): string
    {
        return 'metrics';
    }

    public function getWidth(): int
    {
        return 12;
    }

    public function getHeight(): string
    {
        return 'sm';
    }

    public function getData(): array
    {
        $totalUsers = DB::table('users')->count();
        $activeUsers = DB::table('users')->where('status', 'active')->count();
        $newUsersToday = DB::table('users')->whereDate('created_at', today())->count();
        $totalRoles = DB::table('roles')->count();

        return [
            'metrics' => [
                [
                    'label' => 'Total Users',
                    'value' => $totalUsers,
                    'change' => $this->calculateChange('users', 'total'),
                    'icon' => 'users',
                    'color' => 'blue',
                ],
                [
                    'label' => 'Active Users',
                    'value' => $activeUsers,
                    'change' => $this->calculateChange('users', 'active'),
                    'icon' => 'check-circle',
                    'color' => 'green',
                ],
                [
                    'label' => 'New Today',
                    'value' => $newUsersToday,
                    'change' => 0,
                    'icon' => 'user-plus',
                    'color' => 'purple',
                ],
                [
                    'label' => 'Roles',
                    'value' => $totalRoles,
                    'change' => 0,
                    'icon' => 'shield',
                    'color' => 'orange',
                ],
            ],
        ];
    }

    public function getView(): string
    {
        return 'components.dashboard.widgets.kpi';
    }

    public function canAccess($user): bool
    {
        return $user->can('dashboard.view');
    }

    protected function calculateChange(string $table, string $type): float
    {
        $today = DB::table($table)->whereDate('created_at', today())->count();
        $yesterday = DB::table($table)->whereDate('created_at', now()->subDay())->count();

        if ($yesterday === 0) {
            return $today > 0 ? 100 : 0;
        }

        return round(($today - $yesterday) / $yesterday * 100, 1);
    }
}
