@extends('admin::layouts.app')
@section('page_title', 'User Management')

@section('page_actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add User
    </a>
@endsection

@section('admin_content')

    {{-- Stats --}}
    {{-- <div class="row mb-4">
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-info mb-0">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-success mb-0">
                <div class="inner">
                    <h3>{{ $stats['active'] }}</h3>
                    <p>Active</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-danger mb-0">
                <div class="inner">
                    <h3>{{ $stats['inactive'] }}</h3>
                    <p>Inactive</p>
                </div>
                <div class="icon"><i class="fas fa-user-times"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-warning mb-0">
                <div class="inner">
                    <h3>{{ $stats['admins'] }}</h3>
                    <p>Admins</p>
                </div>
                <div class="icon"><i class="fas fa-user-shield"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-primary mb-0">
                <div class="inner">
                    <h3>{{ $stats['managers'] }}</h3>
                    <p>Managers</p>
                </div>
                <div class="icon"><i class="fas fa-user-tie"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="small-box bg-gradient-secondary mb-0">
                <div class="inner">
                    <h3>{{ $stats['staff'] }}</h3>
                    <p>Staff</p>
                </div>
                <div class="icon"><i class="fas fa-user-cog"></i></div>
            </div>
        </div>
    </div> --}}

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-3">
            <form action="{{ route('admin.users.index') }}" method="GET">

                <div class="row g-2 align-items-center">

                    {{-- Search --}}
                    <div class="col-md-auto">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            class="form-control form-control-sm" placeholder="Search name or email...">
                    </div>

                    {{-- Role --}}
                    <div class="col-md-auto">
                        <select name="role" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ ($filters['role'] ?? '') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-auto">
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-auto d-flex px-0">
                        <button type="submit" class="btn btn-primary btn-sm m-0">
                            <i class="fas fa-search mr-1"></i> Search
                        </button>

                        <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-sm m-0">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-2"></i> Admin Users
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $users->total() }} users</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:50px;">Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="img-circle elevation-1"
                                        style="width:38px;height:38px;object-fit:cover;" />
                                @else
                                    <div class="img-circle d-flex align-items-center
                                        justify-content-center text-white font-weight-bold"
                                        style="width:38px;height:38px;font-size:.85rem;
                                        background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $user->name }}</div>
                                @if ($user->roles->first())
                                    <small class="text-muted">
                                        {{ ucfirst($user->roles->first()->name) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="small">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                <small>{{ $user->phone ?? '—' }}</small>
                            </td>
                            <td>
                                @php
                                    $roleColors = [
                                        'superadmin' => 'danger',
                                        'admin' => 'warning',
                                        'manager' => 'info',
                                        'staff' => 'secondary',
                                    ];
                                    $userRole = $user->roles->first()?->name ?? 'staff';
                                    $roleColor = $roleColors[$userRole] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $roleColor }} px-2 py-1">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    {{ ucfirst($userRole) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-xs btn-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d M Y') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-users fa-3x d-block mb-3 opacity-25"></i>
                                No users found.
                                <a href="{{ route('admin.users.create') }}">Add one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Role Permissions Overview --}}
    <div class="card card-outline card-secondary mt-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shield-alt mr-2"></i> Role Permissions Overview
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ([['name' => 'superadmin', 'color' => 'danger', 'icon' => 'fas fa-crown', 'label' => 'Super Admin', 'desc' => 'Full access to everything including user management and all settings.'], ['name' => 'admin', 'color' => 'warning', 'icon' => 'fas fa-user-shield', 'label' => 'Admin', 'desc' => 'Full content access. Cannot delete users or change email settings.'], ['name' => 'manager', 'color' => 'info', 'icon' => 'fas fa-user-tie', 'label' => 'Manager', 'desc' => 'Can create and edit all content. Cannot delete content or manage users.'], ['name' => 'staff', 'color' => 'secondary', 'icon' => 'fas fa-user-cog', 'label' => 'Staff', 'desc' => 'Read-only access. Can upload media and view all sections.']] as $r)
                    <div class="col-md-3">
                        <div class="card border-{{ $r['color'] }} mb-0">
                            <div class="card-header bg-{{ $r['color'] }} text-white py-2">
                                <i class="{{ $r['icon'] }} mr-2"></i>
                                <strong>{{ $r['label'] }}</strong>
                            </div>
                            <div class="card-body py-2">
                                <p class="small text-muted mb-0">{{ $r['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
