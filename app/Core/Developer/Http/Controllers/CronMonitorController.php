<?php

namespace App\Core\Developer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

class CronMonitorController extends Controller
{
    public function index(Request $request)
    {
        $scheduleEvents = $this->getScheduledEvents();

        if ($request->expectsJson()) {
            return response()->json(['data' => $scheduleEvents]);
        }

        return view('pages.developer.cron.index', ['events' => $scheduleEvents]);
    }

    public function runEvent(Request $request, string $command)
    {
        try {
            Artisan::call($command);
            $output = Artisan::output();

            return back()->with('success', "Command executed: {$output}");
        } catch (\Exception $e) {
            return back()->with('error', "Failed: {$e->getMessage()}");
        }
    }

    protected function getScheduledEvents(): array
    {
        $events = [];

        try {
            $scheduled = Schedule::events();

            foreach ($scheduled as $event) {
                $events[] = [
                    'command' => $event->command ?? 'Closure',
                    'description' => $event->description ?? '',
                    'frequency' => $event->expression ?? '',
                    'next_run' => $event->nextRunAt ?? null,
                    'timezone' => $event->timezone ?? config('app.timezone'),
                ];
            }
        } catch (\Exception $e) {
            // Schedule might not be fully initialized
        }

        // Add default commands
        $defaults = [
            ['command' => 'schedule:run', 'description' => 'Run scheduled tasks', 'frequency' => '* * * * *'],
            ['command' => 'queue:work', 'description' => 'Process queue jobs', 'frequency' => 'Continuous'],
            ['command' => 'queue:restart', 'description' => 'Restart queue workers', 'frequency' => 'Daily'],
            ['command' => 'cache:prune-stale-tags', 'description' => 'Prune stale cache tags', 'frequency' => 'Hourly'],
        ];

        return array_merge($events, $defaults);
    }
}
