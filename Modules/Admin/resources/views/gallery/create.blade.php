@extends('admin::layouts.app')

@section('page_title', 'Upload Image')

@section('admin_content')
    <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-upload mr-2"></i> Upload New Image
                </h3>

                <div class="card-tools">
                    <a href="{{ route('admin.gallery.index', ['album_id' => $selectedAlbumId]) }}"
                        class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Gallery
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">

                        {{-- LEFT SIDE --}}
                        <div class="col-md-8">

                            {{-- TITLE --}}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="Enter image title">
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subtitle">Subtitle</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                    id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                                    placeholder="Enter image subtitle">
                                @error('subtitle')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" placeholder="Describe this image...">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- SEO --}}
                            <div class="form-group">
                                <label for="image_alt">Alt Text (SEO)</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                    id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                            </div>

                            {{-- FILE TYPE --}}
                            <div class="form-group">
                                <label for="file_type">Media Type</label>
                                <select class="form-control" id="file_type" name="file_type">
                                    <option value="image" selected>Image</option>
                                    <option value="video">Video</option>
                                    <option value="youtube">YouTube</option>
                                </select>
                            </div>

                            {{-- IMAGE / VIDEO UPLOAD --}}
                            <div class="form-group" id="fileUploadBox">
                                <label>Upload File</label>
                                <input type="file" class="form-control-file" name="media" accept="image/*,video/*">
                            </div>

                            {{-- YOUTUBE INPUT --}}
                            {{-- YOUTUBE INPUT --}}
                            <div class="form-group" id="youtubeBox" style="display:none;">
                                <label>YouTube Link</label>
                                <input type="url" name="youtube_url" value="{{ old('youtube_url') }}"
                                    class="form-control @error('youtube_url') is-invalid @enderror"
                                    placeholder="https://www.youtube.com/watch?v=..." />
                                <small class="text-muted">If set, this plays automatically in place of the uploaded
                                    image/video for hero banners.</small>
                                @error('youtube_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- RIGHT SIDE (UNCHANGED CMS SYSTEM) --}}
                        <div class="col-md-4">

                            {{-- ALBUM --}}
                            <div class="form-group">
                                <label for="album_id">Album <span class="text-danger">*</span></label>
                                <select class="form-control @error('album_id') is-invalid @enderror" id="album_id"
                                    name="album_id" required>
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}"
                                            {{ old('album_id', $selectedAlbumId) == $album->id ? 'selected' : '' }}>
                                            {{ $album->title }}
                                            @if ($album->code)
                                                —
                                                {{ \Modules\Common\Entities\Album::ALBUM_CODES[$album->code] ?? $album->code }}
                                            @endif
                                            ({{ $album->gallery_count }} images)
                                        </option>
                                    @endforeach
                                </select>
                                @if ($selectedAlbumId)
                                    @php
                                        $selectedAlbum = $albums->firstWhere('id', $selectedAlbumId);
                                    @endphp
                                    @if ($selectedAlbum && $selectedAlbum->code)
                                        <small class="form-text text-muted">
                                            <i class="fas fa-code mr-1"></i>
                                            This album feeds the
                                            <strong>{{ \Modules\Common\Entities\Album::ALBUM_CODES[$selectedAlbum->code] ?? $selectedAlbum->code }}</strong>
                                            section on the live site.
                                        </small>
                                    @endif
                                @endif
                                @error('album_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- IMAGE TYPE (YOUR EXISTING SYSTEM) --}}
                            <div class="form-group">
                                <label for="image_type">Image Type</label>
                                <select class="form-control" id="image_type" name="image_type">
                                    <option value="">Select Type</option>
                                    @foreach (\Modules\Common\Entities\Gallery::getImageTypes() as $key => $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- POSITION --}}
                            <div class="form-group" id="positionGroup" style="display:none;">
                                <label>Floating Position</label>
                                <select class="form-control" name="image_position">
                                    <option value="">Select</option>
                                    @foreach (\Modules\Common\Entities\Gallery::getFloatingPositions() as $key => $pos)
                                        <option value="{{ $key }}">{{ $pos }}</option>
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
                                        placeholder="Link URL">

                                    <select name="type_settings[target]" class="form-control">
                                        <option value="_self">Same Tab</option>
                                        <option value="_blank">New Tab</option>
                                    </select>
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            {{-- FEATURED --}}
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_featured" value="1">
                                    Featured
                                </label>
                            </div>

                            {{-- SORT --}}
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control">
                            </div>

                        </div>

                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        const fileType = document.getElementById('file_type');
        const uploadBox = document.getElementById('fileUploadBox');
        const youtubeBox = document.getElementById('youtubeBox');

        fileType.addEventListener('change', function() {

            if (this.value === 'youtube') {
                uploadBox.style.display = 'none';
                youtubeBox.style.display = 'block';

                document.querySelector('input[name="media"]').value = '';
            } else {
                uploadBox.style.display = 'block';
                youtubeBox.style.display = 'none';

                document.querySelector('input[name="youtube_url"]').value = '';
            }
        });



        const imageTypeSelect = document.getElementById('image_type');
        const positionGroup = document.getElementById('positionGroup');
        const bannerSettings = document.getElementById('bannerSettings');

        function toggleTypeFields() {

            const selected = imageTypeSelect.value;

            positionGroup.style.display = 'none';
            bannerSettings.style.display = 'none';

            if (selected === 'floating_image') {
                positionGroup.style.display = 'block';
            }

            if (selected === 'banner') {
                bannerSettings.style.display = 'block';
            }
        }

        imageTypeSelect.addEventListener('change', toggleTypeFields);
        toggleTypeFields();
    </script>
@endsection
