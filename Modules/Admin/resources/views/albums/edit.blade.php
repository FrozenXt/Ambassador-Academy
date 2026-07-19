@extends('admin::layouts.app')

@section('page_title', 'Edit Album: ' . $album->title)

@section('admin_content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i> Edit Album: {{ $album->title }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.albums.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Albums
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.albums.update', $album) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $album->title) }}"
                                    placeholder="Enter album title">
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code">Page Section (Code)</label>
                                <select class="form-control @error('code') is-invalid @enderror" id="code"
                                    name="code" {{ $album->code ? 'disabled' : '' }}>
                                    @foreach (\Modules\Common\Entities\Album::ALBUM_CODES as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('code', $album->code) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($album->code)
                                    <input type="hidden" name="code" value="{{ $album->code }}">
                                    <small class="form-text text-warning">
                                        <i class="fas fa-lock mr-1"></i> This album is linked to a live page section and its
                                        code is locked. Contact your developer if this needs to change.
                                    </small>
                                @else
                                    <small class="form-text text-muted">
                                        Only choose a section if this album should feed a specific part of the website.
                                    </small>
                                @endif
                                @error('code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug', $album->slug) }}"
                                    placeholder="auto-generated from title">
                                <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" placeholder="Describe this album...">{{ old('description', $album->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status"
                                    name="status">
                                    <option value="active"
                                        {{ old('status', $album->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $album->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured', $album->is_featured) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_featured">
                                        <i class="fas fa-star text-warning"></i> Featured Album
                                    </label>
                                </div>
                                <small class="form-text text-muted">Featured albums will be shown on homepage</small>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_gallery_category"
                                        name="is_gallery_category" value="1"
                                        {{ old('is_gallery_category', $album->is_gallery_category ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_gallery_category">
                                        <i class="fas fa-filter text-info"></i> Show as a filter tab on the Gallery page
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    turn this on if you want to show the types in gallery page
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="sort_order">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order" value="{{ old('sort_order', $album->sort_order) }}">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cover_image">Cover Image</label>
                                <div id="coverPreview" class="mb-3 text-center">
                                    <img src="{{ $album->cover_image_url }}" alt="Cover Image"
                                        style="width: 100%; max-width: 200px; height: auto; border-radius: 10px; border: 2px solid #ddd;">
                                </div>
                                <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror"
                                    id="cover_image" name="cover_image" accept="image/*">
                                @error('cover_image')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Leave empty to keep current cover. Recommended: 800x600
                                    pixels, max 5MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Album
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function generateSlug(text) {
            if (!text) return '';

            return text
                .toString()
                .toLowerCase()
                .trim()
                // Replace spaces with hyphens
                .replace(/\s+/g, '-')
                // Replace special characters with hyphens
                .replace(/[^\w\-]+/g, '-')
                // Replace multiple hyphens with single hyphen
                .replace(/\-{2,}/g, '-')
                // Remove leading and trailing hyphens
                .replace(/^-+|-+$/g, '');
        }

        let autoSlugEnabled = true;
        let lastTitleValue = '';

        document.getElementById('title').addEventListener('keyup', function() {
            const slugInput = document.getElementById('slug');

            // Check if auto-generation is enabled and slug is empty or user hasn't manually edited it
            if (autoSlugEnabled && (slugInput.value === '' || slugInput.value === generateSlug(lastTitleValue))) {
                const slug = generateSlug(this.value);
                slugInput.value = slug;
                lastTitleValue = this.value;
            }
        });

        // When user manually edits slug, disable auto-generation
        document.getElementById('slug').addEventListener('focus', function() {
            autoSlugEnabled = false;
        });

        document.getElementById('cover_image').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('#coverPreview img').src = e.target.result;
            };
            if (e.target.files[0]) {
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
@endsection
