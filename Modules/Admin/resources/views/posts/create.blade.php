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
            min-height: 320px;
            background: #fff;
        }

        .ql-editor {
            min-height: 320px;
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
            font-size: .78rem;
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

        .form-hint {
            font-size: .78rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .image-upload-box {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .image-upload-box img {
            max-height: 140px;
            border-radius: 6px;
            object-fit: cover;
        }
    </style>
@endsection

@section('admin_content')

    <form id="postForm" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- ── LEFT: Main content ── --}}
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
                                placeholder="e.g. Rooftop. Cocktails. Grill. Entertainment." required />
                            @error('title')
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
                            <p class="form-hint">Leave empty to auto-generate from title.</p>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Short summary shown in listings...">{{ old('description') }}</textarea>
                            <p class="form-hint">Shown in post listing cards.</p>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Content</label>
                            <div id="quill-editor"></div>
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
                                class="form-control" placeholder="Leave empty to use post title" />
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Publish, section link, images ── --}}
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

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                class="form-control" min="0" />
                        </div>

                    </div>
                </div>

                {{-- Page Section Link --}}
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-link mr-2"></i> Page Section
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Code</label>
                            <select class="form-control @error('code') is-invalid @enderror" name="code">
                                @foreach (\Modules\Common\Entities\Post::POST_CODES as $value => $label)
                                    <option value="{{ $value }}" {{ old('code') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="form-hint">
                                Only choose a section if this post should feed a specific part of the website. Leave
                                as "— Not linked —" for a regular blog post.
                            </p>
                            @error('code')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Position / Role</label>
                            <input type="text" name="position" value="{{ old('position') }}"
                                class="form-control @error('position') is-invalid @enderror"
                                placeholder="e.g. Chairman & Founder" />
                            <p class="form-hint">Only used for sections that show a name/title signature (e.g. Chairman).
                            </p>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="custom-control custom-switch mt-3">
                            <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                value="1" {{ old('is_featured') ? 'checked' : '' }} />
                            <label class="custom-control-label font-weight-bold" for="isFeatured">Featured Post</label>
                        </div>
                    </div>
                </div>

                {{-- Image 1 --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="image-upload-box mb-2">
                            <img id="imagePreview" src="#" class="d-none img-fluid" alt="Preview">
                            <div id="imagePlaceholder" class="text-muted py-3">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2 d-block"></i>
                                No image selected
                            </div>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="image"
                                class="custom-file-input @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="imageInput"
                                onchange="previewImage(this, 'imagePreview', 'imagePlaceholder')" />
                            <label class="custom-file-label" for="imageInput">Choose image...</label>
                        </div>
                        <p class="form-hint mb-0">JPG, PNG or WEBP. Max 4MB.</p>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Image 2 --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-images mr-2"></i> Second Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="image-upload-box mb-2">
                            <img id="image2Preview" src="#" class="d-none img-fluid" alt="Preview">
                            <div id="image2Placeholder" class="text-muted py-3">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2 d-block"></i>
                                No image selected
                            </div>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="image_2"
                                class="custom-file-input @error('image_2') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="image2Input"
                                onchange="previewImage(this, 'image2Preview', 'image2Placeholder')" />
                            <label class="custom-file-label" for="image2Input">Choose image...</label>
                        </div>
                        <p class="form-hint mb-0">Only needed for sections that show two images side by side.</p>
                        @error('image_2')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-save mr-1"></i> Save Post
                </button>

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

        /* ── Image preview (shared for both image fields) ── */
        function previewImage(input, previewId, placeholderId) {
            var preview = document.getElementById(previewId);
            var placeholder = document.getElementById(placeholderId);
            var label = input.nextElementSibling;

            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
