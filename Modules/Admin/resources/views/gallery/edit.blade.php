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
                                <label>Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $gallery->title) }}">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control">{{ old('description', $gallery->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Alt Text</label>
                                <input type="text" name="image_alt" class="form-control"
                                    value="{{ old('image_alt', $gallery->image_alt) }}">
                            </div>

                            {{-- FILE TYPE --}}
                            <div class="form-group">
                                <label>Media Type</label>
                                <select name="file_type" id="file_type" class="form-control">
                                    <option value="image" {{ old('file_type', 'image') == 'image' ? 'selected' : '' }}>
                                        Image
                                    </option>
                                    <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="youtube" {{ old('file_type') == 'youtube' ? 'selected' : '' }}>YouTube
                                    </option>
                                </select>
                            </div>

                            {{-- UPLOAD --}}
                            <div class="form-group" id="fileBox">
                                <label>Upload File</label>
                                <input type="file" name="media" class="form-control-file"
                                    accept="image/*,video/*,webp/*,jpg/*,png/*">
                            </div>

                            {{-- YOUTUBE --}}
                            <div class="form-group" id="youtubeBox" style="display:none;">
                                <label>YouTube URL</label>
                                <input type="url" name="youtube_url" class="form-control"
                                    value="{{ old('youtube_url', $gallery->youtube_url) }}">
                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Album</label>
                                <select name="album_id" class="form-control">
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}"
                                            {{ $gallery->album_id == $album->id ? 'selected' : '' }}>
                                            {{ $album->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Image Type</label>
                                <select name="image_type" id="image_type" class="form-control">
                                    @foreach (\Modules\Common\Entities\Gallery::getImageTypes() as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ $gallery->image_type == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- POSITION --}}
                            <div class="form-group" id="positionGroup" style="display:none;">
                                <label>Position</label>
                                <select name="image_position" class="form-control">
                                    @foreach (\Modules\Common\Entities\Gallery::getFloatingPositions() as $key => $pos)
                                        <option value="{{ $key }}"
                                            {{ $gallery->image_position == $key ? 'selected' : '' }}>
                                            {{ $pos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- STATUS --}}
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ $gallery->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $gallery->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
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
                    <button class="btn btn-secondary">Reset</button>
                    <button class="btn btn-primary">Update</button>
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

        function toggleType() {
            positionGroup.style.display =
                imageType.value === 'floating_image' ? 'block' : 'none';
        }

        imageType.addEventListener('change', toggleType);
        toggleType();
    </script>
@endsection
