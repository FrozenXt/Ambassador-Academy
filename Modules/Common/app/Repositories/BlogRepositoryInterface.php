<?php

namespace Modules\Common\Repositories;

interface BlogRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function restore(int $id);
    public function forceDelete(int $id);
    public function getTrashed();
    public function toggleStatus(int $id);
    public function toggleFeatured(int $id);
    public function getPublished(int $limit = 10);
    public function getFeatured(int $limit = 3);
    public function getRelated(int $blogId, int $categoryId, int $limit = 3);
    public function incrementViews(int $id);
    public function getRecent($limit);
    public function getLatestPublished();
    public function queryPublished(array $filters = []);
}
