<?php

namespace Modules\Common\Repositories;

use  Modules\Common\Entities\Event;

class EventRepository implements EventRepositoryInterface
{
    protected $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->latest('start_date');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title',    'like', '%' . $filters['search'] . '%')
                    ->orWhere('location', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('organizer', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['period'])) {
            if ($filters['period'] === 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($filters['period'] === 'past') {
                $query->where('start_date', '<', now());
            }
        }
        $query->orderBy('order', 'asc');

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
        $event = $this->findById($id);
        $event->update($data);
        return $event;
    }

    public function delete(int $id)
    {
        $event = $this->findById($id);
        $event->delete();
        return true;
    }

    public function toggleStatus(int $id)
    {
        $event = $this->findById($id);
        $event->update([
            'status' => $event->status === 'published' ? 'draft' : 'published'
        ]);
        return $event;
    }

    public function toggleFeatured(int $id)
    {
        $event = $this->findById($id);
        $event->update(['is_featured' => !$event->is_featured]);
        return $event;
    }

    public function getUpcoming(int $limit = 5)
    {
        return $this->model
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take($limit)
            ->get();
    }

    public function getFeatured(int $limit = 6)
    {
        return $this->model
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('start_date')
            ->take($limit)
            ->get();
    }
}
