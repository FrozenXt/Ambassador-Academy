<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\GalleryRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class GalleryService implements GalleryServiceInterface
{
    protected $galleryRepository;

    public function __construct(GalleryRepositoryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    public function getPaginatedGallery($perPage = 15, $albumId = null)
    {
        return $this->galleryRepository->getAll($perPage, $albumId);
    }

    public function getGalleryItemById($id)
    {
        return $this->galleryRepository->findById($id);
    }

    public function createGalleryItem(array $data)
    {
        if (isset($data['image'])) {
            $data['image_path'] = $this->uploadImage($data['image']);
            unset($data['image']);
        }

        if (isset($data['metadata'])) {
            $data['metadata'] = json_decode($data['metadata'], true);
        }

        return $this->galleryRepository->create($data);
    }

    public function updateGalleryItem($id, array $data)
    {
        $gallery = $this->galleryRepository->findById($id);

        if (isset($data['image'])) {
            // Delete old image
            if ($gallery->image_path) {
                $this->deleteImage($gallery->image_path);
            }
            $data['image_path'] = $this->uploadImage($data['image']);
            unset($data['image']);
        }

        if (isset($data['metadata'])) {
            $data['metadata'] = json_decode($data['metadata'], true);
        }

        return $this->galleryRepository->update($id, $data);
    }

    public function deleteGalleryItem($id)
    {
        $gallery = $this->galleryRepository->findById($id);

        // Delete image files
        if ($gallery->image_path) {
            $this->deleteImage($gallery->image_path);
        }

        return $this->galleryRepository->delete($id);
    }

    public function bulkUpload(array $files, $albumId)
    {
        $items = [];

        foreach ($files as $file) {
            try {
                $imagePath = $this->uploadImage($file);

                $items[] = [
                    'album_id' => $albumId,
                    'image_path' => $imagePath,
                    'status' => 'active',
                    'sort_order' => $this->getNextSortOrder()
                ];
            } catch (\Exception $e) {
                Log::error('Failed to upload file: ' . $e->getMessage());
                continue;
            }
        }

        return $this->galleryRepository->bulkCreate($items);
    }

    public function bulkDelete(array $ids)
    {
        // Delete image files first
        foreach ($ids as $id) {
            try {
                $gallery = $this->galleryRepository->findById($id);
                if ($gallery->image_path) {
                    $this->deleteImage($gallery->image_path);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete image file: ' . $e->getMessage());
            }
        }

        return $this->galleryRepository->bulkDelete($ids);
    }

    public function updateSortOrder(array $orders)
    {
        return $this->galleryRepository->updateSortOrder($orders);
    }

    public function getGalleryStatistics($albumId = null)
    {
        $stats = $this->galleryRepository->getStatistics($albumId);
        $stats['total_size'] = $this->getTotalGallerySize();

        return $stats;
    }

    protected function uploadImage($image)
    {
        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $path = 'gallery/' . date('Y/m/d');
        $fullPath = $path . '/' . $filename;

        // Store original image
        Storage::disk('public')->putFileAs($path, $image, $filename);

        // Create thumbnail
        $this->createThumbnail($fullPath);

        return $fullPath;
    }

    protected function createThumbnail($imagePath)
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);

            if (!file_exists($fullPath)) {
                throw new \Exception("Image file not found: $fullPath");
            }

            $thumbnailPath = dirname($fullPath) . '/thumbnails/' . basename($fullPath);

            if (!file_exists(dirname($thumbnailPath))) {
                mkdir(dirname($thumbnailPath), 0755, true);
            }

            // Intervention Image 4.0 API
            $image = Image::read($fullPath);
            $image->cover(300, 300);
            $image->save($thumbnailPath);

            return true;
        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function deleteImage($imagePath)
    {
        // Delete original
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete thumbnail
        $thumbnailPath = dirname($imagePath) . '/thumbnails/' . basename($imagePath);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        return true;
    }

    protected function getTotalGallerySize()
    {
        try {
            $files = Storage::disk('public')->allFiles('gallery');
            $totalSize = 0;

            foreach ($files as $file) {
                if (strpos($file, '/thumbnails/') === false) {
                    $totalSize += Storage::disk('public')->size($file);
                }
            }

            return $this->formatBytes($totalSize);
        } catch (\Exception $e) {
            Log::error('Failed to calculate gallery size: ' . $e->getMessage());
            return '0 B';
        }
    }

    protected function getNextSortOrder()
    {
        $maxOrder = $this->galleryRepository->getAll(1)->max('sort_order');
        return ($maxOrder ?? 0) + 1;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
