<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\BlogCategory;

class BlogCategoryRepository implements BlogCategoryRepositoryInterface
{
    protected $model;

    public function __construct(BlogCategory $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->withCount('blogs')->orderBy('order');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
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
        $cat = $this->findById($id);
        $cat->update($data);
        return $cat;
    }

    public function delete(int $id)
    {
        $cat = $this->findById($id);
        $cat->delete();
        return true;
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')
            ->orderBy('order')
            ->get();
    }
}
