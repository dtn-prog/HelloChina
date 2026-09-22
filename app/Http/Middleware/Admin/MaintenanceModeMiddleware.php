<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Setting\Services\SettingService;

class MaintenanceModeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceMode = app(SettingService::class)->get('maintenance_mode', false);

        if ($maintenanceMode && !$request->user()?->can('settings.view')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'System is under maintenance.'], 503);
            }
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
