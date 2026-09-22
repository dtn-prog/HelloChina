<?php

namespace App\Core\Audit\Services;

use Spatie\Activitylog\Facades\Activitylog;
use Spatie\Activitylog\Models\Activity;

class AuditService
{
    public function log(string $description, $subject = null, ?string $logName = 'default', array $properties = []): ?Activity
    {
        $properties = array_merge($properties, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return activity($logName)
            ->performedOn($subject)
            ->withProperties($properties)
            ->event($properties['event'] ?? null)
            ->log($description);
    }

    public function logLogin($user, string $ipAddress, string $userAgent): void
    {
        $this->log('User logged in', $user, 'auth', [
            'event' => 'login',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public function logLogout($user): void
    {
        $this->log('User logged out', $user, 'auth', [
            'event' => 'logout',
        ]);
    }

    public function logCreate($subject, array $attributes = []): void
    {
        $this->log("Created {$subject->getMorphClass()}", $subject, 'default', [
            'event' => 'created',
            'attributes' => $attributes,
        ]);
    }

    public function logUpdate($subject, array $old = [], array $new = []): void
    {
        $this->log("Updated {$subject->getMorphClass()}", $subject, 'default', [
            'event' => 'updated',
            'old' => $old,
            'attributes' => $new,
        ]);
    }

    public function logDelete($subject): void
    {
        $this->log("Deleted {$subject->getMorphClass()}", $subject, 'default', [
            'event' => 'deleted',
        ]);
    }

    public function logPermissionChange($user, string $action, array $details = []): void
    {
        $this->log("Permission {$action}", $user, 'security', array_merge([
            'event' => "permission_{$action}",
        ], $details));
    }

    public function logRoleChange($user, string $action, array $details = []): void
    {
        $this->log("Role {$action}", $user, 'security', array_merge([
            'event' => "role_{$action}",
        ], $details));
    }

    public function getRecentActivities(int $limit = 50, ?string $logName = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Activity::query()->latest();

        if ($logName) {
            $query->where('log_name', $logName);
        }

        return $query->limit($limit)->get();
    }

    public function getActivitiesForSubject($subject, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getActivitiesByUser($userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('causer_type', 'App\\Models\\User')
            ->where('causer_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
