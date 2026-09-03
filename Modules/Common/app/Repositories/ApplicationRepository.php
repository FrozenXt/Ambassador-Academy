<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Application;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    protected $model;

    public function __construct(Application $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('guardian_name', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['applying_for'])) {
            $query->where('applying_for', $filters['applying_for']);
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
        $application = $this->findById($id);
        $application->update($data);
        return $application;
    }

    public function delete(int $id)
    {
        $application = $this->findById($id);
        $application->delete();
        return true;
    }

    public function markAsRead(int $id)
    {
        return $this->model->where('id', $id)
            ->where('status', 'unread')
            ->update(['status' => 'read']);
    }

    public function reply(int $id, string $reply)
    {
        return $this->model->where('id', $id)->update([
            'admin_reply' => $reply,
            'status'      => 'replied',
            'replied_at'  => now(),
        ]);
    }

    public function getStats()
    {
        return [
            'total'   => $this->model->count(),
            'unread'  => $this->model->where('status', 'unread')->count(),
            'read'    => $this->model->where('status', 'read')->count(),
            'replied' => $this->model->where('status', 'replied')->count(),
        ];
    }

    public function getUnreadCount()
    {
        return $this->model->where('status', 'unread')->count();
    }

    public function bulkDelete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function bulkMarkRead(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 'read']);
    }
}
