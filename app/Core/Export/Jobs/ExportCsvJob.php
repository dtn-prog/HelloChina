<?php

namespace App\Core\Export\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Core\Export\Services\ExportService;
use App\Models\User;

class ExportCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public string $model,
        public array $columns,
        public array $filters = []
    ) {}

    public function handle(ExportService $exportService): void
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        $modelClass = $this->model;
        $query = $modelClass::query();

        // Apply filters
        foreach ($this->filters as $field => $value) {
            if (is_array($value)) {
                $query->where($field, $value['operator'] ?? '=', $value['value']);
            } else {
                $query->where($field, $value);
            }
        }

        $filename = strtolower(class_basename($this->model)) . '_export_' . now()->format('Y-m-d_His') . '.csv';
        $filePath = $exportService->exportToTempFile($query, $this->columns, 'csv');

        // Notify user
        $user->notify(new \App\Notifications\ExportReadyNotification($filename, $filePath));
    }
}
