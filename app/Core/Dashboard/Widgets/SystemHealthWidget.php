<?php

namespace App\Core\Dashboard\Widgets;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SystemHealthWidget implements WidgetContract
{
    public function getId(): string
    {
        return 'system_health';
    }

    public function getName(): string
    {
        return 'System Health';
    }

    public function getDescription(): string
    {
        return 'System components status';
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
        return 'sm';
    }

    public function getData(): array
    {
        $checks = [];

        // Database
        try {
            DB::connection()->getPdo();
            $checks[] = ['name' => 'Database', 'status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Database', 'status' => 'critical', 'message' => $e->getMessage()];
        }

        // Redis
        try {
            if (class_exists(\Redis::class) && config('database.redis.client')) {
                \Illuminate\Support\Facades\Redis::ping();
                $checks[] = ['name' => 'Redis', 'status' => 'healthy', 'message' => 'Connected'];
            } else {
                $checks[] = ['name' => 'Redis', 'status' => 'warning', 'message' => 'Extension not installed'];
            }
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Redis', 'status' => 'warning', 'message' => 'Not available'];
        }

        // Queue
        try {
            $jobs = DB::table('jobs')->count();
            $checks[] = ['name' => 'Queue', 'status' => $jobs > 100 ? 'warning' : 'healthy', 'message' => "{$jobs} jobs pending"];
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Queue', 'status' => 'warning', 'message' => 'Not available'];
        }

        // Failed Jobs
        $failedJobs = DB::table('failed_jobs')->count();
        $checks[] = ['name' => 'Failed Jobs', 'status' => $failedJobs > 0 ? 'warning' : 'healthy', 'message' => "{$failedJobs} failed"];

        // Storage
        $diskFree = @disk_free_space(storage_path());
        if ($diskFree !== false) {
            $freeGB = round($diskFree / 1073741824, 2);
            $checks[] = ['name' => 'Storage', 'status' => $freeGB < 1 ? 'critical' : 'healthy', 'message' => "{$freeGB} GB free"];
        } else {
            $checks[] = ['name' => 'Storage', 'status' => 'unknown', 'message' => 'Unable to check'];
        }

        // Memory
        $memoryUsage = round(memory_get_usage(true) / 1048576, 2);
        $checks[] = ['name' => 'Memory', 'status' => $memoryUsage > 256 ? 'warning' : 'healthy', 'message' => "{$memoryUsage} MB used"];

        $healthy = collect($checks)->where('status', 'healthy')->count();
        $total = count($checks);

        return [
            'checks' => $checks,
            'summary' => [
                'healthy' => $healthy,
                'total' => $total,
                'percentage' => $total > 0 ? round($healthy / $total * 100) : 0,
            ],
        ];
    }

    public function getView(): string
    {
        return 'components.dashboard.widgets.system-health';
    }

    public function canAccess($user): bool
    {
        return $user->can('dashboard.view');
    }
}
