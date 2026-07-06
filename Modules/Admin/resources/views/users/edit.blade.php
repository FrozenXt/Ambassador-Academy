@extends('admin::layouts.app')
@section('page_title', 'Edit User')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit — {{ $user->name }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- Avatar --}}
                            <div class="col-md-12 text-center mb-3">
                                @if ($user->avatar)
                                    <div class="position-relative d-inline-block">
                                        <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}"
                                            class="img-circle d-block mx-auto"
                                            style="width:80px;height:80px;object-fit:cover;" />
                                    </div>
                                    <div class="mt-1">
                                        <form action="{{ route('admin.users.remove-avatar', $user->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Remove avatar?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                <i class="fas fa-times mr-1"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div id="avatarPlaceholder"
                                        class="img-circle d-flex align-items-center
                                             justify-content-center text-white
                                             font-weight-bold mx-auto"
                                        style="width:80px;height:80px;font-size:1.8rem;
                                            background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <img id="avatarPreview" src="#" class="img-circle d-none mx-auto d-block"
                                        style="width:80px;height:80px;object-fit:cover;" />
                                @endif
                                <div class="mt-2">
                                    <label class="btn btn-sm btn-outline-primary mb-0" style="cursor:pointer;">
                                        <i class="fas fa-camera mr-1"></i>
                                        {{ $user->avatar ? 'Change Photo' : 'Upload Photo' }}
                                        <input type="file" name="avatar" class="d-none"
                                            accept="image/jpeg,image/png,image/webp" onchange="previewAvatar(this)" />
                                    </label>
                                </div>
                                @error('avatar')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordInput"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Leave empty to keep current" autocomplete="new-password" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePwd('passwordInput','eyeIcon1')">
                                                <i class="fas fa-eye" id="eyeIcon1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="confirmInput"
                                            class="form-control" placeholder="Re-enter new password" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePwd('confirmInput','eyeIcon2')">
                                                <i class="fas fa-eye" id="eyeIcon2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror"
                                        onchange="updateRoleInfo(this.value)">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="roleInfo" class="mt-2">
                                        <div class="alert alert-info py-1 px-2 mb-0 small" id="roleInfoText"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active"
                                            {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <hr>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info sidebar --}}
        <div class="col-md-4">
            <div class="card card-outline card-secondary mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> User Info
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted pl-3">ID</td>
                            <td><code>#{{ $user->id }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3">Current Role</td>
                            <td>
                                <span
                                    class="badge badge-{{ $user->roles->first()?->name == 'admin'
                                        ? 'warning'
                                        : ($user->roles->first()?->name == 'manager'
                                            ? 'info'
                                            : 'secondary') }}">
                                    {{ ucfirst($user->roles->first()?->name ?? 'none') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3">Status</td>
                            <td>
                                <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3">Joined</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3">Permissions</td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $user->getAllPermissions()->count() }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                        onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm btn-block">
                            <i class="fas fa-trash mr-1"></i> Delete User
                        </button>
                    </form>
                </div>
            </div>

            {{-- Permissions list --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key mr-2"></i> Current Permissions
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="max-height:250px;overflow-y:auto;">
                    @foreach ($user->getAllPermissions()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'other') as $group => $perms)
                        <div class="mb-2">
                            <small class="text-muted font-weight-bold text-uppercase">{{ $group }}</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @foreach ($perms as $perm)
                                    <span class="badge badge-light border" style="font-size:.7rem;">
                                        {{ explode(' ', $perm->name)[0] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function previewAvatar(input) {
            var preview = document.getElementById('avatarPreview');
            var placeholder = document.getElementById('avatarPlaceholder');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePwd(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        var roleDescriptions = {
            'admin': '🛡️ Admin — Full content management. Cannot delete users or change email settings.',
            'manager': '👔 Manager — Can create and edit all content. Cannot delete or manage users.',
            'staff': '👤 Staff — Read-only. Can upload media but cannot create or edit content.',
        };

        function updateRoleInfo(role) {
            var text = document.getElementById('roleInfoText');
            if (role && roleDescriptions[role]) {
                text.textContent = roleDescriptions[role];
            } else {
                text.textContent = '';
            }
        }

        // Init on load
        updateRoleInfo('{{ old('role', $user->roles->first()?->name ?? '') }}');
    </script>
@endsection
