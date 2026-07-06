<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\BlogCategoryRepositoryInterface;
use Modules\Common\Entities\BlogCategory;
use Illuminate\Support\Facades\Storage;

class BlogCategoryService
{
    protected $repository;

    public function __construct(BlogCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data, $imageFile = null)
    {
        if (empty($data['slug'])) {
            $data['slug'] = BlogCategory::generateSlug($data['name']);
        }

        if ($imageFile) {
            $data['image'] = $imageFile->store('blog-categories', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, $imageFile = null)
    {
        $cat = $this->repository->findById($id);

        if (empty($data['slug'])) {
            $data['slug'] = BlogCategory::generateSlug($data['name'], $id);
        }

        if ($imageFile) {
            if ($cat->image) {
                Storage::disk('public')->delete($cat->image);
            }
            $data['image'] = $imageFile->store('blog-categories', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function getActive()
    {
        return $this->repository->getActive();
    }
}
