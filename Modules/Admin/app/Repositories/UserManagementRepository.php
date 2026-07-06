<?php

namespace Modules\Admin\Repositories;

use Modules\Common\Entities\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;



class UserManagementRepository implements UserManagementRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {

        $query = $this->model->with('roles');
        $currentId = null;

        if (Auth::check()) { // <-- no red line here
            $currentId = Auth::id(); // <-- no red line here
        } elseif (session()->has('admin_id')) {
            $currentId = session('admin_id');
        }
        if ($currentId) {
            $query->where('id', '!=', $currentId);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name',  'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(int $id)
    {
        return $this->model->with('roles')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->findById($id);
        $user->delete();
        return true;
    }

    public function toggleStatus(int $id)
    {
        $user = $this->findById($id);
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);
        return $user;
    }

    public function getRoles()
    {
        // Superadmin cannot be assigned by anyone except itself
        return Role::whereNotIn('name', ['superadmin'])->get();
    }

    public function getStats()
    {
        return [
            'total'    => $this->model->where('is_admin', true)->count(),
            'active'   => $this->model->where('is_admin', true)->where('status', 'active')->count(),
            'inactive' => $this->model->where('is_admin', true)->where('status', 'inactive')->count(),
            'admins'   => $this->model->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'managers' => $this->model->whereHas('roles', fn($q) => $q->where('name', 'manager'))->count(),
            'staff'    => $this->model->whereHas('roles', fn($q) => $q->where('name', 'staff'))->count(),
        ];
    }
}
