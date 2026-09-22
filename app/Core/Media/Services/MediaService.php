<?php

namespace App\Core\Media\Services;

use App\Core\Media\Models\Media;
use App\Core\Media\Models\MediaFolder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaService
{
    public function upload(UploadedFile $file, ?int $folderId = null, ?int $userId = null, string $disk = 'public'): Media
    {
        $fileName = $file->getClientOriginalName();
        $path = $file->store($this->getStoragePath($folderId), $disk);

        $media = Media::create([
            'name' => pathinfo($fileName, PATHINFO_FILENAME),
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'path' => $path,
            'disk' => $disk,
            'folder_id' => $folderId,
            'user_id' => $userId,
            'meta' => $this->extractMeta($file),
        ]);

        return $media;
    }

    public function uploadImage(UploadedFile $file, ?int $folderId = null, ?int $userId = null, array $sizes = []): Media
    {
        $media = $this->upload($file, $folderId, $userId);

        if (!empty($sizes) && str_starts_with($file->getMimeType(), 'image/')) {
            $this->generateSizes($media, $sizes);
        }

        return $media;
    }

    public function delete(Media $media): bool
    {
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        return $media->delete();
    }

    public function getUrl(Media $media): string
    {
        if ($media->disk === 'local') {
            return asset('storage/' . $media->path);
        }

        return Storage::disk($media->disk)->url($media->path);
    }

    public function getSignedUrl(Media $media, int $expiration = 60): string
    {
        return Storage::disk($media->disk)->temporaryUrl($media->path, now()->addMinutes($expiration));
    }

    public function getFolderContents(?int $folderId = null): array
    {
        $folders = MediaFolder::where('parent_id', $folderId)->get();
        $media = Media::where('folder_id', $folderId)->get();

        return [
            'folders' => $folders,
            'media' => $media,
        ];
    }

    public function createFolder(string $name, ?int $parentId = null, ?int $userId = null): MediaFolder
    {
        return MediaFolder::create([
            'name' => $name,
            'parent_id' => $parentId,
            'user_id' => $userId,
        ]);
    }

    public function deleteFolder(MediaFolder $folder): bool
    {
        foreach ($folder->children as $child) {
            $this->deleteFolder($child);
        }

        foreach ($folder->media as $media) {
            $this->delete($media);
        }

        return $folder->delete();
    }

    public function search(string $query, ?string $type = null, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        $search = Media::where('name', 'LIKE', "%{$query}%")
            ->orWhere('file_name', 'LIKE', "%{$query}%");

        if ($type) {
            $search->where('mime_type', 'LIKE', "{$type}%");
        }

        return $search->limit($limit)->get();
    }

    protected function getStoragePath(?int $folderId): string
    {
        if (!$folderId) {
            return 'uploads';
        }

        $folder = MediaFolder::find($folderId);
        return 'uploads/' . $folder->path;
    }

    protected function extractMeta(UploadedFile $file): array
    {
        $meta = [];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $meta['width'] = $imageInfo[0];
                $meta['height'] = $imageInfo[1];
            }
        }

        return $meta;
    }

    protected function generateSizes(Media $media, array $sizes): void
    {
        // TODO: Implement image resizing with Intervention Image
    }
}
