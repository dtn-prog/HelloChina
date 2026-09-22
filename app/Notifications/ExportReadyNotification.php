<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $filename,
        public string $filePath
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Export Ready')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your export file is ready for download.')
            ->line('File: ' . $this->filename)
            ->action('Download', route('export.download', ['file' => $this->filePath]))
            ->line('This link will expire in 24 hours.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'filename' => $this->filename,
            'file_path' => $this->filePath,
            'message' => "Your export file '{$this->filename}' is ready for download.",
            'download_url' => route('export.download', ['file' => $this->filePath]),
        ];
    }
}
