<?php
// Modules/Common/Services/AlbumServiceInterface.php

namespace Modules\Common\Services;

interface AlbumServiceInterface
{
    public function getAllAlbums();
    public function getPaginatedAlbums(int $perPage);
    public function getAlbumById(int $id);
    public function createAlbum(array $data);
    public function updateAlbum(int $id, array $data);
    public function deleteAlbum(int $id);
    public function getAlbumStatistics();
}
