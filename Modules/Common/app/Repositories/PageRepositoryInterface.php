<?php

namespace Modules\Common\Repositories;

interface PageRepositoryInterface
{
    public function getAll(array $filters = []);
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function restore(int $id);
    public function forceDelete(int $id);
    public function getTrashed();
}
