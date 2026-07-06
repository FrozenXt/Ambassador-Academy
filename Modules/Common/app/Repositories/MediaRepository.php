<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Media;

class MediaRepository implements MediaRepositoryInterface
{
    protected $model;

    public function __construct(Media $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 24, array $filters = [])
    {
        $query = $this->model->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('alt_text', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['extension'])) {
            $query->where('extension', $filters['extension']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $media = $this->findById($id);
        $media->update($data);
        return $media;
    }

    public function delete(int $id)
    {
        $media = $this->findById($id);
        $media->delete();
        return true;
    }

    public function getByType(string $type)
    {
        return $this->model->where('type', $type)->latest()->get();
    }

    public function search(string $query)
    {
        return $this->model->where('name', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->latest()
            ->get();
    }

    public function getTotalSize()
    {
        return $this->model->sum('size');
    }

    public function getStats()
    {
        return [
            'total'     => $this->model->count(),
            'images'    => $this->model->where('type', 'image')->count(),
            'videos'    => $this->model->where('type', 'video')->count(),
            'documents' => $this->model->where('type', 'document')->count(),
            'others'    => $this->model->where('type', 'other')->count(),
            'total_size' => $this->getTotalSize(),
        ];
    }
}
