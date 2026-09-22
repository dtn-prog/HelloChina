<?php

namespace App\Core\Dashboard\Services;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    protected array $widgets = [];

    public function register(string $id, string $class): void
    {
        $this->widgets[$id] = $class;
    }

    public function getWidget(string $id): ?WidgetContract
    {
        $class = $this->widgets[$id] ?? null;

        if (!$class || !class_exists($class)) {
            return null;
        }

        return app($class);
    }

    public function getAvailableWidgets(User $user): array
    {
        return collect($this->widgets)
            ->map(fn ($class) => app($class))
            ->filter(fn (WidgetContract $widget) => $widget->canAccess($user))
            ->values()
            ->toArray();
    }

    public function getWidgetsForDashboard(User $user): array
    {
        $savedWidgets = Cache::remember(
            "dashboard_widgets_{$user->id}",
            3600,
            fn () => $this->getUserWidgets($user)
        );

        $widgets = [];
        foreach ($savedWidgets as $widgetData) {
            $widget = $this->getWidget($widgetData['id']);
            if ($widget && $widget->canAccess($user)) {
                $widgets[] = [
                    'config' => $widgetData,
                    'instance' => $widget,
                    'data' => $widget->getData(),
                ];
            }
        }

        return $widgets;
    }

    public function saveUserWidgets(User $user, array $widgetIds): void
    {
        $widgets = collect($widgetIds)->map(fn ($id, $index) => [
            'id' => $id,
            'order' => $index,
            'visible' => true,
        ])->toArray();

        cache()->forget("dashboard_widgets_{$user->id}");
        Cache::remember("dashboard_widgets_{$user->id}", 3600, fn () => $widgets);
    }

    protected function getUserWidgets(User $user): array
    {
        return collect($this->widgets)
            ->keys()
            ->map(fn ($id, $index) => [
                'id' => $id,
                'order' => $index,
                'visible' => true,
            ])
            ->values()
            ->toArray();
    }

    public function getDefaultWidgets(): array
    {
        return array_keys($this->widgets);
    }
}
