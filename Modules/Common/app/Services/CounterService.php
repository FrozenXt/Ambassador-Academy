<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\CounterRepositoryInterface;
use Modules\Common\Repositories\CounterRepository;

class CounterService
{
    protected $repository;

    public function __construct(CounterRepositoryInterface $repository)
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
        $data['order'] = $data['order'] ?? 0;
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $data['order'] = $data['order'] ?? 0;
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

    public function getActive()
    {
        return $this->repository->getActive();
    }

    public function updateOrder(array $items)
    {
        return $this->repository->updateOrder($items);
    }
    public function getAllOrdered()
    {
        return $this->repository->getAllOrdered();
    }
}
