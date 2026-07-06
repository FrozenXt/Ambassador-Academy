<?php

namespace Modules\Common\Repositories;

interface FaqRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function toggleFeatured(int $id);
    public function getActive();
    public function getFeatured(int $limit = 10);
    public function getCategories();
    public function updateOrder(array $items);
}
