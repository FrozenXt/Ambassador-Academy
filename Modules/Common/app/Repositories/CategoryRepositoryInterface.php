<?php

namespace Modules\Common\Repositories;

interface CategoryRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function paginate(int $perPage = 15, array $filters = []);
    public function countBySlugLike(string $slug);
    public function countBySlugLikeExceptId(string $slug, int $id);
}
