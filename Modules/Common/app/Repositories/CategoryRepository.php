<?php

namespace Modules\Common\Repositories;

use \Modules\Common\Entities\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [])
    {
        return $this->model->latest()->get();
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
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id)
    {
        $category = $this->findById($id);
        $category->delete();
        return true;
    }
    public function countBySlugLike(string $slug)
    {
        return $this->model
            ->where('name', 'LIKE', "$slug%")
            ->count();
    }

    public function countBySlugLikeExceptId(string $slug, int $id)
    {
        return $this->model
            ->where('name', 'LIKE', "$slug%")
            ->where('id', '!=', $id)
            ->count();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->withCount('products');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
