<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Album;
use Modules\Common\Entities\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AlbumRepository implements AlbumRepositoryInterface
{
    protected $model;

    public function __construct(Album $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->ordered()->get();
    }
    public function getPaginated(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->ordered();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['cover_image']) && $data['cover_image']) {
                $data['cover_image'] = $this->uploadImage($data['cover_image']);
            }
            // $data['code'] = $this->generateUniqueCode();
            $album = $this->model->create($data);

            DB::commit();
            return $album;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create album: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            DB::beginTransaction();

            $album = $this->findById($id);

            if (isset($data['cover_image']) && $data['cover_image']) {
                if ($album->cover_image) {
                    $this->deleteImage($album->cover_image);
                }
                $data['cover_image'] = $this->uploadImage($data['cover_image']);
            }

            $album->update($data);

            DB::commit();
            return $album;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update album: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $album = $this->findById($id);

            if ($album->cover_image) {
                $this->deleteImage($album->cover_image);
            }

            return $album->delete();
        } catch (\Exception $e) {
            Log::error('Failed to delete album: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getStatistics()
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'inactive' => $this->model->where('status', 'inactive')->count(),
            'featured' => $this->model->where('is_featured', true)->count(),
        ];
    }

    protected function uploadImage($image)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('albums/covers', $filename, 'public');
        return $path;
    }

    protected function deleteImage($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    // protected function generateUniqueCode()
    // {
    //     do {
    //         $code = 'ALB-' . strtoupper(Str::random(6));
    //     } while ($this->model->where('code', $code)->exists());

    //     return $code;
    // }
    public function findByCodeWithGalleries(string $code)
    {
        return Album::where('code', $code)
            ->with(['galleries' => fn($q) => $q->orderBy('sort_order')])
            ->first();
    }

    public function getLatestGalleryByAlbumCode(string $code)
    {
        return Gallery::whereHas('album', fn($q) => $q->where('code', $code))
            ->where('status', 'active')
            ->latest()
            ->first();
    }
}
