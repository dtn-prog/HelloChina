<?php

namespace App\Core\Security\Services;

use App\Core\Auth\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SecurityService
{
    public function getFailedLogins(int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return LoginHistory::failed()
            ->recent($hours)
            ->with('user')
            ->latest()
            ->get();
    }

    public function getBlockedIPs(): array
    {
        return Cache::get('blocked_ips', []);
    }

    public function blockIP(string $ip, int $minutes = 60): void
    {
        $blocked = $this->getBlockedIPs();
        $blocked[$ip] = now()->addMinutes($minutes)->timestamp;
        Cache::put('blocked_ips', $blocked, now()->addMinutes($minutes));
    }

    public function unblockIP(string $ip): void
    {
        $blocked = $this->getBlockedIPs();
        unset($blocked[$ip]);
        Cache::put('blocked_ips', $blocked);
    }

    public function isIPBlocked(string $ip): bool
    {
        $blocked = $this->getBlockedIPs();

        if (!isset($blocked[$ip])) {
            return false;
        }

        if (now()->timestamp > $blocked[$ip]) {
            $this->unblockIP($ip);
            return false;
        }

        return true;
    }

    public function getSuspiciousActivity(int $hours = 24): array
    {
        $failedLogins = LoginHistory::failed()
            ->recent($hours)
            ->selectRaw('ip_address, COUNT(*) as attempts')
            ->groupBy('ip_address')
            ->having('attempts', '>=', 5)
            ->get();

        return [
            'suspicious_ips' => $failedLogins,
            'blocked_ips' => $this->getBlockedIPs(),
            'recent_failures' => $this->getFailedLogins($hours)->count(),
        ];
    }

    public function getPasswordChanges(int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return \Spatie\Activitylog\Models\Activity::where('log_name', 'auth')
            ->where('event', 'password_changed')
            ->where('created_at', '>=', now()->subHours($hours))
            ->with('causer')
            ->latest()
            ->get();
    }

    public function getPermissionChanges(int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return \Spatie\Activitylog\Models\Activity::where('log_name', 'security')
            ->where('created_at', '>=', now()->subHours($hours))
            ->with('causer', 'subject')
            ->latest()
            ->get();
    }

    public function getSecuritySummary(): array
    {
        return [
            'failed_logins_24h' => LoginHistory::failed()->recent(24)->count(),
            'blocked_ips' => count($this->getBlockedIPs()),
            'suspicious_activity' => $this->getSuspiciousActivity(),
            'recent_password_changes' => $this->getPasswordChanges()->count(),
            'recent_permission_changes' => $this->getPermissionChanges()->count(),
        ];
    }
}
