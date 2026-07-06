<?php

namespace Modules\Admin\Repositories;

use Modules\Common\Entities\User;

class AdminRepository implements AdminRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByEmail(string $email)
    {
        return $this->model
            ->where('email', $email)
            ->where('is_admin', true)
            ->first();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getTotalUsers()
    {
        return $this->model
            ->where('is_admin', false)
            ->count();
    }

    public function getTodayUsers()
    {
        return $this->model
            ->where('is_admin', false)
            ->whereDate('created_at', today())
            ->count();
    }

    public function getThisMonthUsers()
    {
        return $this->model
            ->where('is_admin', false)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function getRecentUsers(int $limit = 10)
    {
        return $this->model
            ->where('is_admin', false)
            ->latest()
            ->take($limit)
            ->get();
    }
}
