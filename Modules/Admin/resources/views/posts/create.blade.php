@extends('admin::layouts.app')
@section('page_title', 'New Post')

@section('page_actions')
    <a href="{{ route('admin.posts.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        #quill-editor {
            min-height: 380px;
            background: #fff;
        }

        .ql-editor {
            min-height: 380px;
            font-size: .95rem;
            line-height: 1.8;
        }

        .ql-toolbar.ql-snow {
            background: #f8f9fa;
            border-radius: 4px 4px 0 0;
        }

        .ql-container.ql-snow {
            border-radius: 0 0 4px 4px;
        }

        .slug-preview-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 6px 6px;
            padding: 6px 12px;
            font-size: .8rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .slug-preview-url {
            color: #4f46e5;
            font-weight: 600;
            word-break: break-all;
        }
    </style>
@endsection

@section('admin_content')

    <form id="postForm" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- ── LEFT ── --}}
            <div class="col-md-8">

                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pen mr-2"></i> Post Content
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="titleInput" value="{{ old('title') }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                placeholder="Post title..." required />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="code" id="codeInput" value="{{ old('code') }}"
                                class="form-control form-control-lg @error('code') is-invalid @enderror"
                                placeholder="Post code..." required />
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                placeholder="Optional subtitle..." />
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/posts/</span>
                                </div>
                                <input type="text" name="slug" id="slugInput" value="{{ old('slug') }}"
                                    class="form-control @error('slug') is-invalid @enderror" placeholder="auto-generated" />
                            </div>
                            <div class="slug-preview-box">
                                <i class="fas fa-link" style="font-size:.7rem;flex-shrink:0;"></i>
                                <span class="slug-preview-url" id="slugPreview">
                                    {{ url('/posts/') }}/…
                                </span>
                            </div>
                            <small class="text-muted">Leave empty to auto-generate from title.</small>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Short summary shown in listings...">{{ old('description') }}</textarea>
                            <small class="text-muted">Shown in post listing cards.</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">
                                Content
                            </label>
                            <div id="quill-editor"></div>
                            <input type="hidden" name="content" id="contentHidden" />
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Position
                            </label>

                            <input type="text" name="position" id="positionInput" value="{{ old('position') }}"
                                class="form-control form-control-lg @error('position') is-invalid @enderror"
                                placeholder="Post position..." />

                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                                class="form-control" placeholder="Leave empty to use post title" />
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT ── --}}
            <div class="col-md-4">

                {{-- Publish --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Publish
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Published At</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                                class="form-control" />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                class="form-control" min="0" />
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">Featured
                                    Post</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Save Post
                        </button>
                    </div>
                </div>

                {{-- Image --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Featured Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-2">
                            <div class="custom-file">
                                <input type="file" name="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp" id="imageInput"
                                    onchange="previewImage(this)" />
                                <label class="custom-file-label" for="imageInput">
                                    Choose image...
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">JPG, PNG or WEBP. Max 4MB.</small>
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
        /* ── Quill ── */
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Write your post content here…',
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
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    [{
                        indent: '-1'
                    }, {
                        indent: '+1'
                    }],
                    [{
                        align: []
                    }],
                    ['link', 'image', 'blockquote', 'code-block'],
                    ['clean'],
                ],
            },
        });

        @if (old('content'))
            quill.root.innerHTML = {!! json_encode(old('content')) !!};
        @endif

        document.getElementById('postForm').addEventListener('submit', function() {
            var html = quill.root.innerHTML;
            document.getElementById('contentHidden').value =
                (html === '<p><br></p>' || html === '') ? '' : html;
        });

        /* ── Live slug ── */
        var isManual = false;
        var baseUrl = '{{ url('/posts') }}';

        document.getElementById('titleInput').addEventListener('input', function() {
            if (!isManual) {
                var slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                document.getElementById('slugInput').value = slug;
                document.getElementById('slugPreview').textContent = baseUrl + '/' + (slug || '…');
            }
            var meta = document.getElementById('metaTitleInput');
            if (meta && !meta.value) meta.value = this.value;
        });

        document.getElementById('slugInput').addEventListener('input', function() {
            isManual = this.value.trim().length > 0;
            document.getElementById('slugPreview').textContent = baseUrl + '/' + (this.value || '…');
        });

        document.getElementById('slugInput').addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.toLowerCase()
                    .replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-+|-+$/g, '');
                document.getElementById('slugPreview').textContent = baseUrl + '/' + this.value;
            }
        });

        /* ── Image preview ── */
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
