<?php

namespace Modules\Common\Services;

use Modules\Common\Entities\Service;
use Modules\Common\Repositories\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ServiceService implements ServiceServiceInterface
{
    protected $repository;

    public function __construct(ServiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedServices(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function getServiceById($id)
    {
        return $this->repository->findById($id);
    }

    public function getServiceBySlug(string $slug)
    {
        return $this->repository->findBySlug($slug);
    }

    public function createService(array $data, $imageFile = null)
    {
        // Generate slug
        if (empty($data['slug'])) {
            $data['slug'] = Service::generateSlug($data['title']);
        }

        // Handle image upload
        if ($imageFile) {
            $data['image'] = $imageFile->store('services', 'public');
        }

        // Set meta data if empty
        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'];
        }

        return $this->repository->create($data);
    }

    public function updateService(int $id, array $data, $imageFile = null)
    {
        $service = $this->repository->findById($id);

        // Generate slug if changed
        if (empty($data['slug']) || $data['slug'] !== $service->slug) {
            $data['slug'] = Service::generateSlug($data['title'], $id);
        }

        // Handle image upload
        if ($imageFile) {
            // Delete old image
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $imageFile->store('services', 'public');
        }

        // Set meta data if empty
        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'];
        }

        return $this->repository->update($id, $data);
    }

    public function deleteService(int $id)
    {
        return $this->repository->delete($id);
    }

    public function restoreService(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDeleteService(int $id)
    {
        return $this->repository->forceDelete($id);
    }

    public function getTrashedServices()
    {
        return $this->repository->getTrashed();
    }

    public function updateServiceOrder(array $orders)
    {
        return $this->repository->updateOrder($orders);
    }

    public function toggleServiceStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function removeImage(int $id)
    {
        return $this->repository->removeImage($id);
    }
    
    public function updateServiceImage(int $id, UploadedFile $image)
    {
        return $this->repository->updateImage($id, $image);
    }
}