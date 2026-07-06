<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Faq;

class FaqRepository implements FaqRepositoryInterface
{
    protected $model;

    public function __construct(Faq $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->orderBy('order')->orderBy('id');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('question', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('answer',   'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', (bool) $filters['is_featured']);
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
        $faq = $this->findById($id);
        $faq->update($data);
        return $faq;
    }

    public function delete(int $id)
    {
        $faq = $this->findById($id);
        $faq->delete();
        return true;
    }

    public function toggleStatus(int $id)
    {
        $faq = $this->findById($id);
        $faq->update([
            'status' => $faq->status === 'active' ? 'inactive' : 'active'
        ]);
        return $faq;
    }

    public function toggleFeatured(int $id)
    {
        $faq = $this->findById($id);
        $faq->update(['is_featured' => !$faq->is_featured]);
        return $faq;
    }

    public function getActive()
    {
        return $this->model
            ->where('status', 'active')
            ->orderBy('order')
            ->get();
    }

    public function getFeatured(int $limit = 10)
    {
        return $this->model
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('order')
            ->take($limit)
            ->get();
    }

    public function getCategories()
    {
        return $this->model
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->where('status', 'active')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public function updateOrder(array $items)
    {
        foreach ($items as $item) {
            $this->model->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }
        return true;
    }
}
