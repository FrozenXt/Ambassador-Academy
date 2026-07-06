<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\UserManagementService;
use Modules\Common\Entities\User;

class UserManagementController extends Controller
{
    protected $service;

    public function __construct(UserManagementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorizeSuper();

        $filters = $request->only(['search', 'role', 'status']);
        $users   = $this->service->getPaginated($filters);
        $stats   = $this->service->getStats();
        $roles   = $this->service->getRoles();

        return view('admin::users.index', compact('users', 'stats', 'filters', 'roles'));
    }

    public function create()
    {
        $this->authorizeSuper();
        $roles = $this->service->getRoles();
        return view('admin::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuper();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,manager,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->service->create(
            $request->only(['name', 'email', 'password', 'role', 'phone', 'status']),
            $request->file('avatar')
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User created and role assigned successfully.');
    }

    public function edit(int $id)
    {
        $this->authorizeSuper();
        $user  = $this->service->findById($id);
        $roles = $this->service->getRoles();
        return view('admin::users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeSuper();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,manager,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->service->update(
            $id,
            $request->only(['name', 'email', 'password', 'role', 'phone', 'status']),
            $request->file('avatar')
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        $user = $this->service->findById($id);

        // Prevent deleting superadmin
        if ($user->hasRole('superadmin')) {
            abort(403, 'Cannot delete a Super Admin.');
        }

        $this->service->delete($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(int $id)
    {
        $this->authorizeSuper();
        $this->service->toggleStatus($id);
        return back()->with('success', 'User status updated.');
    }

    public function removeAvatar(int $id)
    {
        $this->authorizeSuper();
        $this->service->removeAvatar($id);
        return back()->with('success', 'Avatar removed.');
    }

    /**
     * Only superadmin can access
     */
    private function authorizeSuper()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            abort(403, 'Unauthorized.');
        }

        $user = User::find($adminId);
        if (!$user || !$user->hasRole('superadmin')) {
            abort(403, 'Only Super Admin can manage users.');
        }
    }
}
