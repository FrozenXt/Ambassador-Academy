@extends('admin::layouts.app')
@section('page_title', 'Create Notice / News')

@section('page_actions')
    <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
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

        .priority-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
        }
    </style>
@endsection

@section('admin_content')

    <form id="noticeForm" action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- ── LEFT — Main Content ── --}}
            <div class="col-md-8">

                {{-- Title & Slug --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-heading mr-2"></i> Notice / News Details
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="titleInput" value="{{ old('title') }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                placeholder="Enter notice or news title..." required />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/notices/</span>
                                </div>
                                <input type="text" name="slug" id="slugInput" value="{{ old('slug') }}"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    placeholder="auto-generated-from-title" />
                            </div>
                            <small class="text-muted">
                                Leave empty to auto-generate. Use only lowercase letters, numbers and hyphens.
                            </small>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Brief summary shown in listings (max 500 chars)...">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Appears in listings and previews.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">
                                Content <span class="text-danger">*</span>
                            </label>
                            <div id="quill-editor">{{ old('content', $notice->content ?? '') }}</div>
                            <input type="hidden" name="content" id="contentHidden" />
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- SEO --}}
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-search mr-2"></i> SEO Settings
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Title</label>
                            <input type="text" name="meta_title" id="metaTitleInput" value="{{ old('meta_title') }}"
                                class="form-control" placeholder="SEO title (leave empty to use main title)" />
                            <small class="text-muted">Recommended: 50–60 characters</small>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                            <small class="text-muted">Recommended: 150–160 characters</small>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                class="form-control" placeholder="keyword1, keyword2, keyword3" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT — Settings ── --}}
            <div class="col-md-4">

                {{-- Publish --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Publish Settings
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">Type</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="notice" {{ old('type', 'notice') == 'notice' ? 'selected' : '' }}>
                                    Notice
                                </option>
                                <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>
                                    News
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Priority</label>
                            <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                                <option value="low" {{ old('priority', 'low') == 'low' ? 'selected' : '' }}>Low
                                </option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Published Date</label>
                            <input type="date" name="published_date"
                                value="{{ old('published_date', date('Y-m-d')) }}"
                                class="form-control @error('published_date') is-invalid @enderror" />
                            @error('published_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="form-control @error('expiry_date') is-invalid @enderror" />
                            <small class="text-muted">Leave empty if never expires.</small>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" class="form-control"
                                placeholder="tag1, tag2, tag3" />
                            <small class="text-muted">Separate with commas.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="statusSwitch"
                                    value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="statusSwitch">
                                    Active
                                </label>
                            </div>
                            <small class="text-muted">
                                Inactive items won't appear on the website.
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input"
                                    id="featuredSwitch" value="1"
                                    {{ old('is_featured') == '1' ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="featuredSwitch">
                                    Featured
                                </label>
                            </div>
                            <small class="text-muted">
                                Featured items are highlighted on the homepage.
                            </small>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Save Notice / News
                        </button>
                        <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-block mt-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Featured Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-2">
                            <div class="custom-file">
                                <input type="file" name="featured_image"
                                    class="custom-file-input @error('featured_image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml"
                                    id="featuredImageInput" onchange="previewImage(this)" />
                                <label class="custom-file-label" for="featuredImageInput">
                                    Choose image...
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">
                            Recommended: 800×500px. Max 2MB. JPG, PNG or WEBP.
                        </small>
                        @error('featured_image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="imagePreview" src="#" class="d-none img-fluid rounded"
                                style="max-height:160px;object-fit:cover;width:100%;" />
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
        /* ── Auto-generate slug from title ── */
        document.getElementById('titleInput').addEventListener('input', function() {
            var slugInput = document.getElementById('slugInput');
            if (slugInput.dataset.manual) return; // Don't override if manually edited

            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        });

        /* ── Mark slug as manually edited ── */
        document.getElementById('slugInput').addEventListener('input', function() {
            this.dataset.manual = this.value ? 'true' : '';
        });

        /* ── Quill Editor ── */
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

        // word / char / read-time stats
        function updateStats() {
            const text = quill.getText().trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            const chars = text.length;
            document.getElementById('wordCount').textContent = words + ' word' + (words !== 1 ? 's' : '');
            document.getElementById('charCount').textContent = chars + ' character' + (chars !== 1 ? 's' : '');
            document.getElementById('readTime').textContent = '~' + Math.max(1, Math.ceil(words / 200)) +
                ' min read';
        }

        quill.on('text-change', function() {
            syncContent();
            updateStats();
        });

        updateStats();

        document.getElementById('pageForm').addEventListener('submit', syncContent);



        /* ── Auto-fill meta title from title ── */
        document.getElementById('titleInput').addEventListener('blur', function() {
            var metaTitle = document.getElementById('metaTitleInput');
            if (metaTitle && !metaTitle.value) {
                metaTitle.value = this.value;
            }
        });

        /* ── Validate slug format on blur ── */
        document.getElementById('slugInput').addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9-]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        /* ── Validate expiry after published ── */
        document.getElementById('expiry_date').addEventListener('change', function() {
            var published = document.querySelector('[name=published_date]').value;
            if (published && this.value && this.value < published) {
                alert('Expiry date cannot be earlier than published date.');
                this.value = '';
            }
        });

        /* ── Featured image preview ── */
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
