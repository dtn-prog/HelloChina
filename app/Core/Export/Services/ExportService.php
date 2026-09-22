<?php

namespace App\Core\Export\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    public function exportCsv(Builder $query, array $columns, string $filename): StreamedResponse
    {
        return response()->stream(function () use ($query, $columns) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, array_keys($columns));

            // Data
            $query->each(function ($row) use ($handle, $columns) {
                $data = [];
                foreach ($columns as $key => $label) {
                    $data[] = $this->getNestedValue($row, $key);
                }
                fputcsv($handle, $data);
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportJson(Builder $query, string $filename): StreamedResponse
    {
        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, '[');

            $first = true;
            $query->each(function ($row) use ($handle, &$first) {
                if (!$first) {
                    fwrite($handle, ',');
                }
                fwrite($handle, $row->toJson());
                $first = false;
            });

            fwrite($handle, ']');
            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportToTempFile(Builder $query, array $columns, string $format, string $disk = 'local'): string
    {
        $filename = 'exports/' . uniqid('export_') . ".{$format}";
        $path = storage_path("app/{$filename}");

        $handle = fopen($path, 'w');

        if ($format === 'csv') {
            fputcsv($handle, array_keys($columns));
            $query->each(function ($row) use ($handle, $columns) {
                $data = [];
                foreach ($columns as $key => $label) {
                    $data[] = $this->getNestedValue($row, $key);
                }
                fputcsv($handle, $data);
            });
        }

        fclose($handle);

        return $filename;
    }

    public function parseCsv(string $content): array
    {
        $rows = array_map('str_getcsv', explode("\n", $content));
        $headers = array_shift($rows);

        return collect($rows)->map(function ($row) use ($headers) {
            return array_combine($headers, $row);
        })->toArray();
    }

    public function parseJson(string $content): array
    {
        return json_decode($content, true) ?? [];
    }

    protected function getNestedValue($row, string $key)
    {
        $keys = explode('.', $key);
        $value = $row;

        foreach ($keys as $k) {
            if (is_object($value)) {
                $value = $value->{$k} ?? null;
            } elseif (is_array($value)) {
                $value = $value[$k] ?? null;
            } else {
                return null;
            }
        }

        return $value;
    }
}
