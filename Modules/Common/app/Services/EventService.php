<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\EventRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Modules\Common\Entities\Event;

class EventService
{
    protected $repository;

    public function __construct(EventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data, $imageFile = null)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Event::generateSlug($data['title']);
        } else {
            // If slug is provided, make it URL friendly and check uniqueness
            $data['slug'] = Event::generateSlug($data['slug']);
        }

        if ($imageFile) {
            $data['image'] = $imageFile->store('events', 'public');
        }

        $data['is_featured'] = isset($data['is_featured']) ? true : false;

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, $imageFile = null)
    {
        $event = $this->repository->findById($id);

        // Generate slug if not provided or empty
        if (empty($data['slug'])) {
            $data['slug'] = Event::generateSlug($data['title'], $id);
        } else {
            // If slug is provided, make it URL friendly and check uniqueness
            $data['slug'] = Event::generateSlug($data['slug'], $id);
        }

        if ($imageFile) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $imageFile->store('events', 'public');
        }

        $data['is_featured'] = isset($data['is_featured']) ? true : false;

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        $event = $this->repository->findById($id);
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        return $this->repository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function toggleFeatured(int $id)
    {
        return $this->repository->toggleFeatured($id);
    }

    public function getUpcoming(int $limit = 5)
    {
        return $this->repository->getUpcoming($limit);
    }

    public function getFeatured(int $limit = 6)
    {
        return $this->repository->getFeatured($limit);
    }

    public function removeImage(int $id)
    {
        $event = $this->repository->findById($id);
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        return $this->repository->update($id, ['image' => null]);
    }
}
