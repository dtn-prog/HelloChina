<?php

namespace App\Core\Dashboard\Widgets;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserStatsWidget implements WidgetContract
{
    public function getId(): string
    {
        return 'user_stats';
    }

    public function getName(): string
    {
        return 'User Statistics';
    }

    public function getDescription(): string
    {
        return 'User registration trends';
    }

    public function getGroup(): string
    {
        return 'metrics';
    }

    public function getWidth(): int
    {
        return 6;
    }

    public function getHeight(): string
    {
        return 'md';
    }

    public function getData(): array
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\User::whereDate('created_at', $date)->count();
            $last7Days->push([
                'date' => $date->format('M d'),
                'count' => $count,
            ]);
        }

        $statusCounts = \App\Models\User::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->pluck('total', 'name')
            ->toArray();

        return [
            'registration_trend' => $last7Days->toArray(),
            'status_distribution' => $statusCounts,
            'role_distribution' => $roleCounts,
        ];
    }

    public function getView(): string
    {
        return 'components.dashboard.widgets.user-stats';
    }

    public function canAccess($user): bool
    {
        return $user->can('dashboard.view') && $user->can('users.view');
    }
}
