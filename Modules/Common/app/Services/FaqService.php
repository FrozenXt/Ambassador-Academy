<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\FaqRepositoryInterface;

class FaqService
{
    protected $repository;

    public function __construct(FaqRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data)
    {
        $data['is_featured'] = isset($data['is_featured']) ? true : false;
        $data['order']       = $data['order'] ?? 0;

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $data['is_featured'] = (int) ($data['is_featured'] ?? 0);
        $data['order']       = (int) ($data['order'] ?? 0);

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function toggleFeatured(int $id)
    {
        return $this->repository->toggleFeatured($id);
    }

    public function getActive()
    {
        return $this->repository->getActive();
    }

    public function getFeatured(int $limit = 10)
    {
        return $this->repository->getFeatured($limit);
    }

    public function getCategories()
    {
        return $this->repository->getCategories();
    }

    public function updateOrder(array $items)
    {
        return $this->repository->updateOrder($items);
    }

    public function getGroupedByCategory()
    {
        return $this->repository->getActive()->groupBy('category');
    }
}
