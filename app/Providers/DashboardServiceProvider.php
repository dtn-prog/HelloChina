<?php

namespace App\Providers;

use App\Core\Dashboard\Services\DashboardService;
use App\Core\Dashboard\Widgets\KPIWidget;
use App\Core\Dashboard\Widgets\ActivityWidget;
use App\Core\Dashboard\Widgets\UserStatsWidget;
use App\Core\Dashboard\Widgets\SystemHealthWidget;
use App\Core\Dashboard\Widgets\LoginHistoryWidget;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DashboardService::class, function ($app) {
            $service = new DashboardService();

            // Register default widgets
            $service->register('kpi', KPIWidget::class);
            $service->register('activity', ActivityWidget::class);
            $service->register('user_stats', UserStatsWidget::class);
            $service->register('system_health', SystemHealthWidget::class);
            $service->register('login_history', LoginHistoryWidget::class);

            return $service;
        });
    }

    public function boot(): void
    {
        //
    }
}
