@extends('admin::layouts.app')
@section('page_title', 'Create Menu')

@section('page_actions')
    <a href="{{ route('admin.menus.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <form action="{{ route('admin.menus.store') }}" method="POST" id="menuForm">
        @csrf

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-2"></i> Create New Menu
                </h3>
            </div>

            <div class="card-body">
                <div class="row">

                    {{-- Menu Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">Menu Name</label>
                            <input type="text" id="menu_name" name="name" value="{{ old('name') }}"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                placeholder="e.g. Main Navigation">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug / URL</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/menu/</span>
                                </div>

                                <input type="text" id="menu_slug" name="slug" value="{{ old('slug') }}"
                                    class="form-control @error('slug') is-invalid @enderror" placeholder="auto-generated">
                            </div>

                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small id="slug_status" class="text-muted"></small>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Location</label>
                            <select name="location" class="form-control">
                                <option value="">Select</option>
                                <option value="header">Header</option>
                                <option value="footer">Footer</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary btn-lg">
                    <i class="fas fa-save mr-1"></i> Create Menu
                </button>
            </div>
        </div>
    </form>

@endsection

@section('extra_js')
    <script>
        $(document).ready(function() {

            let slugEdited = false;
            let debounceTimer;

            const nameInput = document.getElementById('menu_name');
            const slugInput = document.getElementById('menu_slug');
            const slugStatus = document.getElementById('slug_status');

            // Slugify function (same level as service)
            function slugify(text) {
                return text.toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            // If user edits slug manually → stop auto overwrite
            slugInput.addEventListener('input', function() {
                slugEdited = true;
                checkSlug(this.value);
            });

            // Auto slug from name
            nameInput.addEventListener('input', function() {
                if (slugEdited) return;

                let slug = slugify(this.value);
                slugInput.value = slug;

                checkSlug(slug);
            });

            // AJAX slug check (optional but pro)
            function checkSlug(slug) {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {

                    if (!slug) {
                        slugStatus.innerHTML = '';
                        return;
                    }

                    fetch(`{{ route('admin.menus.checkSlug') }}?slug=${slug}`)
                        .then(res => res.json())
                        .then(data => {

                            if (data.exists) {
                                slugStatus.innerHTML =
                                    '<span class="text-danger">Slug already taken</span>';
                            } else {
                                slugStatus.innerHTML =
                                    '<span class="text-success">Slug available</span>';
                            }

                        });

                }, 400);
            }

            // Form validation (same UX as service)
            $('#menuForm').on('submit', function(e) {

                let name = $('#menu_name').val().trim();

                if (!name) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Menu name is required'
                    });

                    return false;
                }

                return true;
            });

        });
    </script>
@endsection
