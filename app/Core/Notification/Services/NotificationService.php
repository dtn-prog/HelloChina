<?php

namespace App\Core\Notification\Services;

use App\Core\Notification\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendInApp(User $user, string $title, string $body, ?string $url = null): void
    {
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\AdminNotification',
            'data' => [
                'title' => $title,
                'body' => $body,
                'url' => $url,
            ],
        ]);
    }

    public function sendFromTemplate(string $templateName, User $user, array $data = [], ?string $channel = null): void
    {
        $template = NotificationTemplate::active()->where('name', $templateName)->first();

        if (!$template) {
            return;
        }

        $channel = $channel ?? $template->channel;

        match ($channel) {
            'in_app' => $this->sendInApp($user, $template->renderSubject($data), $template->render($data)),
            'email' => $this->sendEmail($user, $template->renderSubject($data), $template->render($data)),
            default => null,
        };
    }

    public function sendEmail(User $user, string $subject, string $body): void
    {
        // TODO: Implement queue-based email sending
        // Mail::to($user->email)->queue(new SendEmailJob($subject, $body));
    }

    public function sendBulkInApp(array $userIds, string $title, string $body, ?string $url = null): void
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $this->sendInApp($user, $title, $body, $url);
        }
    }

    public function getUnreadCount(User $user): int
    {
        return $user->notifications()->whereNull('read_at')->count();
    }

    public function markAsRead(User $user, string $notificationId): void
    {
        $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
    }

    public function markAllAsRead(User $user): void
    {
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
    }

    public function getRecent(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $user->notifications()->latest()->limit($limit)->get();
    }
}
