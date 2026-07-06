<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Counter;

class CounterRepository implements CounterRepositoryInterface
{
    protected $model;

    public function __construct(Counter $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->orderBy('order');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title',       'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
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
        $counter = $this->findById($id);
        $counter->update($data);
        return $counter;
    }

    public function delete(int $id)
    {
        $counter = $this->findById($id);
        $counter->delete();
        return true;
    }

    public function toggleStatus(int $id)
    {
        $counter = $this->findById($id);
        $counter->update([
            'status' => $counter->status === 'active' ? 'inactive' : 'active'
        ]);
        return $counter;
    }

    public function getActive()
    {
        return $this->model
            ->where('status', 'active')
            ->orderBy('order')
            ->get();
    }

    public function updateOrder(array $items)
    {
        foreach ($items as $item) {
            $this->model->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }
        return true;
    }
    public function getAllOrdered()
    {
        return Counter::orderBy('order')->get();
    }
}
