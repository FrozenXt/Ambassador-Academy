<?php

namespace Modules\Common\Repositories;

interface CounterRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function getActive();
    public function updateOrder(array $items);
    public function getAllOrdered();
}
