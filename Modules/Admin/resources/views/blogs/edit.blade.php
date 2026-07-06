@extends('admin::layouts.app')
@section('page_title', 'Edit Blog Post')

@section('page_actions')
    @if ($blog->status == 'published')
        <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="btn btn-info btn-sm mr-2">
            <i class="fas fa-eye mr-1"></i> View
        </a>
    @endif
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

        .slug-changed-badge {
            font-size: .65rem;
            background: #fef3c7;
            color: #92400e;
            padding: 1px 8px;
            border-radius: 10px;
            font-weight: 700;
            white-space: nowrap;
        }
    </style>
@endsection

@section('admin_content')

    <form id="blogForm" action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            {{-- ── LEFT ── --}}
            <div class="col-md-8">

                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>
                            Edit — {{ Str::limit($blog->title, 40) }}
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="titleInput" value="{{ old('title', $blog->title) }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror" required />
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
                                <input type="text" name="slug" id="slugInput" value="{{ old('slug', $blog->slug) }}"
                                    class="form-control @error('slug') is-invalid @enderror" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetSlug()"
                                        title="Reset to original">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="slug-preview-box">
                                <i class="fas fa-link" style="font-size:.7rem;flex-shrink:0;"></i>
                                <span class="slug-preview-url" id="slugPreview"></span>
                                <span class="slug-changed-badge d-none" id="slugChangedBadge">
                                    ⚠ Changed
                                </span>
                            </div>
                            <small class="text-muted">
                                Original: <code>{{ $blog->slug }}</code>
                            </small>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Excerpt</label>
                            <textarea name="excerpt" rows="2" class="form-control">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Content</label>
                            <div id="quill-editor"></div>
                            <input type="hidden" name="content" id="contentHidden" />
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
                            <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control">{{ old('meta_description', $blog->meta_description) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                value="{{ old('meta_keywords', $blog->meta_keywords) }}" class="form-control" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT ── --}}
            <div class="col-md-4">

                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">— No Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <input type="text" name="tags"
                                value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : $blog->tags) }}"
                                class="form-control" placeholder="tag1, tag2, tag3" />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Order</label>
                            <input type="number" name="order" value="{{ old('order', $blog->order) }}"
                                class="form-control" min="0" />
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                    value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">Featured
                                    Post</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="allow_comments" class="custom-control-input"
                                    id="allowComments" value="1"
                                    {{ old('allow_comments', $blog->allow_comments) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="allowComments">Allow
                                    Comments</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-save mr-1"></i> Update Post
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
                        @if ($blog->featured_image)
                            <div class="mb-2 position-relative">
                                <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                    class="img-fluid rounded w-100" style="max-height:130px;object-fit:cover;" />
                                <form action="{{ route('admin.blogs.remove-image', $blog->id) }}" method="POST"
                                    class="mt-1" onsubmit="return confirm('Remove image?')">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-danger">
                                        <i class="fas fa-times mr-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        @endif
                        <div class="input-group mb-2">
                            <div class="custom-file">
                                <input type="file" name="featured_image" class="custom-file-input"
                                    accept="image/jpeg,image/png,image/webp" id="featuredImageInput"
                                    onchange="previewImage(this)" />
                                <label class="custom-file-label" for="featuredImageInput">
                                    {{ $blog->featured_image ? 'Change image...' : 'Choose image...' }}
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Leave empty to keep current.</small>
                        <div class="mt-2">
                            <img id="imagePreview" src="#" class="d-none img-fluid rounded"
                                style="max-height:130px;object-fit:cover;width:100%;" />
                        </div>
                    </div>
                </div>

                {{-- Post Info --}}
                <div class="card card-outline card-secondary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i> Post Info
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted pl-3">Views</td>
                                <td><span class="badge badge-secondary">{{ $blog->views }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Read Time</td>
                                <td><small>{{ $blog->reading_time }}</small></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Author</td>
                                <td><small>{{ $blog->author->name ?? '—' }}</small></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Created</td>
                                <td><small>{{ $blog->created_at->format('d M Y') }}</small></td>
                            </tr>
                            @if ($blog->published_at)
                                <tr>
                                    <td class="text-muted pl-3">Published</td>
                                    <td><small>{{ $blog->published_at->format('d M Y') }}</small></td>
                                </tr>
                            @endif
                        </table>
                    </div>
    </form>
    </div>
    <div class="card-footer">
        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
            onsubmit="return confirm('Move to trash?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm btn-block">
                <i class="fas fa-trash mr-1"></i> Move to Trash
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
        /* ── Quill ── */
        document.addEventListener('DOMContentLoaded', function() {
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

            // Use old('content') on validation fail, otherwise use $blog->content
            var existingContent = {!! json_encode(old('content', $blog->content ?? '')) !!};
            if (existingContent) quill.root.innerHTML = existingContent;

            document.getElementById('blogForm').addEventListener('submit', function() {
                var html = quill.root.innerHTML;
                document.getElementById('contentHidden').value =
                    (html === '<p><br></p>' || html === '') ? '' : html;
            });

            // Live slug
            var originalSlug = {!! json_encode($blog->slug) !!};
            var baseUrl = '{{ url('/blog') }}';
            var isManual = false;
        });

        function buildSlug(text) {
            return text.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function updateSlugPreview(slug) {
            document.getElementById('slugPreview').textContent = baseUrl + '/' + (slug || '…');
            var badge = document.getElementById('slugChangedBadge');
            if (slug && slug !== originalSlug) {
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }

        document.getElementById('slugInput').addEventListener('input', function() {
            isManual = this.value.trim().length > 0;
            updateSlugPreview(this.value);
        });

        document.getElementById('slugInput').addEventListener('blur', function() {
            if (this.value) {
                this.value = buildSlug(this.value);
                updateSlugPreview(this.value);
            }
        });

        document.getElementById('titleInput').addEventListener('input', function() {
            if (!isManual) {
                var generated = buildSlug(this.value);
                document.getElementById('slugInput').value = generated;
                updateSlugPreview(generated);
            }
        });

        function resetSlug() {
            document.getElementById('slugInput').value = originalSlug;
            isManual = false;
            updateSlugPreview(originalSlug);
            document.getElementById('slugInput').style.background = '#d1fae5';
            setTimeout(function() {
                document.getElementById('slugInput').style.background = '';
            }, 800);
        }

        // Init
        updateSlugPreview(document.getElementById('slugInput').value || originalSlug);

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
