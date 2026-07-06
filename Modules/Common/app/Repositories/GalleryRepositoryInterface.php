<?php

namespace Modules\Common\Repositories;

interface GalleryRepositoryInterface
{
    public function getAll($perPage = 15, $albumId = null);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function bulkCreate(array $items);
    public function bulkDelete(array $ids);
    public function updateSortOrder(array $orders);
    public function getStatistics($albumId = null);
    public function getByAlbum($albumId, $perPage = 15);
    public function getFeatured($limit = 10);
    public function updateStatus($id, $status);
    public function toggleFeatured($id);
}
