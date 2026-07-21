@extends('admin::layouts.app')

@section('page_title', 'Edit Media: ' . ($gallery->title ?? 'Untitled'))

@section('admin_content')
    <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i> Edit Media
                </h3>

                <div class="card-tools">
                    <a href="{{ route('admin.gallery.index', ['album_id' => $gallery->album_id]) }}"
                        class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">

                        {{-- LEFT --}}
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $gallery->title) }}" placeholder="Enter image title">
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subtitle">Subtitle</label>
                                <input type="text" name="subtitle" id="subtitle"
                                    class="form-control @error('subtitle') is-invalid @enderror"
                                    value="{{ old('subtitle', $gallery->subtitle) }}" placeholder="Enter image subtitle">
                                @error('subtitle')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Describe this image...">{{ old('description', $gallery->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image_alt">Alt Text</label>
                                <input type="text" name="image_alt" id="image_alt"
                                    class="form-control @error('image_alt') is-invalid @enderror"
                                    value="{{ old('image_alt', $gallery->image_alt) }}">
                                @error('image_alt')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- FILE TYPE --}}
                            <div class="form-group">
                                <label for="file_type">Media Type</label>
                                <select name="file_type" id="file_type" class="form-control">
                                    <option value="image"
                                        {{ old('file_type', $gallery->file_type) == 'image' ? 'selected' : '' }}>
                                        Image
                                    </option>
                                    <option value="video"
                                        {{ old('file_type', $gallery->file_type) == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="youtube"
                                        {{ old('file_type', $gallery->file_type) == 'youtube' ? 'selected' : '' }}>YouTube
                                    </option>
                                </select>
                            </div>

                            {{-- UPLOAD --}}
                            <div class="form-group" id="fileBox">
                                <label>Upload File</label>
                                <input type="file" name="media"
                                    class="form-control-file @error('media') is-invalid @enderror"
                                    accept="image/*,video/*,webp/*,jpg/*,png/*">
                                <small class="text-muted">Leave empty to keep the current file.</small>
                                @error('media')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- YOUTUBE --}}
                            <div class="form-group" id="youtubeBox" style="display:none;">
                                <label>YouTube URL</label>
                                <input type="url" name="youtube_url"
                                    class="form-control @error('youtube_url') is-invalid @enderror"
                                    value="{{ old('youtube_url', $gallery->youtube_url) }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">If set, this plays automatically in place of the uploaded
                                    image/video for hero banners.</small>
                                @error('youtube_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="album_id">Album <span class="text-danger">*</span></label>
                                <select name="album_id" id="album_id"
                                    class="form-control @error('album_id') is-invalid @enderror" required>
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}"
                                            {{ old('album_id', $gallery->album_id) == $album->id ? 'selected' : '' }}>
                                            {{ $album->title }}
                                            @if ($album->code)
                                                —
                                                {{ \Modules\Common\Entities\Album::ALBUM_CODES[$album->code] ?? $album->code }}
                                            @endif
                                            ({{ $album->gallery_count }} images)
                                        </option>
                                    @endforeach
                                </select>
                                @php
                                    $selectedAlbum = $albums->firstWhere('id', old('album_id', $gallery->album_id));
                                @endphp
                                @if ($selectedAlbum && $selectedAlbum->code)
                                    <small class="form-text text-muted">
                                        <i class="fas fa-code mr-1"></i>
                                        This album feeds the
                                        <strong>{{ \Modules\Common\Entities\Album::ALBUM_CODES[$selectedAlbum->code] ?? $selectedAlbum->code }}</strong>
                                        section on the live site.
                                    </small>
                                @endif
                                @error('album_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image_type">Image Type</label>
                                <select name="image_type" id="image_type" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach (\Modules\Common\Entities\Gallery::getImageTypes() as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ old('image_type', $gallery->image_type) == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- POSITION --}}
                            <div class="form-group" id="positionGroup" style="display:none;">
                                <label>Floating Position</label>
                                <select name="image_position" class="form-control">
                                    <option value="">Select</option>
                                    @foreach (\Modules\Common\Entities\Gallery::getFloatingPositions() as $key => $pos)
                                        <option value="{{ $key }}"
                                            {{ old('image_position', $gallery->image_position) == $key ? 'selected' : '' }}>
                                            {{ $pos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- BANNER SETTINGS --}}
                            <div class="card card-info" id="bannerSettings" style="display:none;">
                                <div class="card-header">
                                    <h5 class="card-title">Banner Settings</h5>
                                </div>
                                <div class="card-body">
                                    <input type="url" name="type_settings[link]" class="form-control mb-2"
                                        placeholder="Link URL"
                                        value="{{ old('type_settings.link', $gallery->type_settings['link'] ?? '') }}">

                                    <select name="type_settings[target]" class="form-control">
                                        <option value="_self"
                                            {{ old('type_settings.target', $gallery->type_settings['target'] ?? '_self') == '_self' ? 'selected' : '' }}>
                                            Same Tab
                                        </option>
                                        <option value="_blank"
                                            {{ old('type_settings.target', $gallery->type_settings['target'] ?? '_self') == '_blank' ? 'selected' : '' }}>
                                            New Tab
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active"
                                        {{ old('status', $gallery->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $gallery->status) == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            {{-- FEATURED --}}
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_featured" value="1"
                                        {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                                    Featured
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $gallery->sort_order) }}">
                            </div>

                            {{-- CURRENT MEDIA --}}
                            <div class="form-group">
                                <label>Current Media</label>

                                @if ($gallery->file_type == 'image')
                                    <img src="{{ asset('storage/' . $gallery->path) }}"
                                        style="width:100%;border-radius:10px;">
                                @elseif($gallery->file_type == 'video')
                                    <video controls style="width:100%;border-radius:10px;">
                                        <source src="{{ asset('storage/' . $gallery->path) }}">
                                    </video>
                                @elseif($gallery->file_type == 'youtube')
                                    <iframe width="100%" height="200"
                                        src="https://www.youtube.com/embed/{{ \Str::afterLast($gallery->youtube_url, '=') }}"
                                        frameborder="0" allowfullscreen></iframe>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function toggleMedia() {
            const type = document.getElementById('file_type').value;

            document.getElementById('fileBox').style.display = 'none';
            document.getElementById('youtubeBox').style.display = 'none';

            if (type === 'youtube') {
                document.getElementById('youtubeBox').style.display = 'block';
            } else {
                document.getElementById('fileBox').style.display = 'block';
            }
        }

        document.getElementById('file_type').addEventListener('change', toggleMedia);
        toggleMedia();

        // image type logic
        const imageType = document.getElementById('image_type');
        const positionGroup = document.getElementById('positionGroup');
        const bannerSettings = document.getElementById('bannerSettings');

        function toggleType() {
            positionGroup.style.display = 'none';
            bannerSettings.style.display = 'none';

            if (imageType.value === 'floating_image') {
                positionGroup.style.display = 'block';
            }

            if (imageType.value === 'banner') {
                bannerSettings.style.display = 'block';
            }
        }

        imageType.addEventListener('change', toggleType);
        toggleType();
    </script>
@endsection
