@extends('admin::layouts.app')
@section('page_title', 'Edit Notice / News')

@section('page_actions')
    <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <style>
        #quill-editor {
            min-height: 300px;
            background: #fff;
        }

        .ql-editor {
            min-height: 300px;
            font-size: .95rem;
            line-height: 1.7;
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
            transition: all 0.3s ease;
        }

        #slugInput {
            transition: background-color 0.3s ease;
            font-family: monospace;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-info {
            color: #17a2b8;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-muted {
            color: #6c757d;
        }
    </style>
@endsection

@section('admin_content')

    {{-- ===== UPDATE FORM ===== --}}
    <form id="updateNoticeForm" action="{{ route('admin.notices.update', $notice->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            {{-- LEFT: Main Content --}}
            <div class="col-md-8">

                {{-- Title & Slug --}}
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit mr-2"></i> Edit — {{ $notice->title }}</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="titleInput" value="{{ old('title', $notice->title) }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">/notices/</span></div>
                                <input type="text" name="slug" id="slugInput" value="{{ old('slug', $notice->slug) }}"
                                    class="form-control @error('slug') is-invalid @enderror">
                            </div>
                            <small class="text-muted">Leave empty to auto-generate. Double-click to reset.</small>
                            <div id="slug_status" class="mt-1"></div>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Brief summary...">{{ old('short_description', $notice->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Content <span class="text-danger">*</span></label>
                            <div id="quill-editor"></div>
                            <textarea name="content" id="contentHidden" style="display:none;">{{ old('content', $notice->content) }}</textarea>
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- SEO --}}
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-search mr-2"></i> SEO Settings</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Title</label>
                            <input type="text" name="meta_title" id="metaTitleInput"
                                value="{{ old('meta_title', $notice->meta_title ?? '') }}" class="form-control"
                                placeholder="SEO title">
                            <small class="text-muted">Recommended: 50–60 characters</small>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="SEO description...">{{ old('meta_description', $notice->meta_description ?? '') }}</textarea>
                            <small class="text-muted">Recommended: 150–160 characters</small>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                value="{{ old('meta_keywords', $notice->meta_keywords ?? '') }}" class="form-control"
                                placeholder="keyword1, keyword2">
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Settings --}}
            <div class="col-md-4">

                {{-- Publish Settings --}}
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cog mr-2"></i> Publish Settings</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">Type</label>
                            <select name="type" class="form-control">
                                <option value="notice" {{ old('type', $notice->type) == 'notice' ? 'selected' : '' }}>
                                    Notice</option>
                                <option value="news" {{ old('type', $notice->type) == 'news' ? 'selected' : '' }}>News
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Priority</label>
                            <select name="priority" class="form-control">
                                <option value="low"
                                    {{ old('priority', $notice->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium"
                                    {{ old('priority', $notice->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"
                                    {{ old('priority', $notice->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent"
                                    {{ old('priority', $notice->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Published Date</label>
                            <input type="date" name="published_date"
                                value="{{ old('published_date', optional($notice->published_date)->format('Y-m-d') ?? date('Y-m-d')) }}"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Expiry Date</label>
                            <input type="date" name="expiry_date"
                                value="{{ old('expiry_date', optional($notice->expiry_date)->format('Y-m-d') ?? '') }}"
                                class="form-control">
                            <small class="text-muted">Leave empty if never expires.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <input type="text" name="tags"
                                value="{{ old('tags', is_array($notice->tags) ? implode(', ', $notice->tags) : $notice->tags) }}"
                                class="form-control" placeholder="tag1, tag2">
                            <small class="text-muted">Separate with commas.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="statusSwitch"
                                    value="1" {{ old('status', $notice->status) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="statusSwitch">Active</label>
                            </div>
                            <small class="text-muted">Inactive items won't appear on website.</small>
                        </div>

                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input"
                                    id="featuredSwitch" value="1"
                                    {{ old('is_featured', $notice->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="featuredSwitch">Featured</label>
                            </div>
                            <small class="text-muted">Featured items highlighted on homepage.</small>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning btn-block"><i class="fas fa-save mr-1"></i> Update
                            Notice / News</button>
                        <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-block mt-2"><i
                                class="fas fa-times mr-1"></i> Cancel</a>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-image mr-2"></i> Featured Image</h3>
                    </div>
                    <div class="card-body">
                        @if (!empty($notice->featured_image))
                            <div class="mb-2 position-relative" id="currentImageContainer">
                                <img src="{{ asset('storage/' . $notice->featured_image) }}" class="img-fluid rounded"
                                    style="width:100%;max-height:130px;object-fit:cover;">
                                <button type="button" class="btn btn-xs btn-danger position-absolute remove-image-btn"
                                    style="top:6px;right:6px;"><i class="fas fa-times"></i> Remove</button>
                                <small class="text-muted d-block mt-1">Current image</small>
                            </div>
                        @endif
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                        <div class="input-group mb-2">
                            <div class="custom-file">
                                <input type="file" name="featured_image" class="custom-file-input"
                                    accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml"
                                    id="featuredImageInput" onchange="previewImage(this)">
                                <label class="custom-file-label"
                                    for="featuredImageInput">{{ $notice->featured_image ? 'Change image...' : 'Choose image...' }}</label>
                            </div>
                        </div>
                        <small class="text-muted">Leave empty to keep current. Click Remove to delete existing
                            image.</small>
                        <div class="mt-3"><img id="imagePreview" src="#" class="d-none img-fluid rounded"
                                style="max-height:130px;object-fit:cover;width:100%;"></div>
                    </div>
                </div>

                {{-- Notice Info --}}
                <div class="card card-outline card-secondary mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Notice Info</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted pl-3">ID</td>
                                <td><code>#{{ $notice->id }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Type</td>
                                <td><span
                                        class="badge badge-{{ $notice->type == 'news' ? 'info' : 'warning' }}">{{ ucfirst($notice->type) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Priority</td>
                                <td><span
                                        class="badge badge-{{ $notice->priority == 'urgent' ? 'danger' : ($notice->priority == 'high' ? 'warning' : ($notice->priority == 'medium' ? 'info' : 'secondary')) }}">{{ ucfirst($notice->priority) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Created</td>
                                <td>{{ $notice->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Updated</td>
                                <td>{{ $notice->updated_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- ===== DELETE FORM ===== --}}
    <form id="deleteNoticeForm" action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST"
        class="mt-2" onsubmit="return confirm('Delete this notice permanently?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-block"><i class="fas fa-trash mr-1"></i> Delete
            Notice</button>
    </form>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script>
        /* ===== Quill Editor ===== */
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Write your notice or news content here...',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'align': []
                    }],
                    ['link', 'blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });
        const contentHidden = document.getElementById('contentHidden');
        if (contentHidden.value) quill.root.innerHTML = contentHidden.value;
        quill.on('text-change', () => {
            contentHidden.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        });

        /* ===== Slug Logic ===== */
        const titleInput = document.getElementById('titleInput');
        const slugInput = document.getElementById('slugInput');
        const slugStatus = document.getElementById('slug_status');
        let slugEdited = false,
            debounceTimer;
        const noticeId = {{ $notice->id }};

        function slugify(text) {
            return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        }

        function checkSlug(slug) {
            clearTimeout(debounceTimer);
            if (!slug) {
                slugStatus.innerHTML = '';
                return;
            }
            slugStatus.innerHTML = '<span class="text-info">Checking availability...</span>';
            debounceTimer = setTimeout(() => {
                fetch(`/admin/notices/check-slug?slug=${encodeURIComponent(slug)}&id=${noticeId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json()).then(data => {
                        slugStatus.innerHTML = data.exists ?
                            '<span class="text-danger">✗ This slug is taken</span>' :
                            '<span class="text-success">✓ Slug is available</span>';
                    }).catch(() => {
                        slugStatus.innerHTML = '<span class="text-warning">Could not verify slug</span>';
                    });
            }, 500);
        }
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', () => {
                if (!slugEdited) {
                    slugInput.value = slugify(titleInput.value);
                    checkSlug(slugInput.value);
                }
            });
            slugInput.addEventListener('input', () => {
                slugEdited = true;
                checkSlug(slugInput.value);
            });
            slugInput.addEventListener('dblclick', () => {
                slugEdited = false;
                slugInput.value = slugify(titleInput.value);
                checkSlug(slugInput.value);
            });
            checkSlug(slugInput.value);
        }

        /* ===== Image Preview & Remove ===== */
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const label = input.nextElementSibling;
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        const removeBtn = document.querySelector('.remove-image-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                document.getElementById('remove_image').value = '1';
                const container = this.closest('#currentImageContainer');
                if (container) container.style.display = 'none';
            });
        }
    </script>
@endsection
