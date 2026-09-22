<?php

namespace App\Core\Dashboard\Widgets;

use App\Core\Dashboard\Contracts\WidgetContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityWidget implements WidgetContract
{
    public function getId(): string
    {
        return 'activity';
    }

    public function getName(): string
    {
        return 'Recent Activity';
    }

    public function getDescription(): string
    {
        return 'Latest system activities';
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
        $activities = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($activity) => [
                'id' => $activity->id,
                'description' => $activity->description,
                'causer' => $activity->causer?->name ?? 'System',
                'causer_avatar' => $activity->causer?->avatar_url,
                'subject_type' => class_basename($activity->subject_type ?? ''),
                'event' => $activity->event,
                'time' => $activity->created_at->diffForHumans(),
                'ip_address' => $activity->ip_address,
            ])
            ->toArray();

        return [
            'activities' => $activities,
            'total' => \Spatie\Activitylog\Models\Activity::count(),
        ];
    }

    public function getView(): string
    {
        return 'components.dashboard.widgets.activity';
    }

    public function canAccess($user): bool
    {
        return $user->can('dashboard.view');
    }
}
