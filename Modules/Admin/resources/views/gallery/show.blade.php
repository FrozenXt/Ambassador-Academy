@extends('admin::layouts.app')

@section('page_title', 'View Image: ' . ($gallery->title ?? 'Untitled'))

@section('admin_content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> View Image: {{ $gallery->title ?? 'Untitled' }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.gallery.index', ['album_id' => $gallery->album_id]) }}"
                                class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Gallery
                            </a>
                            <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Edit Image
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Main Image -->
                            <div class="col-md-8">
                                <div class="text-center">
                                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title ?? 'Gallery Image' }}"
                                        class="img-fluid rounded" style="max-height: 600px; width: auto; cursor: pointer;"
                                        onclick="openFullscreen(this.src)">
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="text-center mt-4">
                                    @if ($previous)
                                        <a href="{{ route('admin.gallery.show', $previous->id) }}"
                                            class="btn btn-secondary">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    @endif

                                    @if ($next)
                                        <a href="{{ route('admin.gallery.show', $next->id) }}" class="btn btn-secondary">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Image Details -->
                            <div class="col-md-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <h4>Image Details</h4>
                                        <hr>

                                        @if ($gallery->title)
                                            <div class="mb-3">
                                                <strong><i class="fas fa-heading"></i> Title:</strong>
                                                <p class="mb-0">{{ $gallery->title }}</p>
                                            </div>
                                        @endif

                                        @if ($gallery->description)
                                            <div class="mb-3">
                                                <strong><i class="fas fa-align-left"></i> Description:</strong>
                                                <p class="mb-0">{{ $gallery->description }}</p>
                                            </div>
                                        @endif

                                        @if ($gallery->image_alt)
                                            <div class="mb-3">
                                                <strong><i class="fas fa-tag"></i> Alt Text:</strong>
                                                <p class="mb-0">{{ $gallery->image_alt }}</p>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <strong><i class="fas fa-folder"></i> Album:</strong>
                                            <p class="mb-0">
                                                <a
                                                    href="{{ route('admin.gallery.index', ['album_id' => $gallery->album_id]) }}">
                                                    {{ $gallery->album->title }}
                                                </a>
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-tag"></i> Image Type:</strong>
                                            <p class="mb-0">
                                                <span class="badge badge-primary">
                                                    {{ ucfirst(str_replace('_', ' ', $gallery->image_type ?? 'general')) }}
                                                </span>
                                            </p>
                                        </div>

                                        @if ($gallery->image_position)
                                            <div class="mb-3">
                                                <strong><i class="fas fa-location-dot"></i> Position:</strong>
                                                <p class="mb-0">
                                                    <span class="badge badge-secondary">
                                                        {{ ucfirst(str_replace('-', ' ', $gallery->image_position)) }}
                                                    </span>
                                                </p>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <strong><i class="fas fa-sort-numeric-down"></i> Sort Order:</strong>
                                            <p class="mb-0">{{ $gallery->sort_order }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-flag-checkered"></i> Status:</strong>
                                            <p class="mb-0">
                                                <span
                                                    class="badge badge-{{ $gallery->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($gallery->status) }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-star"></i> Featured:</strong>
                                            <p class="mb-0">
                                                @if ($gallery->is_featured)
                                                    <span class="badge badge-warning"><i class="fas fa-star"></i> Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar"></i> Created:</strong>
                                            <p class="mb-0">{{ $gallery->created_at->format('F d, Y h:i A') }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar-alt"></i> Last Updated:</strong>
                                            <p class="mb-0">{{ $gallery->updated_at->format('F d, Y h:i A') }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <strong><i class="fas fa-database"></i> File Info:</strong>
                                            <p class="mb-0 small text-muted">
                                                Path: {{ $gallery->image_path }}
                                            </p>
                                        </div>

                                        <hr>

                                        <div class="text-center">
                                            <a href="{{ route('admin.gallery.edit', $gallery->id) }}"
                                                class="btn btn-info btn-block">
                                                <i class="fas fa-edit"></i> Edit Image
                                            </a>
                                            <form action="{{ route('admin.gallery.destroy', $gallery->id) }}"
                                                method="POST" class="mt-2"
                                                onsubmit="return confirm('Delete this image permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-block">
                                                    <i class="fas fa-trash"></i> Delete Image
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Album Gallery Grid -->
        @if ($albumImages && $albumImages->count() > 1)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-images"></i> More from {{ $gallery->album->title }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($albumImages as $image)
                                    @if ($image->id != $gallery->id)
                                        <div class="col-md-2 col-sm-3 col-4 mb-3">
                                            <a href="{{ route('admin.gallery.show', $image->id) }}">
                                                <img src="{{ $image->thumbnail_url }}"
                                                    alt="{{ $image->title ?? 'Image' }}" class="img-fluid rounded"
                                                    style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;">
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Fullscreen Modal -->
    <div class="modal fade" id="fullscreenModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img id="fullscreenImage" src="" alt="Fullscreen Image"
                        style="max-width: 100%; max-height: 100%;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function openFullscreen(imageSrc) {
            document.getElementById('fullscreenImage').src = imageSrc;
            $('#fullscreenModal').modal('show');
        }

        // Keyboard navigation
        $(document).keydown(function(e) {
            if (e.key === 'ArrowLeft' && {
                    {
                        $previous ? 'true' : 'false'
                    }
                }) {
                window.location.href = '{{ $previous ? route('admin.gallery.show', $previous->id) : '#' }}';
            } else if (e.key === 'ArrowRight' && {
                    {
                        $next ? 'true' : 'false'
                    }
                }) {
                window.location.href = '{{ $next ? route('admin.gallery.show', $next->id) : '#' }}';
            }
        });
    </script>
@endsection
