@extends('admin::layouts.app')
@section('page_title', 'Add User')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2"></i> Add New User
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Avatar preview --}}
                            <div class="col-md-12 text-center mb-3">
                                <div id="avatarPlaceholder"
                                    class="img-circle d-flex align-items-center
                                         justify-content-center text-white font-weight-bold mx-auto"
                                    style="width:80px;height:80px;font-size:1.8rem;
                                        background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                    <i class="fas fa-user"></i>
                                </div>
                                <img id="avatarPreview" src="#" class="img-circle d-none mx-auto d-block"
                                    style="width:80px;height:80px;object-fit:cover;" />
                                <div class="mt-2">
                                    <label class="btn btn-sm btn-outline-primary mb-0" style="cursor:pointer;">
                                        <i class="fas fa-camera mr-1"></i> Upload Photo
                                        <input type="file" name="avatar" class="d-none"
                                            accept="image/jpeg,image/png,image/webp" onchange="previewAvatar(this)" />
                                    </label>
                                </div>
                                <small class="text-muted d-block">Optional. JPG, PNG. Max 2MB.</small>
                                @error('avatar')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. Rajesh Hamal" />
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
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="user@example.com" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordInput"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Min 8 characters" autocomplete="new-password" />
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
                                    <label class="font-weight-bold">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="confirmInput"
                                            class="form-control" placeholder="Re-enter password" />
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
                                        <option value="">— Select Role —</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    {{-- Role info badge --}}
                                    <div id="roleInfo" class="mt-2 d-none">
                                        <div class="alert alert-info py-1 px-2 mb-0 small" id="roleInfoText"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="form-control" placeholder="+977-98..." />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active"
                                            {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Create User & Assign Role
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Help --}}
        <div class="col-md-4">
            <div class="card card-outline card-info mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i> Role Permissions
                    </h3>
                </div>
                <div class="card-body p-0">
                    @foreach ([['role' => 'admin', 'color' => 'warning', 'icon' => 'fas fa-user-shield', 'perms' => ['Full content management', 'Manage all modules', 'Cannot delete users', 'Cannot change email settings']], ['role' => 'manager', 'color' => 'info', 'icon' => 'fas fa-user-tie', 'perms' => ['Create & edit content', 'Cannot delete content', 'Upload media', 'Reply to contacts']], ['role' => 'staff', 'color' => 'secondary', 'icon' => 'fas fa-user-cog', 'perms' => ['View all sections', 'Upload media only', 'Cannot create or edit', 'Read-only access']]] as $r)
                        <div class="p-3 border-bottom">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-{{ $r['color'] }} mr-2">
                                    <i class="{{ $r['icon'] }} mr-1"></i>
                                    {{ ucfirst($r['role']) }}
                                </span>
                            </div>
                            <ul class="list-unstyled mb-0 small">
                                @foreach ($r['perms'] as $perm)
                                    <li class="mb-1">
                                        <i class="fas fa-check text-success mr-1"></i>
                                        {{ $perm }}
                                    </li>
                                @endforeach
                            </ul>
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
                    placeholder.style.display = 'none';
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
            'admin': '🛡️ Admin — Full content management. Can manage all modules except user deletion and email settings.',
            'manager': '👔 Manager — Can create and edit all content. Cannot delete or manage users.',
            'staff': '👤 Staff — Read-only access. Can upload media files but cannot create or edit content.',
        };

        function updateRoleInfo(role) {
            var box = document.getElementById('roleInfo');
            var text = document.getElementById('roleInfoText');
            if (role && roleDescriptions[role]) {
                text.textContent = roleDescriptions[role];
                box.classList.remove('d-none');
            } else {
                box.classList.add('d-none');
            }
        }

        // Trigger on page load if old() value
        @if (old('role'))
            updateRoleInfo('{{ old('role') }}');
        @endif
    </script>
@endsection
