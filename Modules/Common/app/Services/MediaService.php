<?php

namespace Modules\Common\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Common\Entities\Media;

class MediaService
{
    /**
     * Get paginated media with filters
     */
    public function getPaginatedMedia(array $filters = [], int $perPage = 24)
    {
        $query = Media::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('alt_text', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Apply type filter
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }

        // Apply extension filter
        if (!empty($filters['extension'])) {
            $query->where('extension', $filters['extension']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get media statistics
     */
    public function getStats(): array
    {
        return [
            'total' => Media::count(),
            'images' => Media::where('type', 'image')->count(),
            'videos' => Media::where('type', 'video')->count(),
            'documents' => Media::where('type', 'document')->count(),
            'others' => Media::where('type', 'other')->count(),
            'total_size' => Media::sum('file_size'),
            'formatted_size' => $this->formatBytes(Media::sum('file_size'))
        ];
    }

    /**
     * Get media by ID
     */
    public function getMediaById(int $id): Media
    {
        return Media::findOrFail($id);
    }

    /**
     * Upload a single file
     */
    public function uploadFile($file, array $metadata = []): Media
    {

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = $this->generateFileName($originalName, $extension);
        $filePath = $file->storeAs('media', $fileName, 'public');

        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Determine file type
        $type = $this->determineFileType($mimeType, $extension);

        // Get image dimensions if it's an image
        $dimensions = $this->getImageDimensions($filePath, $type);

        // Process tags
        $tags = null;
        if (!empty($metadata['tags'])) {
            $tags = array_map('trim', explode(',', $metadata['tags']));
        }

        $path = $file->store('media', 'public');
        return Media::create([
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_url' => asset('storage/' . $path),
            'file_size' => $fileSize,
            'size'       => $file->getSize(),
            'mime_type' => $mimeType,
            'extension' => $extension,
            'type' => $type,
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'alt_text' => $metadata['alt_text'] ?? null,
            'title' => $metadata['title'] ?? null,
            'description' => $metadata['description'] ?? null,
            'tags' => $tags,
        ]);
    }

    /**
     * Update media metadata
     */
    public function updateMedia(int $id, array $data): Media
    {
        $media = Media::findOrFail($id);

        // Process tags if present
        if (isset($data['tags'])) {
            $data['tags'] = $data['tags'] ? array_map('trim', explode(',', $data['tags'])) : null;
        }

        $media->update($data);

        return $media->fresh();
    }

    /**
     * Delete a single media file
     */
    public function deleteMedia(int $id): bool
    {
        $media = Media::findOrFail($id);

        // Delete the physical file
        Storage::disk('public')->delete($media->file_path);

        // Delete the database record
        return $media->delete();
    }

    /**
     * Bulk delete media files
     */
    public function bulkDelete(array $ids): int
    {
        $medias = Media::whereIn('id', $ids)->get();
        $count = 0;

        foreach ($medias as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
            $count++;
        }

        return $count;
    }

    /**
     * Generate unique filename
     */
    protected function generateFileName(string $originalName, string $extension): string
    {
        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        return $baseName . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Determine file type based on mime type and extension
     */
    protected function determineFileType(string $mimeType, string $extension): string
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        }

        if (strpos($mimeType, 'video/') === 0) {
            return 'video';
        }

        $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv', 'ppt', 'pptx'];
        if (in_array(strtolower($extension), $documentExtensions)) {
            return 'document';
        }

        return 'other';
    }

    /**
     * Get image dimensions if applicable
     */
    protected function getImageDimensions(string $filePath, string $type): array
    {
        $dimensions = ['width' => null, 'height' => null];

        if ($type === 'image') {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                $imageInfo = getimagesize($fullPath);
                if ($imageInfo) {
                    $dimensions['width'] = $imageInfo[0];
                    $dimensions['height'] = $imageInfo[1];
                }
            }
        }

        return $dimensions;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get all media for API/JSON responses
     */
    public function getAllMedia(array $filters = [])
    {
        $query = Media::query();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->get();
    }

    /**
     * Get media by type
     */
    public function getMediaByType(string $type, int $limit = null)
    {
        $query = Media::where('type', $type);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->latest()->get();
    }

    /**
     * Search media by tags
     */
    public function searchByTags(array $tags)
    {
        return Media::whereJsonContains('tags', $tags)->get();
    }
}
