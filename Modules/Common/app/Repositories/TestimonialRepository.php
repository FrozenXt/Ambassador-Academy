<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Testimonial;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    protected $model;

    public function __construct(Testimonial $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model
            ->orderBy('order', 'asc')
            ->latest(); // fallback

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name',    'like', '%' . $filters['search'] . '%')
                    ->orWhere('company', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $testimonial = $this->findById($id);
        $testimonial->update($data);
        return $testimonial;
    }

    public function delete(int $id)
    {
        $testimonial = $this->findById($id);
        $testimonial->delete();
        return true;
    }

    public function toggleStatus(int $id)
    {
        $testimonial = $this->findById($id);
        $testimonial->update([
            'status' => $testimonial->status === 'active' ? 'inactive' : 'active'
        ]);
        return $testimonial;
    }

    public function toggleFeatured(int $id)
    {
        $testimonial = $this->findById($id);
        $testimonial->update([
            'is_featured' => !$testimonial->is_featured
        ]);
        return $testimonial;
    }

    public function getFeatured(int $limit = 6)
    {
        return $this->model
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('order', 'asc')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getAll()
    {
        return $this->model
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->latest()
            ->get();
    }
    public function updateOrder(array $orders)
    {
        foreach ($orders as $orderData) {
            $this->model->where('id', $orderData['id'])
                ->update(['order' => $orderData['order']]);
        }
        return true;
    }
}
