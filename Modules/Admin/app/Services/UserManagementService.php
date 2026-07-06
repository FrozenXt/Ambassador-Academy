<?php

namespace Modules\Admin\Services;

use Modules\Admin\Repositories\UserManagementRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagementService
{
    protected $repository;

    public function __construct(UserManagementRepositoryInterface $repository)
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
        $role = $data['role'];

        $user = $this->repository->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role,
            'phone'    => $data['phone'] ?? null,
            'status'   => $data['status'] ?? 'active',
            'is_admin' => true,
            'avatar'   => $avatarFile
                ? $avatarFile->store('avatars', 'public')
                : null,
        ]);

        // Assign Spatie role
        $user->syncRoles($role);

        return $user;
    }

    public function update(int $id, array $data, $avatarFile = null)
    {
        $user = $this->repository->findById($id);

        $updateData = [
            'name'   => $data['name'],
            'email'  => $data['email'],
            'role'   => $data['role'],
            'phone'  => $data['phone'] ?? null,
            'status' => $data['status'] ?? 'active',
        ];

        // Update password only if provided
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        // Handle avatar
        if ($avatarFile) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $avatarFile->store('avatars', 'public');
        }

        $user = $this->repository->update($id, $updateData);

        // Sync Spatie role
        $user->syncRoles($data['role']);

        return $user;
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function getRoles()
    {
        return $this->repository->getRoles();
    }

    public function getStats()
    {
        return $this->repository->getStats();
    }

    public function removeAvatar(int $id)
    {
        $user = $this->repository->findById($id);
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $this->repository->update($id, ['avatar' => null]);
        }
        return true;
    }
}
