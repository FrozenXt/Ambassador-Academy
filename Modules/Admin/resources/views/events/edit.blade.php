@extends('admin::layouts.app')
@section('page_title', 'Edit Event')

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

        #slug_status {
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
@endsection

@section('admin_content')

    <form id="pageForm" action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            {{-- Main Content --}}
            <div class="col-md-8">
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i> Edit — {{ $event->title }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="eventTitle" value="{{ old('title', $event->title) }}"
                                class="form-control @error('title') is-invalid @enderror" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" id="eventSlug" value="{{ old('slug', $event->slug) }}"
                                class="form-control @error('slug') is-invalid @enderror" />
                            <small class="text-muted">Leave empty to auto-generate. Double-click to reset
                                auto-generation.</small>
                            <div id="slug_status" class="mt-1"></div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $event->short_description) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label>Full Content</label>
                            <div id="quill-editor"></div>
                            <textarea name="content" id="contentHidden" style="display:none;">{{ old('content', $event->content) }}</textarea>
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
                                    <input type="datetime-local" name="start_date"
                                        value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
                                        class="form-control @error('start_date') is-invalid @enderror" />
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date & Time</label>
                                    <input type="datetime-local" name="end_date"
                                        value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location / City</label>
                                    <input type="text" name="location" value="{{ old('location', $event->location) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Venue / Address</label>
                                    <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Organizer --}}
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
                                    <input type="text" name="organizer"
                                        value="{{ old('organizer', $event->organizer) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Registration URL</label>
                                    <input type="url" name="registration_url"
                                        value="{{ old('registration_url', $event->registration_url) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Contact Email</label>
                                    <input type="email" name="contact_email"
                                        value="{{ old('contact_email', $event->contact_email) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Contact Phone</label>
                                    <input type="text" name="contact_phone"
                                        value="{{ old('contact_phone', $event->contact_phone) }}" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-md-4">
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="event" {{ old('type', $event->type) == 'event' ? 'selected' : '' }}>Event
                                </option>
                                <option value="announcement"
                                    {{ old('type', $event->type) == 'announcement' ? 'selected' : '' }}>Announcement
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Order</label>
                            <input type="number" name="order" value="{{ old('order', $event->order) }}"
                                class="form-control" min="0" />
                        </div>
                        <hr>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                {{ old('is_featured', $event->is_featured) ? 'checked' : '' }} />
                            <label class="custom-control-label font-weight-bold" for="isFeatured">
                                Featured on Homepage
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <!-- Update Form -->
                        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save mr-1"></i> Update Event
                            </button>
                        </form>

                        <a href="{{ route('admin.events.index') }}" class="btn btn-default btn-block mt-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>

                    {{-- Event Image - SIMPLE VERSION WITHOUT REMOVE BUTTON --}}
                    <div class="card card-outline card-info mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-image mr-2"></i> Event Image
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($event->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid rounded"
                                        style="width:100%;max-height:120px;object-fit:cover;" />
                                    <div class="mt-1">
                                        <small class="text-muted">Current image (upload new to replace)</small>
                                    </div>
                                </div>
                            @endif

                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input"
                                        accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="eventImage"
                                        onchange="previewImage(this)" />
                                    <label class="custom-file-label" for="eventImage">
                                        {{ $event->image ? 'Change image...' : 'Choose image...' }}
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Upload new image to replace current. Leave empty to keep
                                current.</small>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="imagePreview" src="#" class="d-none img-fluid rounded"
                                    style="max-height:120px;object-fit:cover;width:100%;" />
                            </div>
                        </div>
                    </div>

                    {{-- Event Info --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i> Info
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted pl-3">Created</td>
                                    <td>{{ $event->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-3">Updated</td>
                                    <td>{{ $event->updated_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <form id="deleteNoticeForm" action="{{ route('admin.events.destroy', $event->id) }}"
                                method="POST" class="mt-2"
                                onsubmit="return confirm('Delete this event permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-block">
                                    <i class="fas fa-trash mr-1"></i> Delete Event
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </form>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        // Initialize Quill Editor
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
            },
        });

        const contentHidden = document.getElementById('contentHidden');

        // Set initial content
        if (contentHidden.value && contentHidden.value.trim()) {
            quill.root.innerHTML = contentHidden.value;
        }

        function syncContent() {
            const html = quill.root.innerHTML;
            contentHidden.value = (html === '<p><br></p>' || html === '') ? '' : html;
        }

        quill.on('text-change', function() {
            syncContent();
        });

        // ========== AUTO SLUG ==========
        const titleInput = document.getElementById('eventTitle');
        const slugInput = document.getElementById('eventSlug');
        const slugStatus = document.getElementById('slug_status');

        let slugEdited = false;
        let debounceTimer;
        const eventId = {{ $event->id }};

        function slugify(text) {
            if (!text) return '';
            return text.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }

        function checkSlug(slug) {
            clearTimeout(debounceTimer);
            if (!slug || slug === '') {
                if (slugStatus) slugStatus.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                const url = `/admin/events/check-slug?slug=${encodeURIComponent(slug)}&id=${eventId}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (slugStatus) {
                            if (data.exists) {
                                slugStatus.innerHTML = '<span class="text-danger">✗ Slug already taken</span>';
                            } else {
                                slugStatus.innerHTML = '<span class="text-success">✓ Slug available</span>';
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 500);
        }

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (!slugEdited) {
                    slugInput.value = slugify(this.value);
                    checkSlug(slugInput.value);
                }
            });

            slugInput.addEventListener('input', function() {
                slugEdited = true;
                checkSlug(this.value);
            });

            slugInput.addEventListener('dblclick', function() {
                slugEdited = false;
                this.value = slugify(titleInput.value);
                checkSlug(this.value);
            });
        }

        // Form submission
        const form = document.getElementById('pageForm');
        form.addEventListener('submit', function(e) {
            syncContent();
            return true;
        });

        // Initial slug check
        if (slugInput && slugInput.value) {
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
