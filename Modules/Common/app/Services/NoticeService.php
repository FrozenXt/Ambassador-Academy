<?php


namespace Modules\Common\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Common\Repositories\NoticeRepositoryInterface;
use Modules\Common\Entities\Notice;

class NoticeService
{
    protected $repository;

    public function __construct(NoticeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function search($term, $perPage = 15)
    {
        return $this->repository->search($term, $perPage);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findBySlug($slug)
    {
        $notice = $this->repository->findBySlug($slug);
        $notice->incrementViews();
        return $notice;
    }

    public function create(array $data)
    {
        if (isset($data['featured_image']) && $data['featured_image']) {
            $data['featured_image'] = $this->uploadImage($data['featured_image']);
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        if (empty($data['published_date'])) {
            $data['published_date'] = now();
        }

        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $notice = $this->find($id);

        if (isset($data['featured_image']) && $data['featured_image']) {
            if ($notice->featured_image) {
                $this->deleteImage($notice->featured_image);
            }
            $data['featured_image'] = $this->uploadImage($data['featured_image']);
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        $notice = $this->find($id);
        if ($notice->featured_image) {
            $this->deleteImage($notice->featured_image);
        }
        return $this->repository->delete($id);
    }

    public function toggleStatus($id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function toggleFeatured($id)
    {
        return $this->repository->toggleFeatured($id);
    }



    public function getStatistics()
    {
        return $this->repository->getStatistics();
    }

    protected function uploadImage($image)
    {
        $fileName = 'notice_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('notices', $fileName, 'public');
        return $path;
    }

    protected function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
