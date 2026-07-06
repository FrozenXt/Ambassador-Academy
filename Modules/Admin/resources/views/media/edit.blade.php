@extends('admin::layouts.app')
@section('page_title', 'Edit Media - ' . $media->name)

@section('extra_css')
    <style>
        .preview-container {
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .preview-image {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            background: #fff;
        }

        .preview-video {
            width: 100%;
            max-height: 400px;
            background: #000;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
        }

        .tag-input {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 42px;
            cursor: text;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            background: #e5e7eb;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .tag-remove {
            margin-left: 6px;
            cursor: pointer;
            color: #6b7280;
        }

        .tag-remove:hover {
            color: #dc2626;
        }

        .tag-input-field {
            border: none;
            outline: none;
            flex: 1;
            min-width: 100px;
            background: transparent;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
@endsection

@section('admin_content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit text-primary mr-2"></i>
                        Edit Media: {{ $media->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form id="editMediaForm" action="{{ route('admin.media.update', $media->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $media->title) }}" placeholder="Enter media title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alt_text">Alt Text</label>
                            <input type="text" class="form-control @error('alt_text') is-invalid @enderror"
                                id="alt_text" name="alt_text" value="{{ old('alt_text', $media->alt_text) }}"
                                placeholder="Alternative text for accessibility">
                            @error('alt_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Important for SEO and accessibility. Describe what's in the image/video.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="Enter media description">{{ old('description', $media->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <div class="tag-input" id="tagInput" onclick="focusTagInput()">
                                <div id="tagList">
                                    @if ($media->tags)
                                        @foreach ($media->tags as $tag)
                                            <span class="tag">
                                                {{ $tag }}
                                                <span class="tag-remove"
                                                    onclick="removeTag(this, '{{ $tag }}')">&times;</span>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                                <input type="text" id="tagInputField" class="tag-input-field" placeholder="Add tags..."
                                    onkeydown="handleTagInput(event)">
                            </div>
                            <input type="hidden" name="tags" id="tagsHidden"
                                value="{{ old('tags', $media->tags ? implode(', ', $media->tags) : '') }}">
                            <small class="form-text text-muted">
                                Press Enter or comma to add tags. Helps organize and search media files.
                            </small>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Update Media
                            </button>
                            <a href="{{ route('admin.media.show', $media->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                            <button type="button" class="btn btn-danger ml-auto" onclick="confirmDelete()">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info mr-2"></i>
                        File Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-card">
                        @if ($media->type === 'image')
                            <div class="preview-container">
                                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->alt_text }}"
                                    class="preview-image">
                            </div>
                        @elseif($media->type === 'video')
                            <div class="preview-container">
                                <video controls class="preview-video">
                                    <source src="{{ asset('storage/' . $media->file_path) }}"
                                        type="{{ $media->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded">
                                <i class="fas fa-file-alt fa-4x text-secondary mb-3"></i>
                                <p class="mb-0">{{ $media->file_name }}</p>
                                <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fas fa-download mr-1"></i> Download File
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="info-item">
                        <span class="info-label">File Name:</span>
                        <span class="text-muted">{{ $media->file_name }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">File Size:</span>
                        <span class="text-muted">{{ $media->formatted_size }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">File Type:</span>
                        <span class="badge badge-info">{{ strtoupper($media->extension) }}</span>
                    </div>

                    @if ($media->width)
                        <div class="info-item">
                            <span class="info-label">Dimensions:</span>
                            <span class="text-muted">{{ $media->width }} × {{ $media->height }} px</span>
                        </div>
                    @endif

                    <div class="info-item">
                        <span class="info-label">Media Type:</span>
                        <span class="badge badge-secondary">{{ ucfirst($media->type) }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Uploaded:</span>
                        <span class="text-muted">{{ $media->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Last Modified:</span>
                        <span class="text-muted">{{ $media->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this media file?</p>
                    <p class="text-danger mb-0"><strong>This action cannot be undone!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <form id="deleteForm" action="{{ route('admin.media.destroy', $media->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" action="{{ route('admin.media.destroy', $media->id) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('extra_js')
    <script>
        let tags = [];

        // Initialize tags from existing data
        @if ($media->tags)
            tags = {!! json_encode($media->tags) !!};
        @endif

        function updateTagsHidden() {
            document.getElementById('tagsHidden').value = tags.join(', ');
        }

        function renderTags() {
            const tagList = document.getElementById('tagList');
            tagList.innerHTML = '';
            tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'tag';
                tagElement.innerHTML =
                    `${tag}<span class="tag-remove" onclick="removeTagFromArray('${tag}')">&times;</span>`;
                tagList.appendChild(tagElement);
            });
            updateTagsHidden();
        }

        function addTag(tag) {
            tag = tag.trim().toLowerCase();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
                return true;
            }
            return false;
        }

        function removeTagFromArray(tag) {
            const index = tags.indexOf(tag);
            if (index > -1) {
                tags.splice(index, 1);
                renderTags();
            }
        }

        function removeTag(element, tag) {
            removeTagFromArray(tag);
        }

        function handleTagInput(event) {
            const input = event.target;
            const value = input.value;

            if (event.key === 'Enter' || event.key === ',' || event.key === ' ') {
                event.preventDefault();
                if (value.trim()) {
                    addTag(value);
                    input.value = '';
                }
            } else if (event.key === 'Backspace' && value === '' && tags.length > 0) {
                tags.pop();
                renderTags();
            }
        }

        function focusTagInput() {
            document.getElementById('tagInputField').focus();
        }

        function confirmDelete() {
            $('#deleteModal').modal('show');
        }

        // Auto-save functionality (optional)
        let autoSaveTimer;
        const form = document.getElementById('editMediaForm');
        const formFields = ['title', 'alt_text', 'description'];

        function autoSave() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Auto-saved successfully', 'success');
                        }
                    })
                    .catch(error => console.error('Auto-save failed:', error));
            }, 3000);
        }

        // Add auto-save listeners
        formFields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener('input', autoSave);
            }
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText =
                'top: 20px; right: 20px; z-index: 9999; min-width: 250px; animation: slideIn 0.3s ease-out;';
            notification.innerHTML =
                `<i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i> ${message}`;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);

        // Initialize
        renderTags();
    </script>
@endsection
