<?php

namespace Modules\Common\Repositories;

interface MediaRepositoryInterface
{
    public function paginate(int $perPage = 24, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByType(string $type);
    public function search(string $query);
    public function getTotalSize();
    public function getStats();
}
