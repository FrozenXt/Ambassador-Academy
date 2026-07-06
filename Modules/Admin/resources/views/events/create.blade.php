@extends('admin::layouts.app')
@section('page_title', 'Add Event')

@section('page_actions')
    <a href="{{ route('admin.events.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        #quill-editor {
            height: 200px;
            background: #fff;
        }

        .ql-editor {
            min-height: 200px;
            font-size: .95rem;
        }

        .ql-toolbar.ql-snow {
            background: #f8f9fa;
            border-radius: 4px 4px 0 0;
        }

        .ql-container.ql-snow {
            border-radius: 0 0 4px 4px;
        }
    </style>
@endsection

@section('admin_content')

    <form id="pageForm" action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- Main Content --}}
            <div class="col-md-8">

                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i> Event Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="eventTitle" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Event or announcement title" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" id="eventSlug" value="{{ old('slug') }}"
                                class="form-control @error('slug') is-invalid @enderror" placeholder="auto-generated" />
                            <small class="text-muted">Leave empty to auto-generate.</small>
                            <div id="slug_status" class="mt-1"></div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Brief description shown in listings...">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label>Full Content</label>
                            <div id="quill-editor">{{ old('content') }}</div>
                            <input type="hidden" name="content" id="contentHidden" />
                        </div>
                    </div>
                </div>

                {{-- Date & Location --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i> Date & Location
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                        class="form-control @error('start_date') is-invalid @enderror" />
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date & Time</label>
                                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                                        class="form-control @error('end_date') is-invalid @enderror" />
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location / City</label>
                                    <input type="text" name="location" value="{{ old('location') }}"
                                        class="form-control" placeholder="e.g. Kathmandu, Nepal" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Venue / Address</label>
                                    <input type="text" name="venue" value="{{ old('venue') }}" class="form-control"
                                        placeholder="e.g. City Hall, Room 101" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Organizer & Contact --}}
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-tie mr-2"></i> Organizer & Contact
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Organizer</label>
                                    <input type="text" name="organizer" value="{{ old('organizer') }}"
                                        class="form-control" placeholder="e.g. John Doe / ABC Organization" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Registration URL</label>
                                    <input type="url" name="registration_url" value="{{ old('registration_url') }}"
                                        class="form-control @error('registration_url') is-invalid @enderror"
                                        placeholder="https://..." />
                                    @error('registration_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Contact Email</label>
                                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                                        class="form-control @error('contact_email') is-invalid @enderror"
                                        placeholder="contact@example.com" />
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Contact Phone</label>
                                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                                        class="form-control" placeholder="+977-9800000000" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-md-4">

                {{-- Publish --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="event" {{ old('type', 'event') == 'event' ? 'selected' : '' }}>
                                    Event
                                </option>
                                <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>
                                    Announcement
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label>Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                min="0" />
                        </div>
                        <hr>
                        <div class="custom-control custom-switch">

                            <!-- always send false when unchecked -->
                            <input type="hidden" name="is_featured" value="0">

                            <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                value="1" {{ old('is_featured') == '1' ? 'checked' : '' }} />

                            <label class="custom-control-label font-weight-bold" for="isFeatured">
                                Featured on Homepage
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Save Event
                        </button>
                    </div>
                </div>

                {{-- Image --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Event Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="eventImage"
                                    onchange="previewImage(this)" />
                                <label class="custom-file-label" for="eventImage">
                                    Choose image...
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">JPG, PNG or WEBP. Max 3MB.</small>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="imagePreview" src="#" class="d-none img-fluid rounded"
                                style="max-height:150px;object-fit:cover;width:100%;" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Write your page content here…',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, 4, false]
                    }],
                    [{
                        font: []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        color: []
                    }, {
                        background: []
                    }],
                    [{
                        script: 'sub'
                    }, {
                        script: 'super'
                    }],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }, {
                        list: 'check'
                    }],
                    [{
                        indent: '-1'
                    }, {
                        indent: '+1'
                    }],
                    [{
                        align: []
                    }],
                    ['link', 'image', 'video'],
                    ['blockquote', 'code-block'],
                    ['clean'],
                ],
                history: {
                    delay: 1000,
                    maxStack: 100,
                    userOnly: true
                },
            },
        });

        const contentHidden = document.getElementById('contentHidden');

        // restore old() value on validation fail
        if (contentHidden.value.trim()) {
            quill.root.innerHTML = contentHidden.value;
        }

        function syncContent() {
            const html = quill.root.innerHTML;
            contentHidden.value = (html === '<p><br></p>' || html === '') ? '' : html;
        }

        quill.on('text-change', function() {
            syncContent();
        });

        document.getElementById('pageForm').addEventListener('submit', syncContent);

        // ========== AUTO SLUG WITH AJAX CHECK ==========
        const titleInput = document.getElementById('eventTitle');
        const slugInput = document.getElementById('eventSlug');
        const slugStatus = document.getElementById('slug_status');

        let slugEdited = false;
        let debounceTimer;

        function slugify(text) {
            return text.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }

        // Check slug availability via AJAX
        function checkSlug(slug) {
            clearTimeout(debounceTimer);

            if (!slug || slug === '') {
                slugStatus.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('admin.events.checkSlug') }}?slug=${encodeURIComponent(slug)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            slugStatus.innerHTML =
                                '<span class="text-danger">✗ This slug is already taken</span>';
                        } else {
                            slugStatus.innerHTML = '<span class="text-success">✓ Slug is available</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking slug:', error);
                        slugStatus.innerHTML = '';
                    });
            }, 500);
        }

        // Auto-generate slug from title (LIVE as you type)
        titleInput.addEventListener('input', function() {
            if (!slugEdited) {
                const slug = slugify(this.value);
                slugInput.value = slug;
                checkSlug(slug);
            }
        });

        // When user manually edits slug
        slugInput.addEventListener('input', function() {
            slugEdited = true;
            checkSlug(this.value);
        });

        // Reset manual edit flag when form is submitted
        document.getElementById('pageForm').addEventListener('submit', function() {
            slugEdited = false;
        });

        // Initial check if slug has value (for edit mode)
        if (slugInput.value) {
            checkSlug(slugInput.value);
        }

        // Image preview
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            var label = input.nextElementSibling;
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
