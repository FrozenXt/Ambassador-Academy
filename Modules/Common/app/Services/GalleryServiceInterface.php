<?php

namespace Modules\Common\Services;

interface GalleryServiceInterface
{
    public function getPaginatedGallery($perPage = 15, $albumId = null);
    public function getGalleryItemById($id);
    public function createGalleryItem(array $data);
    public function updateGalleryItem($id, array $data);
    public function deleteGalleryItem($id);
    public function bulkUpload(array $files, $albumId);
    public function bulkDelete(array $ids);
    public function updateSortOrder(array $orders);
    public function getGalleryStatistics($albumId = null);
}
