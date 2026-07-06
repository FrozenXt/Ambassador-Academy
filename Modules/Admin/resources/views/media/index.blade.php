@extends('admin::layouts.app')
@section('page_title', 'Media Library')

@section('extra_css')
    <style>
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .media-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            cursor: pointer;
        }

        .media-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .media-item.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .media-thumb {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .media-thumb-icon {
            width: 100%;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background: #f3f4f6;
        }

        .media-info {
            padding: 8px;
            background: #fff;
        }

        .media-name {
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #1f2937;
        }

        .media-size {
            font-size: 0.65rem;
            color: #6b7280;
        }

        .media-check {
            position: absolute;
            top: 6px;
            left: 6px;
            z-index: 10;
        }

        .media-check input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .media-actions {
            position: absolute;
            top: 6px;
            right: 6px;
            display: none;
            gap: 4px;
            z-index: 10;
        }

        .media-item:hover .media-actions {
            display: flex;
        }

        .media-actions .btn {
            padding: 2px 6px;
            font-size: 11px;
        }

        .drop-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            background: #fafbff;
        }

        .drop-zone.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .stat-box {
            background: #fff;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .stat-box .stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b82f6;
        }

        .stat-box .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9998;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .file-preview {
            display: inline-block;
            margin: 5px;
            padding: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
        }

        .file-preview img {
            max-width: 80px;
            max-height: 60px;
            border-radius: 4px;
        }

        .error-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 350px;
            max-width: 500px;
        }

        .error-alert {
            margin-bottom: 10px;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Error Display Area --}}
    <div id="errorContainer" class="error-container"></div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Files</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num text-success">{{ $stats['images'] }}</div>
                <div class="stat-label">Images</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num text-info">{{ $stats['videos'] }}</div>
                <div class="stat-label">Videos</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num text-warning">{{ $stats['documents'] }}</div>
                <div class="stat-label">Documents</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num text-secondary">{{ $stats['others'] }}</div>
                <div class="stat-label">Others</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-box">
                <div class="stat-num text-danger" style="font-size:1.1rem;">{{ $stats['formatted_size'] }}</div>
                <div class="stat-label">Total Size</div>
            </div>
        </div>
    </div>

    {{-- Upload Area --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cloud-upload-alt text-primary mr-2"></i> Upload Files</h5>
        </div>
        <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf

                <div class="drop-zone" id="dropZone">
                    <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>
                    <h5>Drag & Drop files here</h5>
                    <p class="text-muted mb-3">or</p>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-folder-open mr-2"></i> Browse Files
                    </button>
                    <input type="file" id="fileInput" name="files[]" multiple class="d-none"
                        accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" />
                    <p class="text-muted small mt-3 mb-0">
                        <i class="fas fa-info-circle"></i> Supports: JPG, PNG, GIF, WEBP, MP4, PDF, DOC, XLS — Max 10MB each
                    </p>
                </div>

                {{-- Preview Queue --}}
                <div id="previewQueue" class="mt-3" style="display: none;">
                    <h6><i class="fas fa-images"></i> Preview (<span id="previewCount">0</span> files)</h6>
                    <div id="previewList" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;"></div>
                </div>

                {{-- Extra fields --}}
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Alt Text</label>
                            <input type="text" name="alt_text" class="form-control"
                                placeholder="Describe the media..." />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Media title..." />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i class="fas fa-upload mr-2"></i> Upload (<span id="uploadCount">0</span>)
                    </button>
                </div>

                <div id="uploadProgress" class="mt-3" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar"
                            style="width: 0%;"></div>
                    </div>
                    <p class="text-center small mt-2" id="progressText">0%</p>
                </div>
            </form>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('admin.media.index') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search files..." style="width: 250px;">
                            <select name="type" class="form-control ml-2">
                                <option value="">All Types</option>
                                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
                                <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents
                                </option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Others</option>
                            </select>
                            <div class="input-group-append ml-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.media.index') }}" class="btn btn-secondary ml-1">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <span id="selectedCount" class="text-muted mr-2">0 selected</span>
                    <button class="btn btn-sm btn-primary" id="selectAllBtn">
                        <i class="fas fa-check-square"></i> Select All
                    </button>
                    <button class="btn btn-sm btn-danger ml-1" id="bulkDeleteBtn" style="display: none;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-images text-primary mr-2"></i> Media Files</h5>
        </div>
        <div class="card-body">
            @if ($media->count())
                <div class="media-grid" id="mediaGrid">
                    @foreach ($media as $item)
                        <div class="media-item" data-id="{{ $item->id }}">
                            <div class="media-check">
                                <input type="checkbox" class="media-checkbox" value="{{ $item->id }}">
                            </div>
                            <div class="media-actions">
                                <a href="{{ route('admin.media.show', $item->id) }}" class="btn btn-info btn-sm"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-danger btn-sm delete-single" data-id="{{ $item->id }}"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div onclick="window.location.href='{{ route('admin.media.show', $item->id) }}'">
                                @if ($item->type == 'image')
                                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->alt_text }}"
                                        class="media-thumb">
                                @elseif($item->type == 'video')
                                    <div class="media-thumb-icon">
                                        <i class="fas fa-video fa-3x text-info"></i>
                                    </div>
                                @elseif($item->type == 'document')
                                    <div class="media-thumb-icon">
                                        @if ($item->extension == 'pdf')
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        @elseif(in_array($item->extension, ['doc', 'docx']))
                                            <i class="fas fa-file-word fa-3x text-primary"></i>
                                        @elseif(in_array($item->extension, ['xls', 'xlsx']))
                                            <i class="fas fa-file-excel fa-3x text-success"></i>
                                        @else
                                            <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                        @endif
                                    </div>
                                @else
                                    <div class="media-thumb-icon">
                                        <i class="fas fa-file fa-3x text-secondary"></i>
                                    </div>
                                @endif
                                <div class="media-info">
                                    <div class="media-name">{{ Str::limit($item->name, 25) }}</div>
                                    <div class="media-size">{{ $item->formatted_size }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $media->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-photo-video fa-4x mb-3"></i>
                    <h5>No media files found</h5>
                    <p>Upload some files using the form above.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center bg-white p-4 rounded">
            <div class="spinner-border text-primary mb-2"></div>
            <p>Uploading files...</p>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        let selectedIds = new Set();
        let selectedFiles = [];

        // Show error messages
        function showErrors(errors) {
            const errorContainer = $('#errorContainer');
            errorContainer.empty();

            if (typeof errors === 'object') {
                // Display multiple errors
                for (let field in errors) {
                    if (errors.hasOwnProperty(field) && Array.isArray(errors[field])) {
                        errors[field].forEach(message => {
                            const errorDiv = $(`
                                <div class="alert alert-danger error-alert">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <strong>Error!</strong> ${message}
                                </div>
                            `);
                            errorContainer.append(errorDiv);

                            // Auto remove after 5 seconds
                            setTimeout(() => {
                                errorDiv.fadeOut(300, () => errorDiv.remove());
                            }, 5000);
                        });
                    }
                }
            } else if (typeof errors === 'string') {
                // Display single error
                const errorDiv = $(`
                    <div class="alert alert-danger error-alert">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Error!</strong> ${errors}
                    </div>
                `);
                errorContainer.append(errorDiv);

                setTimeout(() => {
                    errorDiv.fadeOut(300, () => errorDiv.remove());
                }, 5000);
            }
        }

        // Show success message
        function showSuccess(message) {
            const errorContainer = $('#errorContainer');
            const successDiv = $(`
                <div class="alert alert-success error-alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Success!</strong> ${message}
                </div>
            `);
            errorContainer.append(successDiv);

            setTimeout(() => {
                successDiv.fadeOut(300, () => successDiv.remove());
            }, 3000);
        }

        // Preview files before upload
        function previewFiles(files) {
            selectedFiles = Array.from(files);
            const previewList = $('#previewList');
            const previewQueue = $('#previewQueue');
            const previewCount = $('#previewCount');

            previewList.empty();

            if (selectedFiles.length === 0) {
                previewQueue.hide();
                return;
            }

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const previewItem = $('<div class="file-preview d-inline-block text-center mr-2 mb-2"></div>');

                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewItem.html(`
                            <img src="${e.target.result}" style="width: 80px; height: 60px; object-fit: cover;">
                            <div class="small text-muted mt-1">${file.name.substring(0, 15)}</div>
                        `);
                    } else {
                        let icon = 'fa-file';
                        if (file.type.startsWith('video/')) icon = 'fa-video';
                        else if (file.type === 'application/pdf') icon = 'fa-file-pdf';
                        else if (file.type.includes('word')) icon = 'fa-file-word';
                        else if (file.type.includes('excel')) icon = 'fa-file-excel';

                        previewItem.html(`
                            <div style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center; background: #f3f4f6;">
                                <i class="fas ${icon} fa-2x text-secondary"></i>
                            </div>
                            <div class="small text-muted mt-1">${file.name.substring(0, 15)}</div>
                        `);
                    }
                    previewList.append(previewItem);
                };

                if (file.type.startsWith('image/')) {
                    reader.readAsDataURL(file);
                } else {
                    previewItem.html(`
                        <div style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center; background: #f3f4f6;">
                            <i class="fas fa-file fa-2x text-secondary"></i>
                        </div>
                        <div class="small text-muted mt-1">${file.name.substring(0, 15)}</div>
                    `);
                    previewList.append(previewItem);
                }
            });

            previewCount.text(selectedFiles.length);
            $('#uploadCount').text(selectedFiles.length);
            $('#uploadBtn').prop('disabled', selectedFiles.length === 0);
            previewQueue.show();
        }

        // File input change
        $('#fileInput').on('change', function() {
            previewFiles(this.files);
        });

        // Drag and drop
        const dropZone = $('#dropZone');
        dropZone.on('dragover', function(e) {
            e.preventDefault();
            dropZone.addClass('dragover');
        });

        dropZone.on('dragleave', function() {
            dropZone.removeClass('dragover');
        });

        dropZone.on('drop', function(e) {
            e.preventDefault();
            dropZone.removeClass('dragover');
            const files = e.originalEvent.dataTransfer.files;
            $('#fileInput')[0].files = files;
            previewFiles(files);
        });

        dropZone.on('click', function() {
            $('#fileInput').click();
        });

        // Upload form
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            if (selectedFiles.length === 0) {
                showErrors('Please select files to upload');
                return;
            }

            const formData = new FormData(this);

            $('#uploadBtn').prop('disabled', true);
            $('#uploadProgress').show();
            $('#loadingOverlay').show();

            $.ajax({
                url: '{{ route('admin.media.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                xhr: function() {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            $('#progressBar').css('width', percent + '%');
                            $('#progressText').text(percent + '%');
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    $('#loadingOverlay').hide();
                    $('#uploadProgress').hide();
                    $('#progressBar').css('width', '0%');

                    if (response.success) {
                        showSuccess(response.message);
                        // Clear preview
                        $('#previewQueue').hide();
                        $('#previewList').empty();
                        selectedFiles = [];
                        $('#fileInput').val('');
                        $('#uploadCount').text('0');
                        $('#uploadBtn').prop('disabled', true);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        if (response.errors) {
                            showErrors(response.errors);
                        } else {
                            showErrors(response.message || 'Upload failed');
                        }
                        $('#uploadBtn').prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    $('#loadingOverlay').hide();
                    $('#uploadProgress').hide();

                    let errorMessage = 'Upload failed. Please try again.';

                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        // Validation errors
                        showErrors(xhr.responseJSON.errors);
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showErrors(xhr.responseJSON.message);
                    } else {
                        showErrors(errorMessage);
                    }

                    $('#uploadBtn').prop('disabled', false);
                }
            });
        });

        // Select/Deselect
        function updateSelection() {
            const count = selectedIds.size;
            $('#selectedCount').text(count + ' selected');
            $('#bulkDeleteBtn').toggle(count > 0);
        }

        $('.media-checkbox').on('change', function() {
            const id = $(this).val();
            const item = $(this).closest('.media-item');

            if (this.checked) {
                selectedIds.add(id);
                item.addClass('selected');
            } else {
                selectedIds.delete(id);
                item.removeClass('selected');
            }
            updateSelection();
        });

        // Select All
        $('#selectAllBtn').on('click', function() {
            const checkboxes = $('.media-checkbox');
            const allChecked = checkboxes.length === selectedIds.size;

            checkboxes.each(function() {
                this.checked = !allChecked;
                const id = $(this).val();
                const item = $(this).closest('.media-item');

                if (!allChecked) {
                    selectedIds.add(id);
                    item.addClass('selected');
                } else {
                    selectedIds.delete(id);
                    item.removeClass('selected');
                }
            });
            updateSelection();
        });

        // Bulk Delete
        $('#bulkDeleteBtn').on('click', function() {
            if (selectedIds.size === 0) return;

            if (!confirm(`Delete ${selectedIds.size} file(s)? This action cannot be undone.`)) return;

            $.ajax({
                url: '{{ route('admin.media.bulk-delete') }}',
                type: 'POST',
                data: JSON.stringify({
                    ids: Array.from(selectedIds)
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showErrors(response.message || 'Delete failed');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Delete failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showErrors(errorMessage);
                }
            });
        });

        // Single Delete
        $('.delete-single').on('click', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');

            if (!confirm('Delete this file? This action cannot be undone.')) return;

            $.ajax({
                url: `/admin/media/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showErrors(response.message || 'Delete failed');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Delete failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showErrors(errorMessage);
                }
            });
        });
    </script>
@endsection
