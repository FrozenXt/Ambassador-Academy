<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Gallery;
use Illuminate\Support\Facades\DB;

class GalleryRepository implements GalleryRepositoryInterface
{
    protected $model;

    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage = 15, $albumId = null)
    {
        $query = $this->model->with('album')->ordered();

        if ($albumId) {
            $query->where('album_id', $albumId);
        }

        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with('album')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $gallery = $this->findById($id);
        $gallery->update($data);
        return $gallery;
    }

    public function delete($id)
    {
        $gallery = $this->findById($id);
        return $gallery->delete();
    }

    public function bulkCreate(array $items)
    {
        return DB::transaction(function () use ($items) {
            $created = [];
            foreach ($items as $item) {
                $created[] = $this->model->create($item);
            }
            return $created;
        });
    }

    public function bulkDelete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return $this->model->whereIn('id', $ids)->delete();
        });
    }

    public function updateSortOrder(array $orders)
    {
        return DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                $this->model->where('id', $order['id'])
                    ->update(['sort_order' => $order['sort_order']]);
            }
            return true;
        });
    }

    public function getStatistics($albumId = null)
    {
        $query = $this->model->query();

        if ($albumId) {
            $query->where('album_id', $albumId);
        }

        return [
            'total' => $query->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'inactive' => (clone $query)->where('status', 'inactive')->count(),
            'featured' => (clone $query)->where('is_featured', true)->count(),
        ];
    }

    public function getByAlbum($albumId, $perPage = 15)
    {
        return $this->model->where('album_id', $albumId)
            ->ordered()
            ->paginate($perPage);
    }

    public function getFeatured($limit = 10)
    {
        return $this->model->featured()
            ->active()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function updateStatus($id, $status)
    {
        $gallery = $this->findById($id);
        $gallery->status = $status;
        $gallery->save();
        return $gallery;
    }

    public function toggleFeatured($id)
    {
        $gallery = $this->findById($id);
        $gallery->is_featured = !$gallery->is_featured;
        $gallery->save();
        return $gallery;
    }
}
