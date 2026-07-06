<?php
// Modules/Common/Services/AlbumService.php

namespace Modules\Common\Services;

use Illuminate\Support\Facades\DB;
use Modules\Common\Repositories\AlbumRepositoryInterface;
use Modules\Common\Services\AlbumServiceInterface;

class AlbumService implements AlbumServiceInterface
{
    protected $albumRepository;

    public function __construct(AlbumRepositoryInterface $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    public function getAllAlbums()
    {
        return $this->albumRepository->getAll();
    }

    public function getPaginatedAlbums(int $perPage = 15, array $filters = [])
    {
        return $this->albumRepository->getPaginated($perPage, $filters);
    }

    public function getAlbumById(int $id)
    {
        return $this->albumRepository->findById($id);
    }

    public function createAlbum(array $data)
    {
        DB::beginTransaction();
        try {
            $album = $this->albumRepository->create($data);
            DB::commit();
            return $album;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateAlbum(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $album = $this->albumRepository->update($id, $data);
            DB::commit();
            return $album;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteAlbum(int $id)
    {
        return $this->albumRepository->delete($id);
    }

    public function getAlbumStatistics()
    {
        return $this->albumRepository->getStatistics();
    }
    public function getByCodeWithGalleries($code)
    {
        return $this->albumRepository->findByCodeWithGalleries($code);
    }

    public function getLatestGalleryByAlbumCode($code)
    {
        return $this->albumRepository->getLatestGalleryByAlbumCode($code);
    }
}
