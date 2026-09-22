<?php

namespace App\Core\Monitoring\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    public function index(Request $request)
    {
        $checks = $this->runHealthChecks();

        if ($request->expectsJson()) {
            return response()->json(['data' => $checks]);
        }

        return view('pages.monitoring.health', compact('checks'));
    }

    public function check(Request $request)
    {
        $checks = $this->runHealthChecks();

        $allHealthy = collect($checks['checks'])->every(fn ($c) => $c['status'] === 'healthy');

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'degraded',
            'data' => $checks,
        ], $allHealthy ? 200 : 503);
    }

    protected function runHealthChecks(): array
    {
        $checks = [];

        // Database
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 2);
            $checks[] = [
                'name' => 'Database',
                'status' => $latency > 1000 ? 'warning' : 'healthy',
                'message' => "Connected ({$latency}ms)",
                'latency' => $latency,
            ];
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Database', 'status' => 'critical', 'message' => $e->getMessage()];
        }

        // Redis
        try {
            $start = microtime(true);
            Redis::ping();
            $latency = round((microtime(true) - $start) * 1000, 2);
            $checks[] = [
                'name' => 'Redis',
                'status' => 'healthy',
                'message' => "Connected ({$latency}ms)",
                'latency' => $latency,
            ];
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Redis', 'status' => 'warning', 'message' => 'Not available'];
        }

        // Queue
        try {
            $pending = DB::table('jobs')->count();
            $checks[] = [
                'name' => 'Queue',
                'status' => $pending > 100 ? 'warning' : 'healthy',
                'message' => "{$pending} jobs pending",
            ];
        } catch (\Exception $e) {
            $checks[] = ['name' => 'Queue', 'status' => 'warning', 'message' => 'Not available'];
        }

        // Failed Jobs
        $failedJobs = DB::table('failed_jobs')->count();
        $checks[] = [
            'name' => 'Failed Jobs',
            'status' => $failedJobs > 0 ? 'warning' : 'healthy',
            'message' => "{$failedJobs} failed",
        ];

        // Storage
        $diskFree = @disk_free_space(storage_path());
        if ($diskFree !== false) {
            $freeGB = round($diskFree / 1073741824, 2);
            $checks[] = [
                'name' => 'Storage',
                'status' => $freeGB < 1 ? 'critical' : ($freeGB < 5 ? 'warning' : 'healthy'),
                'message' => "{$freeGB} GB free",
            ];
        } else {
            $checks[] = ['name' => 'Storage', 'status' => 'unknown', 'message' => 'Unable to check'];
        }

        // Memory
        $memoryUsage = round(memory_get_usage(true) / 1048576, 2);
        $memoryLimit = round(ini_get('memory_limit') / 1024, 2);
        $checks[] = [
            'name' => 'Memory',
            'status' => $memoryUsage > 256 ? 'warning' : 'healthy',
            'message' => "{$memoryUsage} MB / {$memoryLimit} MB",
        ];

        // CPU
        $load = sys_getloadavg();
        $checks[] = [
            'name' => 'CPU Load',
            'status' => $load[0] > 80 ? 'warning' : 'healthy',
            'message' => sprintf('%.1f / %.1f / %.1f', $load[0], $load[1], $load[2]),
        ];

        // PHP Version
        $checks[] = [
            'name' => 'PHP Version',
            'status' => 'healthy',
            'message' => PHP_VERSION,
        ];

        // Laravel Version
        $checks[] = [
            'name' => 'Laravel Version',
            'status' => 'healthy',
            'message' => app()->version(),
        ];

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
}
