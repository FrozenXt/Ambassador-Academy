@extends('admin::layouts.app')
@section('page_title', 'New Blog Post')

@section('page_actions')
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-default btn-sm">
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

        /* Slug live preview */
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

    <form id="blogForm" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label class="font-weight-bold">Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/blog/</span>
                                </div>
                                <input type="text" name="slug" id="slugInput" value="{{ old('slug') }}"
                                    class="form-control @error('slug') is-invalid @enderror" placeholder="auto-generated" />
                            </div>
                            <div class="slug-preview-box">
                                <i class="fas fa-link" style="font-size:.7rem;flex-shrink:0;"></i>
                                <span class="slug-preview-url" id="slugPreview">
                                    {{ url('/blog/') }}/…
                                </span>
                            </div>
                            <small class="text-muted">Leave empty to auto-generate from title.</small>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Excerpt</label>
                            <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror"
                                placeholder="Short summary shown in listings (max 700 chars)...">{{ old('excerpt') }}</textarea>
                            <small class="text-muted">Shown in blog listing cards.</small>
                            @error('excerpt')
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
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                class="form-control" placeholder="keyword1, keyword2" />
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
                            <label class="font-weight-bold">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">— No Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" class="form-control"
                                placeholder="tag1, tag2, tag3" />
                            <small class="text-muted">Separate with commas.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                min="0" />
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">Featured
                                    Post</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="allow_comments" class="custom-control-input"
                                    id="allowComments" value="1"
                                    {{ old('allow_comments', true) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="allowComments">Allow
                                    Comments</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Publish Post
                        </button>
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
                                    accept="image/jpeg,image/png,image/webp" id="featuredImageInput"
                                    onchange="previewImage(this)" />
                                <label class="custom-file-label" for="featuredImageInput">
                                    Choose image...
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">JPG, PNG or WEBP. Max 3MB.</small>
                        @error('featured_image')
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
            placeholder: 'Write your blog content here…',
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

        document.getElementById('blogForm').addEventListener('submit', function() {
            var html = quill.root.innerHTML;
            document.getElementById('contentHidden').value =
                (html === '<p><br></p>' || html === '') ? '' : html;
        });

        /* ── Live slug ── */
        var isManual = false;
        var baseUrl = '{{ url('/blog') }}';

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
