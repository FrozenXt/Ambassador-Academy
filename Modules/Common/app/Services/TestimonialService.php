<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\TestimonialRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class TestimonialService
{
    protected $repository;

    public function __construct(TestimonialRepositoryInterface $repository)
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

    public function create(array $data, $avatarFile = null)
    {
        if ($avatarFile) {
            $data['avatar'] = $avatarFile->store('testimonials', 'public');
        }

        $data['is_featured'] = isset($data['is_featured']) ? true : false;


        if (!isset($data['order'])) {
            $lastOrder = $this->repository->getAll()->max('order') ?? 0;
            $data['order'] = $lastOrder + 1;
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, $avatarFile = null)
    {
        $testimonial = $this->repository->findById($id);

        if ($avatarFile) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $data['avatar'] = $avatarFile->store('testimonials', 'public');
        }

        $data['is_featured'] = isset($data['is_featured']) ? true : false;

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        $testimonial = $this->repository->findById($id);
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
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

    public function getFeatured(int $limit = 6)
    {
        return $this->repository->getFeatured($limit);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }
    public function updateOrder(array $orders)
    {
        return $this->repository->updateOrder($orders);
    }
}
