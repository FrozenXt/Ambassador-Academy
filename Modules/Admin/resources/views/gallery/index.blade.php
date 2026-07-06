@extends('admin::layouts.app')

@section('page_title', 'Gallery Management')

@section('admin_content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-images mr-2"></i> Gallery Management
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.gallery.create', ['album_id' => $albumId]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Upload Image
                    </a>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#bulkUploadModal">
                        <i class="fas fa-cloud-upload-alt"></i> Bulk Upload
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('admin.gallery.index') }}" id="filterForm">
                            <div class="form-group">
                                <label>Filter by Album</label>
                                <select name="album_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Albums</option>
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}"
                                            {{ request('album_id') == $album->id ? 'selected' : '' }}>
                                            {{ $album->title }} ({{ $album->gallery_count ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('admin.gallery.index') }}" id="filterTypeForm">
                            <div class="form-group">
                                <label>Filter by Type</label>
                                <select name="image_type" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Types</option>
                                    @foreach (\Modules\Common\Entities\Gallery::getImageTypes() as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ request('image_type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if (request('album_id'))
                                <input type="hidden" name="album_id" value="{{ request('album_id') }}">
                            @endif
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary btn-sm mt-4">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-images"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Images</span>
                                <span class="info-box-number">{{ $statistics['total'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active</span>
                                <span class="info-box-number">{{ $statistics['active'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-times-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Inactive</span>
                                <span class="info-box-number">{{ $statistics['inactive'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-star"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Featured</span>
                                <span class="info-box-number">{{ $statistics['featured'] }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-database"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Size</span>
                                <span class="info-box-number">{{ $statistics['total_size'] }}</span>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="float-right mb-2">
                            <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                        </div>
                        <div class="clearfix"></div>

                        <div class="row" id="galleryGrid">
                            @forelse($gallery as $item)
                                <div class="col-md-3 col-sm-6 mb-4 gallery-item" data-id="{{ $item->id }}">
                                    <div class="card h-100">
                                        <div class="card-body p-0">
                                            <div class="position-relative">
                                                <input type="checkbox" class="gallery-checkbox" value="{{ $item->id }}"
                                                    style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                <a href="{{ route('admin.gallery.show', $item->id) }}">
                                                    <img src="{{ $item->thumbnail_url }}" class="img-fluid"
                                                        alt="{{ $item->title ?? 'Gallery Image' }}"
                                                        style="height: 200px; width: 100%; object-fit: cover;">
                                                    @if ($item->is_featured)
                                                        <span class="badge badge-warning position-absolute"
                                                            style="top: 10px; right: 10px;">
                                                            <i class="fas fa-star"></i> Featured
                                                        </span>
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="p-2">
                                                <h6 class="mb-1 text-truncate">{{ $item->title ?: 'Untitled' }}</h6>
                                                <small class="text-muted">
                                                    {{ $item->album?->title ?? 'No Album' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="mt-2 mb-2">
                                                <span
                                                    class="badge badge-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                                <span class="badge badge-info">Order: {{ $item->sort_order }}</span>
                                                @if ($item->image_type)
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-tag"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $item->image_type)) }}
                                                    </span>
                                                @endif
                                                @if ($item->image_position)
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-location-dot"></i>
                                                        {{ ucfirst(str_replace('-', ' ', $item->image_position)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ url('admin/gallery/' . $item->id . '/edit') }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ url('admin/gallery/' . $item->id) }}" method="POST"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this image?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle"></i> No images found.
                                        <a href="{{ route('admin.gallery.create') }}">Upload your first image</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            {{ $gallery->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="progress" style="display: none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // Bulk delete functionality
            let selectedItems = new Set();

            $('.gallery-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    selectedItems.add($(this).val());
                } else {
                    selectedItems.delete($(this).val());
                }

                $('#bulkDeleteBtn').toggle(selectedItems.size > 0);
            });

            $('#bulkDeleteBtn').on('click', function() {
                if (confirm('Are you sure you want to delete ' + selectedItems.size + ' images?')) {
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
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Delete failed: ' + (xhr.responseJSON?.message ||
                                'Unknown error'));
                        }
                    });
                }
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
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Upload failed: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                    },
                    complete: function() {
                        $('.progress').hide();
                    }
                });
            });

            // Sortable drag and drop
            const grid = document.getElementById('galleryGrid');
            if (grid) {
                new Sortable(grid, {
                    animation: 150,
                    handle: '.card',
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
                                if (response.success) {
                                    toastr.success('Sort order updated');
                                } else {
                                    toastr.error('Failed to update sort order');
                                }
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
    <style>
        .sortable-ghost {
            opacity: 0.5;
            background: #c8ebfb;
        }
    </style>
@endsection
