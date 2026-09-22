<?php

namespace App\Core\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Media extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'file_size',
        'path',
        'disk',
        'folder_id',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'meta' => 'array',
    ];

    public function getSizeAttribute(): ?int
    {
        return $this->file_size;
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->disk === 'local') {
            return asset('storage/' . $this->path);
        }

        return \Illuminate\Support\Facades\Storage::disk($this->disk)->url($this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    public function isDocument(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function scopeOfFolder($query, ?int $folderId)
    {
        return $query->where('folder_id', $folderId);
    }

    public function scopeOfDisk($query, string $disk)
    {
        return $query->where('disk', $disk);
    }
}
