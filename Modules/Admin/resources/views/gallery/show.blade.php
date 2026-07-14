@extends('admin::layouts.app')

@section('page_title', 'View Image: ' . ($gallery->title ?? 'Untitled'))

@section('page_actions')
    <a href="{{ route('admin.gallery.index', ['album_id' => $gallery->album_id]) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Gallery
    </a>
    <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-info btn-sm">
        <i class="fas fa-edit mr-1"></i> Edit Image
    </a>
@endsection

@section('admin_content')

    <div class="row">
        {{-- Main image + viewer --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card card-outline card-primary h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-image mr-2"></i>{{ $gallery->title ?: 'Untitled' }}
                    </h3>
                    <span class="badge badge-{{ $gallery->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($gallery->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="image-viewer text-center">
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title ?? 'Gallery Image' }}"
                            class="img-fluid rounded viewer-img" onclick="openFullscreen(this.src)">
                        <button type="button" class="btn btn-sm btn-dark viewer-expand"
                            onclick="openFullscreen(document.querySelector('.viewer-img').src)" title="View fullscreen">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                    {{-- Prev / Next --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        @if ($previous)
                            <a href="{{ route('admin.gallery.show', $previous->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </a>
                        @else
                            <span></span>
                        @endif

                        <small class="text-muted d-none d-sm-inline">
                            Use <kbd>&larr;</kbd> / <kbd>&rarr;</kbd> to navigate
                        </small>

                        @if ($next)
                            <a href="{{ route('admin.gallery.show', $next->id) }}" class="btn btn-outline-secondary">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        @else
                            <span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Details sidebar --}}
        <div class="col-lg-4">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title mb-0"><i class="fas fa-circle-info mr-2"></i>Image Details</h3>
                </div>

                <div class="card-body">
                    @if ($gallery->description)
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-align-left"></i> Description</span>
                            <p class="mb-0">{{ $gallery->description }}</p>
                        </div>
                    @endif

                    @if ($gallery->image_alt)
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-tag"></i> Alt Text</span>
                            <p class="mb-0">{{ $gallery->image_alt }}</p>
                        </div>
                    @endif

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-folder"></i> Album</span>
                        <p class="mb-0">
                            @if ($gallery->album)
                                <a href="{{ route('admin.gallery.index', ['album_id' => $gallery->album_id]) }}">
                                    {{ $gallery->album->title }}
                                </a>
                            @else
                                <span class="text-muted">No album</span>
                            @endif
                        </p>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-tag"></i> Type</span>
                        <p class="mb-0">
                            <span class="badge badge-primary">
                                {{ ucfirst(str_replace('_', ' ', $gallery->image_type ?? 'general')) }}
                            </span>
                        </p>
                    </div>

                    @if ($gallery->image_position)
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-location-dot"></i> Position</span>
                            <p class="mb-0">
                                <span class="badge badge-secondary">
                                    {{ ucfirst(str_replace('-', ' ', $gallery->image_position)) }}
                                </span>
                            </p>
                        </div>
                    @endif

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-sort-numeric-down"></i> Sort Order</span>
                        <p class="mb-0">{{ $gallery->sort_order }}</p>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-star"></i> Featured</span>
                        <p class="mb-0">
                            @if ($gallery->is_featured)
                                <span class="badge badge-warning"><i class="fas fa-star"></i> Yes</span>
                            @else
                                <span class="badge badge-light border">No</span>
                            @endif
                        </p>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar"></i> Created</span>
                        <p class="mb-0">{{ $gallery->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar-alt"></i> Last Updated</span>
                        <p class="mb-0">{{ $gallery->updated_at->format('d M Y, h:i A') }}</p>
                    </div>

                    {{-- <div class="detail-row mb-0">
                        <span class="detail-label"><i class="fas fa-database"></i> File Path</span>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control text-truncate" value="{{ $gallery->image_path }}"
                                readonly id="filePathInput" style="font-size:.75rem;">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyFilePath()"
                                    title="Copy path">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-info btn-block">
                        <i class="fas fa-edit mr-1"></i> Edit Image
                    </a>
                    <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" class="mt-2"
                        onsubmit="return confirm('Delete this image permanently? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-1"></i> Delete Image
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- More from this album --}}
    @if ($albumImages && $albumImages->count() > 1)
        <div class="card card-outline card-primary mt-2">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-images mr-2"></i> More from {{ $gallery->album->title ?? 'this album' }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($albumImages as $image)
                        @continue($image->id == $gallery->id)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                            <a href="{{ route('admin.gallery.show', $image->id) }}" class="related-thumb d-block">
                                <img src="{{ $image->thumbnail_url }}" alt="{{ $image->title ?? 'Image' }}"
                                    class="img-fluid rounded" loading="lazy">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Fullscreen viewer modal — Bootstrap 4 markup, matching the rest of
         the admin panel (bootstrap@4.6.2 is what's actually loaded here;
         the previous "data-bs-dismiss" / "btn-close" / "modal-fullscreen"
         classes are Bootstrap 5 only and silently do nothing under BS4). --}}
    <div class="modal fade" id="fullscreenModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen-custom">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img id="fullscreenImage" src="" alt="Fullscreen Image"
                        style="max-width:100%;max-height:100%;">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra_css')
    <style>
        .image-viewer {
            position: relative;
        }

        .viewer-img {
            max-height: 560px;
            width: auto;
            cursor: zoom-in;
        }

        .viewer-expand {
            position: absolute;
            bottom: 10px;
            right: 10px;
            opacity: .85;
        }

        .detail-row {
            padding-bottom: .85rem;
            margin-bottom: .85rem;
            border-bottom: 1px solid #eef1f4;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8a94a6;
            margin-bottom: .25rem;
        }

        .related-thumb img {
            height: 90px;
            width: 100%;
            object-fit: cover;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .related-thumb:hover img {
            transform: scale(1.03);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        /* Bootstrap-4-compatible "fullscreen" modal, since modal-fullscreen
                           is a Bootstrap 5 class that doesn't exist in BS4 */
        .modal-fullscreen-custom {
            width: 100vw;
            max-width: 100vw;
            height: 100vh;
            margin: 0;
        }

        .modal-fullscreen-custom .modal-content {
            height: 100vh;
            border: 0;
            border-radius: 0;
        }
    </style>
@endsection

@section('extra_js')
    <script>
        const prevImageUrl = @json($previous ? route('admin.gallery.show', $previous->id) : null);
        const nextImageUrl = @json($next ? route('admin.gallery.show', $next->id) : null);

        function openFullscreen(imageSrc) {
            document.getElementById('fullscreenImage').src = imageSrc;
            $('#fullscreenModal').modal('show');
        }

        function copyFilePath() {
            const input = document.getElementById('filePathInput');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(function() {
                toastr.success('Path copied to clipboard');
            }).catch(function() {
                document.execCommand('copy');
            });
        }

        // Keyboard navigation — disabled while the fullscreen modal is open
        // or while typing in a field, so arrow keys don't unexpectedly
        // navigate away.
        $(document).on('keydown', function(e) {
            const modalOpen = $('#fullscreenModal').hasClass('show');
            const typing = $(e.target).is('input, textarea, select');
            if (modalOpen || typing) return;

            if (e.key === 'ArrowLeft' && prevImageUrl) {
                window.location.href = prevImageUrl;
            } else if (e.key === 'ArrowRight' && nextImageUrl) {
                window.location.href = nextImageUrl;
            }
        });
    </script>
@endsection
