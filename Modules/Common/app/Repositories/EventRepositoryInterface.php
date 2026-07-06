<?php

namespace Modules\Common\Repositories;

interface EventRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function toggleFeatured(int $id);
    public function getUpcoming(int $limit = 5);
    public function getFeatured(int $limit = 6);
}
