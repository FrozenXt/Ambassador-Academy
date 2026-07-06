<?php
// Modules/Common/Repositories/AlbumRepositoryInterface.php

namespace Modules\Common\Repositories;

interface AlbumRepositoryInterface
{
    public function getAll();
    public function getPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getStatistics();
    public function findByCodeWithGalleries(string $code);
    public function getLatestGalleryByAlbumCode(string $code);
}
