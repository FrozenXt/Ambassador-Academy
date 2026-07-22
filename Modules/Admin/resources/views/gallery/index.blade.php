@extends('admin::layouts.app')

@section('page_title', 'Gallery Management')

@section('page_actions')
    <a href="{{ route('admin.gallery.create', ['album_id' => request('album_id')]) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-upload mr-1"></i> Upload Image
    </a>
    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#bulkUploadModal">
        <i class="fas fa-cloud-upload-alt mr-1"></i> Bulk Upload
    </button>
@endsection

@section('admin_content')

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="small-box bg-info mb-0">
                <div class="inner">
                    <h3>{{ $statistics['total'] }}</h3>
                    <p>Total Images</p>
                </div>
                <div class="icon"><i class="fas fa-images"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="small-box bg-success mb-0">
                <div class="inner">
                    <h3>{{ $statistics['active'] }}</h3>
                    <p>Active</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="small-box bg-secondary mb-0">
                <div class="inner">
                    <h3>{{ $statistics['inactive'] }}</h3>
                    <p>Inactive</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="small-box bg-warning mb-0">
                <div class="inner">
                    <h3>{{ $statistics['featured'] }}</h3>
                    <p>Featured</p>
                </div>
                <div class="icon"><i class="fas fa-star"></i></div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            {{-- Single combined form: previously "album" and "type" were two
                 separate forms, so picking one silently dropped the other. --}}
            <form method="GET" action="{{ route('admin.gallery.index') }}" id="filterForm">
                <div class="form-row align-items-end">

                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <label class="small text-muted mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Search by title...">
                    </div>

                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <label class="small text-muted mb-1">Album</label>

                        <select name="album_id" class="form-control form-control-sm">
                            <option value="">All Albums</option>

                            @foreach ($albums as $album)
                                <option value="{{ $album->id }}"
                                    {{ request('album_id') == $album->id ? 'selected' : '' }}>
                                    {{ $album->title }}
                                    @if ($album->code)
                                        — {{ \Modules\Common\Entities\Album::ALBUM_CODES[$album->code] ?? $album->code }}
                                    @endif
                                    ({{ $album->gallery_count ?? 0 }} images)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <label class="small text-muted mb-1">Type</label>
                        <select name="image_type" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            @foreach (\Modules\Common\Entities\Gallery::getImageTypes() as $key => $type)
                                <option value="{{ $key }}" {{ request('image_type') == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill mr-1">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            @if (request()->anyFilled(['search', 'album_id', 'image_type']))
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-default btn-sm"
                                    title="Clear filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Gallery grid --}}
    <div class="card card-outline card-primary">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
            <h3 class="card-title mb-0">
                <i class="fas fa-images mr-2"></i> Images
            </h3>

            <div class="d-flex align-items-center">
                <div class="custom-control custom-checkbox mr-3">
                    <input type="checkbox" class="custom-control-input" id="selectAllImages"
                        onclick="toggleSelectAll(this)">
                    <label class="custom-control-label small" for="selectAllImages">Select all</label>
                </div>

                <button type="button" class="btn btn-danger btn-sm mr-2" id="bulkDeleteBtn" style="display:none;">
                    <i class="fas fa-trash mr-1"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>

                <span class="badge badge-primary">{{ $gallery->total() }} total</span>
            </div>
        </div>

        <div class="card-body">
            <div class="row" id="galleryGrid">
                @forelse($gallery as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 gallery-item" data-id="{{ $item->id }}">
                        <div class="card h-100 gallery-card">

                            <div class="gallery-thumb position-relative">
                                <span class="drag-handle" title="Drag to reorder">
                                    <i class="fas fa-grip-vertical"></i>
                                </span>

                                <input type="checkbox" class="gallery-checkbox" value="{{ $item->id }}">

                                @if ($item->is_featured)
                                    <span class="badge badge-warning featured-badge">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @endif

                                <a href="{{ route('admin.gallery.show', $item->id) }}">
                                    <img src="{{ $item->thumbnail_url }}" class="img-fluid" loading="lazy"
                                        alt="{{ $item->title ?? 'Gallery Image' }}">
                                </a>

                                <div class="gallery-hover-actions">
                                    <a href="{{ route('admin.gallery.show', $item->id) }}" class="btn btn-sm btn-light"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ url('admin/gallery/' . $item->id . '/edit') }}"
                                        class="btn btn-sm btn-light" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light text-danger" title="Delete"
                                        onclick="deleteSingleImage({{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body p-2">
                                <h6 class="mb-1 text-truncate" title="{{ $item->title ?: 'Untitled' }}">
                                    {{ $item->title ?: 'Untitled' }}
                                </h6>
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-folder mr-1"></i>{{ $item->album?->title ?? 'No Album' }}
                                </small>

                                <div class="d-flex flex-wrap" style="gap:4px;">
                                    <span class="badge badge-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    <span class="badge badge-light border">#{{ $item->sort_order }}</span>
                                    @if ($item->image_type)
                                        <span class="badge badge-primary">
                                            {{ ucfirst(str_replace('_', ' ', $item->image_type)) }}
                                        </span>
                                    @endif
                                    @if ($item->image_position)
                                        <span class="badge badge-secondary">
                                            {{ ucfirst(str_replace('-', ' ', $item->image_position)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-images fa-3x mb-3 d-block"></i>
                            No images found.
                            <a href="{{ route('admin.gallery.create') }}">Upload your first image</a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($gallery->hasPages())
                <div class="d-flex justify-content-end mt-2">
                    {{ $gallery->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Hidden delete form for single-item delete --}}
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <div class="modal fade" id="bulkUploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Images</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="bulkUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Album</label>
                            <select name="album_id" class="form-control" required>
                                <option value="">Select Album</option>
                                @foreach ($albums as $album)
                                    <option value="{{ $album->id }}"
                                        {{ request('album_id') == $album->id ? 'selected' : '' }}>
                                        {{ $album->title }} ({{ $album->gallery_count ?? 0 }} images)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Images</label>
                            <input type="file" name="images[]" class="form-control-file" multiple accept="image/*"
                                required>
                            <small class="text-muted">You can select multiple images at once. Max 5MB per image.</small>
                        </div>
                        <div class="progress" style="display:none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Upload All
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extra_css')
    <style>
        .gallery-thumb {
            overflow: hidden;
            background: #f1f5f9;
        }

        .gallery-thumb img {
            height: 200px;
            width: 100%;
            object-fit: cover;
            display: block;
            transition: transform .25s ease;
        }

        .gallery-card:hover .gallery-thumb img {
            transform: scale(1.04);
        }

        .gallery-checkbox {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            width: 18px;
            height: 18px;
        }

        .drag-handle {
            position: absolute;
            top: 8px;
            right: 10px;
            z-index: 10;
            color: #fff;
            background: rgba(0, 0, 0, .45);
            border-radius: 4px;
            padding: 3px 6px;
            cursor: grab;
            opacity: 0;
            transition: opacity .15s ease;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .gallery-card:hover .drag-handle {
            opacity: 1;
        }

        .featured-badge {
            position: absolute;
            top: 40px;
            right: 10px;
            z-index: 9;
        }

        .gallery-hover-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            gap: 6px;
            justify-content: center;
            padding: 8px;
            background: linear-gradient(to top, rgba(0, 0, 0, .55), transparent);
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .15s ease, transform .15s ease;
        }

        .gallery-card:hover .gallery-hover-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .sortable-ghost {
            opacity: .5;
            background: #c8ebfb;
        }
    </style>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            const selectedItems = new Set();

            function refreshBulkBar() {
                $('#selectedCount').text(selectedItems.size);
                $('#bulkDeleteBtn').toggle(selectedItems.size > 0);
                $('#selectAllImages').prop('checked',
                    selectedItems.size > 0 && selectedItems.size === $('.gallery-checkbox').length
                );
            }

            $('.gallery-checkbox').on('change', function() {
                const id = $(this).val();
                $(this).is(':checked') ? selectedItems.add(id) : selectedItems.delete(id);
                refreshBulkBar();
            });

            window.toggleSelectAll = function(source) {
                $('.gallery-checkbox').each(function() {
                    $(this).prop('checked', source.checked);
                    source.checked ? selectedItems.add($(this).val()) : selectedItems.delete($(this)
                        .val());
                });
                refreshBulkBar();
            };

            window.deleteSingleImage = function(id) {
                if (!confirm('Delete this image?')) return;
                const form = document.getElementById('deleteForm');
                form.action = "{{ url('admin/gallery') }}/" + id;
                form.submit();
            };

            $('#bulkDeleteBtn').on('click', function() {
                if (!confirm('Are you sure you want to delete ' + selectedItems.size + ' images?')) return;

                $.ajax({
                    url: '{{ route('admin.gallery.bulk-delete') }}',
                    method: 'POST',
                    data: {
                        ids: Array.from(selectedItems),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Delete failed');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Delete failed: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                    }
                });
            });

            // Bulk upload
            $('#bulkUploadForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('admin.gallery.bulk-upload') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('.progress').show();
                        $('.progress-bar').css('width', '0%');
                    },
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = (e.loaded / e.total) * 100;
                                $('.progress-bar').css('width', percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Upload failed');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Upload failed: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                    },
                    complete: function() {
                        $('.progress').hide();
                    }
                });
            });

            // Drag-and-drop reorder — handle is now the dedicated grip icon,
            // not the whole card, so clicking Edit/Delete/checkbox no longer
            // fights with the drag gesture.
            const grid = document.getElementById('galleryGrid');
            if (grid) {
                new Sortable(grid, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function() {
                        const orders = [];
                        document.querySelectorAll('#galleryGrid .gallery-item').forEach(function(el,
                            index) {
                            orders.push({
                                id: parseInt(el.getAttribute('data-id')),
                                sort_order: index + 1
                            });
                        });

                        $.ajax({
                            url: '{{ route('admin.gallery.sort-order') }}',
                            method: 'POST',
                            data: {
                                orders: orders,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                response.success ?
                                    toastr.success('Sort order updated') :
                                    toastr.error('Failed to update sort order');
                            },
                            error: function() {
                                toastr.error('Failed to update sort order');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection
