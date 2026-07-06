@extends('admin::layouts.app')
@section('page_title', 'Edit Testimonial')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i> Edit Testimonial — {{ $testimonial->name }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST"
                enctype="multipart/form-data" id="testimonialForm">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required-field">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $testimonial->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position / Role</label>
                                    <input type="text" name="position"
                                        value="{{ old('position', $testimonial->position) }}" class="form-control"
                                        placeholder="e.g. CEO, Manager" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" name="company" value="{{ old('company', $testimonial->company) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required-field">Rating</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <select name="rating" id="ratingSelect" class="form-control" required
                                            onchange="updateStars(this.value)">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}"
                                                    {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                        <div id="starPreview" class="star-display">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i
                                                    class="fa{{ $s <= $testimonial->rating ? 's' : 'r' }} fa-star text-warning"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required-field">Testimonial Content</label>
                                    <textarea name="content" rows="5" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $testimonial->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-4">
                        {{-- Avatar Upload --}}
                        <div class="form-group">
                            <label>Avatar / Photo</label>
                            <div class="text-center mb-3 p-3 border rounded" id="avatarContainer">
                                @if ($testimonial->avatar)
                                    <img id="avatarPreview"
                                        src="{{ asset('storage/' . $testimonial->avatar) . '?v=' . $testimonial->updated_at->timestamp }}"
                                        class="rounded-circle elevation-2 mx-auto d-block shadow"
                                        style="width: 100px; height: 100px; object-fit: cover;" alt="Current avatar" />
                                    <div class="mt-2">
                                        <small class="text-muted d-block">Current avatar</small>
                                    </div>
                                @else
                                    <div id="avatarPlaceholder"
                                        class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center mx-auto font-weight-bold shadow"
                                        style="width: 100px; height: 100px; font-size: 2rem;">
                                        {{ $testimonial->name ? strtoupper(substr($testimonial->name, 0, 2)) : 'NA' }}
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted d-block">No avatar</small>
                                    </div>
                                @endif
                            </div>

                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar"
                                        class="custom-file-input @error('avatar') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif" id="avatarInput" />
                                    <label class="custom-file-label" for="avatarInput">Choose new photo...</label>
                                </div>
                            </div>
                            @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="help-text mt-1">
                                <small class="text-muted">
                                    JPG, PNG, WebP, SVG, GIF • Max 2MB • 100x100px recommended<br>
                                    <strong>Leave empty to keep current avatar</strong>
                                </small>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $testimonial->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"
                                    {{ old('status', $testimonial->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        {{-- Display Order --}}
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" name="order" value="{{ old('order', $testimonial->order ?? 0) }}"
                                class="form-control" min="0" max="999" />
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>

                        {{-- Featured --}}
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                    {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }} value="1" />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">
                                    <i class="fas fa-star text-warning mr-1"></i>Featured on Homepage
                                </label>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-block btn-lg mb-2">
                                <i class="fas fa-save mr-1"></i> Update Testimonial
                            </button>

                            <button type="button" class="btn btn-outline-danger btn-block btn-sm" id="deleteBtn"
                                onclick="confirmDelete({{ $testimonial->id }})">
                                <i class="fas fa-trash mr-1"></i> Delete Testimonial
                            </button>
                        </div>

                        {{-- Timestamps --}}
                        <div class="info-text mt-3">
                            <small>
                                <strong>Created:</strong> {{ $testimonial->created_at->format('M d, Y H:i') }}<br>
                                <strong>Updated:</strong> {{ $testimonial->updated_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden Delete Form --}}
    <form id="deleteForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Avatar preview
            $('#avatarInput').on('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Error', 'Image must be less than 2MB', 'error');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = $('#avatarPreview');
                        const placeholder = $('#avatarPlaceholder');
                        const container = $('#avatarContainer');

                        preview.attr('src', e.target.result).removeClass('d-none').show();
                        if (placeholder.length) placeholder.hide();

                        container.find('.text-muted').text('New avatar selected');
                        $('.custom-file-label').text(file.name);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Form validation
            $('#testimonialForm').on('submit', function(e) {
                const name = $('input[name="name"]').val().trim();
                const content = $('textarea[name="content"]').val().trim();
                const rating = $('select[name="rating"]').val();

                if (!name || !content || !rating) {
                    e.preventDefault();
                    Swal.fire('Error', 'Please fill all required fields', 'error');
                    return false;
                }
                return true;
            });

            // Custom file label
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Choose new photo...');
            });
        });

        function updateStars(rating) {
            const preview = document.getElementById('starPreview');
            let html = '';
            for (let i = 1; i <= 5; i++) {
                html += i <= rating ?
                    '<i class="fas fa-star text-warning"></i>' :
                    '<i class="far fa-star text-muted"></i>';
            }
            preview.innerHTML = html;
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete testimonial?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', `/admin/testimonials/${id}`).submit();
                }
            });
        }
    </script>

    <style>
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .help-text {
            font-size: 12px;
            color: #6c757d;
        }

        .info-text {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #4f46e5;
        }

        .star-display i {
            font-size: 1.2rem;
            margin-right: 2px;
        }

        .custom-file-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('extra_js')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('avatarPreview');
                    var placeholder = document.getElementById('avatarPlaceholder');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                    input.nextElementSibling.textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateStars(rating) {
            var preview = document.getElementById('starPreview');
            var html = '';
            for (var i = 1; i <= 5; i++) {
                html += i <= rating ?
                    '<i class="fas fa-star text-warning"></i>' :
                    '<i class="far fa-star text-muted"></i>';
            }
            preview.innerHTML = html;
        }
    </script>
@endsection
