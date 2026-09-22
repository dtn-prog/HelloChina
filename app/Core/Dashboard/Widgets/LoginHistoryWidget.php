<?php

namespace App\Core\Dashboard\Widgets;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginHistoryWidget implements WidgetContract
{
    public function getId(): string
    {
        return 'login_history';
    }

    public function getName(): string
    {
        return 'Login History';
    }

    public function getDescription(): string
    {
        return 'Recent login attempts';
    }

    public function getGroup(): string
    {
        return 'monitoring';
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
        $recentLogins = DB::table('login_history')
            ->leftJoin('users', 'users.id', '=', 'login_history.user_id')
            ->select(
                'login_history.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('login_history.created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        $failedToday = DB::table('login_history')
            ->where('status', 'failed')
            ->whereDate('created_at', today())
            ->count();

        $successToday = DB::table('login_history')
            ->where('status', 'success')
            ->whereDate('created_at', today())
            ->count();

        $suspiciousIPs = DB::table('login_history')
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subHours(24))
            ->select('ip_address', DB::raw('count(*) as attempts'))
            ->groupBy('ip_address')
            ->having('attempts', '>=', 5)
            ->get()
            ->toArray();

        return [
            'recent_logins' => $recentLogins,
            'failed_today' => $failedToday,
            'success_today' => $successToday,
            'suspicious_ips' => $suspiciousIPs,
        ];
    }

    public function getView(): string
    {
        return 'components.dashboard.widgets.login-history';
    }

    public function canAccess($user): bool
    {
        return $user->can('dashboard.view') && $user->can('login_history.view');
    }
}
