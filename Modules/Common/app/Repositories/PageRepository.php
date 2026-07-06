<?php

namespace Modules\Common\Repositories;

use  Modules\Common\Entities\Page;

class PageRepository implements PageRepositoryInterface
{
    protected $model;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [])
    {
        return $this->model->latest()->get();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with('creator');

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%')
                ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['layout'])) {
            $query->where('layout', $filters['layout']);
        }

        return $query->orderBy('order')->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(int $id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $page = $this->findById($id);
        $page->update($data);
        return $page;
    }

    public function delete(int $id)
    {
        $page = $this->findById($id);
        $page->delete();
        return true;
    }

    public function restore(int $id)
    {
        $page = $this->model->withTrashed()->findOrFail($id);
        $page->restore();
        return true;
    }

    public function forceDelete(int $id)
    {
        $page = $this->model->withTrashed()->findOrFail($id);
        $page->forceDelete();
        return true;
    }

    public function getTrashed()
    {
        return $this->model->onlyTrashed()->latest()->get();
    }
}
