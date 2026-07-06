<?php

namespace Modules\Admin\Repositories;

interface UserManagementRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function getRoles();
    public function getStats();
}
