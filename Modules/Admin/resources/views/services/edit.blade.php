@extends('admin::layouts.app')
@section('page_title', 'Edit Service: ' . ($service->title ?? ''))

@section('page_actions')
    <div class="btn-group">
        <a href="{{ route('admin.services.index') }}" class="btn btn-default btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        @if ($service->status == 'active')
            <a href="{{ route('web.service.detail', $service->slug) }}" class="service-link">
                <i class="fas fa-eye mr-1"></i> Preview
            </a>
        @endif
    </div>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

    <style>
        #quill-editor {
            min-height: 400px;
            background: #fff;
        }

        .ql-container.ql-snow {
            min-height: 400px;
        }

        .ql-editor {
            min-height: 400px;
        }

        .icon-preview {
            font-size: 48px;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 10px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .icon-preview:hover {
            border-color: #4f46e5;
            background: #f3f4f6;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }

        .current-image {
            position: relative;
            display: inline-block;
        }

        .current-image .remove-image-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 12px;
        }

        .current-image .remove-image-btn:hover {
            transform: scale(1.1);
            background: #c82333;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        .card-header .card-title i {
            margin-right: 8px;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }

        .info-text {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .info-text i {
            color: #4f46e5;
            margin-right: 8px;
        }

        /* NEW STYLES FOR CROPPER AND DRAG & DROP */
        .upload-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #4f46e5 !important;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08), rgba(79, 70, 229, 0.04)) !important;
            transform: translateY(-2px);
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        /* Circular preview styles */
        .circular-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .preview-container {
            text-align: center;
        }

        .preview-container img:hover {
            transform: scale(1.05);
        }

        /* Cropper Modal Styles */
        .cropper-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.2s, opacity 0.2s;
        }

        .cropper-modal-overlay.active {
            visibility: visible;
            opacity: 1;
        }

        .cropper-modal-container {
            background: #fff;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .cropper-modal-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .cropper-modal-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .cropper-modal-header .close {
            font-size: 24px;
            font-weight: 300;
            line-height: 1;
            color: #000;
            opacity: 0.5;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .cropper-modal-header .close:hover {
            opacity: 0.75;
        }

        .cropper-modal-body {
            padding: 20px;
            background: #f8f9fa;
            overflow-y: auto;
            flex: 1;
        }

        .cropper-image-wrapper {
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #cropperImage {
            max-width: 100%;
            max-height: 500px;
            display: block;
            margin: 0 auto;
        }

        .aspect-buttons {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 12px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .aspect-btn {
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #dee2e6;
            background: #fff;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .aspect-btn:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .aspect-btn.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .cropper-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #fff;
        }

        .cropper-modal-footer .btn {
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid;
        }

        .cropper-modal-footer .btn-secondary {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }

        .cropper-modal-footer .btn-secondary:hover {
            background: #e9ecef;
        }

        .cropper-modal-footer .btn-primary {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .cropper-modal-footer .btn-primary:hover {
            background: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection

@section('admin_content')
    @if ($service->image)
        <form action="{{ route('admin.services.remove-image', $service->id) }}" method="POST" id="removeImageForm"
            style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data"
        id="serviceForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                {{-- Basic Information --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i> Service Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="required-field">Service Title</label>
                            <input type="text" name="title" value="{{ old('title', $service->title) }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror" id="serviceTitle"
                                placeholder="Enter service title" autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">This will be displayed as the main heading for the service.</div>
                        </div>

                        <div class="form-group">
                            <label class="required-field">Service Type</label>
                            <input type="text" name="type" value="{{ old('type', $service->type) }}"
                                class="form-control form-control-lg @error('type') is-invalid @enderror" id="serviceType"
                                placeholder="Enter service type" autofocus>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">This will be displayed as the main heading for the service.</div>
                        </div>

                        <div class="form-group">
                            <label>Slug / URL</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/services/</span>
                                </div>
                                <input type="text" name="slug" value="{{ old('slug', $service->slug ?? '') }}"
                                    class="form-control @error('slug') is-invalid @enderror" id="serviceSlug"
                                    placeholder="auto-generated-from-title">
                            </div>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Leave empty to auto-generate from title. Use only lowercase letters,
                                numbers, and hyphens.</div>
                        </div>

                        <div class="form-group">
                            <label>Short Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Brief description of the service...">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">A short summary (max 500 characters) displayed in service listings.</div>
                        </div>

                        <div class="form-group">
                            <label>Full Content</label>
                            <div id="quill-editor"></div>
                            <textarea name="content" id="contentHidden" style="display:none;">{{ old('content', $service->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Detailed description of the service. You can format text, add images, and
                                more.</div>
                        </div>
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="card card-success card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-search mr-2"></i> SEO Settings
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}"
                                class="form-control" placeholder="Page title for search engines">
                            <div class="help-text">Leave empty to use service title. Recommended length: 50-60 characters.
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea name="meta_description" rows="3" class="form-control"
                                placeholder="Brief description for search engines">{{ old('meta_description', $service->meta_description) }}</textarea>
                            <div class="help-text">Recommended length: 150-160 characters. This appears in search results.
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                value="{{ old('meta_keywords', $service->meta_keywords) }}" class="form-control"
                                placeholder="service, web development, design, etc.">
                            <div class="help-text">Separate keywords with commas. Not used by most search engines but still
                                helpful.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Publish Settings --}}
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Publish
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="required-field">Status</label>
                            <select name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle"></i> Inactive
                                </option>
                            </select>
                            <div class="help-text">Active services will be displayed on the website.</div>
                        </div>

                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" name="order" value="{{ old('order', $service->order) }}"
                                class="form-control" min="0">
                            <div class="help-text">Lower numbers appear first. Services are ordered by this value.</div>
                        </div>

                        <div class="info-text">
                            <i class="fas fa-clock"></i>
                            <small>
                                Created: {{ $service->created_at->format('M d, Y H:i') }}<br>
                                Last updated: {{ $service->updated_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        @canEdit
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save mr-2"></i> Update Service
                        </button>
                    </div>
                    @endcanEdit
                </div>

                {{-- Icon Selection --}}
                <div class="card card-info card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-icons mr-2"></i> Font Awesome Icon
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="icon-preview" id="iconPreview">
                            <i class="{{ $service->icon ?? 'fas fa-cog' }} fa-3x"></i>
                        </div>
                        <div class="form-group mt-3">
                            <label>Icon Class</label>
                            <input type="text" name="icon"
                                value="{{ old('icon', $service->icon ?? 'fas fa-cog') }}" class="form-control"
                                id="iconInput" placeholder="fas fa-cog">
                            <div class="help-text">
                                <i class="fas fa-info-circle"></i>
                                <a href="https://fontawesome.com/icons" target="_blank" rel="noopener">
                                    Browse Font Awesome Icons
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Featured Image with Cropping & Drag & Drop --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Featured Image
                        </h3>
                    </div>

                    <div class="card-body p-4">

                        {{-- ==========================
             CURRENT IMAGE
        ========================== --}}
                        @if ($service->image)
                            <div class="text-center mb-4" id="currentImageContainer">

                                <img src="{{ asset('storage/' . $service->image) . '?v=' . $service->updated_at->timestamp }}"
                                    id="imagePreview" class="rounded shadow"
                                    style="max-width: 250px; object-fit: contain;">

                                <div class="mt-3">
                                    <button type="button" id="openCropper" class="btn btn-warning btn-sm">
                                        Crop Image
                                    </button>

                                    <button type="button" id="removePreview" class="btn btn-danger btn-sm">
                                        Remove
                                    </button>
                                </div>

                                <small class="text-muted d-block mt-2">
                                    Current Image
                                </small>
                            </div>
                        @else
                            {{-- If no image --}}
                            <div class="text-center mb-3">
                                <img id="imagePreview" class="d-none rounded shadow"
                                    style="max-width: 250px; object-fit: contain;">
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" id="openCropper" class="btn btn-warning btn-sm d-none">
                                    Crop Image
                                </button>

                                <button type="button" id="removePreview" class="btn btn-danger btn-sm d-none">
                                    Remove
                                </button>
                            </div>
                        @endif


                        {{-- ==========================
             UPLOAD ZONE
        ========================== --}}
                        <div id="dropZone"
                            class="upload-zone border border-dashed border-secondary rounded-3 p-5 text-center position-relative">

                            <input type="file" name="image" id="serviceImage" class="d-none"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif">

                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>

                            <h6>
                                Drop image here or
                                <button type="button" id="browseBtn" class="btn btn-link p-0 text-primary">
                                    browse
                                </button>
                            </h6>

                            <small class="text-muted">
                                JPG, PNG, WebP, SVG, GIF • Max 7MB
                            </small>
                        </div>

                        @error('image')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror


                        {{-- ==========================
             CROPPER (INLINE / MODAL STYLE)
        ========================== --}}
                        <div id="cropperContainer" class="d-none mt-4">

                            <img id="cropperImage" class="img-fluid w-100 rounded mb-3"
                                style="max-height: 400px; object-fit: contain;">

                            <div class="text-center mb-2">
                                <button type="button" id="rotateBtn" class="btn btn-sm btn-outline-dark">Rotate</button>
                                <button type="button" id="zoomIn" class="btn btn-sm btn-outline-dark">Zoom +</button>
                                <button type="button" id="zoomOut" class="btn btn-sm btn-outline-dark">Zoom -</button>
                            </div>

                            <div class="text-center">
                                <button type="button" id="cropBtn" class="btn btn-success">Apply Crop</button>
                                <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                            </div>

                        </div>

                    </div>
                </div>
            @endsection
            @section('extra_js')
                <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    $(function() {

                        let cropper = null;
                        let isSubmitting = false;

                        const fileInput = $('#serviceImage');
                        const dropZone = $('#dropZone');

                        // ==========================
                        // QUILL EDITOR
                        // ==========================
                        const quill = new Quill('#quill-editor', {
                            theme: 'snow',
                            placeholder: 'Write your page content here…',
                            modules: {
                                toolbar: [
                                    [{
                                        header: [1, 2, 3, 4, false]
                                    }],
                                    [{
                                        font: []
                                    }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{
                                        color: []
                                    }, {
                                        background: []
                                    }],
                                    [{
                                        script: 'sub'
                                    }, {
                                        script: 'super'
                                    }],
                                    [{
                                        list: 'ordered'
                                    }, {
                                        list: 'bullet'
                                    }],
                                    [{
                                        indent: '-1'
                                    }, {
                                        indent: '+1'
                                    }],
                                    [{
                                        align: []
                                    }],
                                    ['link', 'image', 'video'],
                                    ['blockquote', 'code-block'],
                                    ['clean']
                                ]
                            }
                        });

                        const contentHidden = $('#contentHidden');

                        if (contentHidden.val().trim()) {
                            quill.root.innerHTML = contentHidden.val();
                        }

                        function syncContent() {
                            const html = quill.root.innerHTML;
                            contentHidden.val((html === '<p><br></p>' || html === '') ? '' : html);
                        }

                        quill.on('text-change', syncContent);


                        // ==========================
                        // SLUG GENERATION
                        // ==========================
                        $('#serviceTitle').on('input', function() {
                            let slug = $(this).val().toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-')
                                .replace(/(^-|-$)/g, '');

                            $('#serviceSlug').val(slug);
                        });


                        // ==========================
                        // ICON PREVIEW
                        // ==========================
                        $('#iconInput').on('input', function() {
                            let icon = $(this).val();
                            $('#iconPreview').html(
                                icon ? `<i class="${icon} fa-3x"></i>` :
                                `<i class="fas fa-cog fa-3x text-muted"></i>`
                            );
                        });


                        // ==========================
                        // DRAG & DROP
                        // ==========================
                        dropZone.on('dragover', e => e.preventDefault());

                        dropZone.on('drop', function(e) {
                            e.preventDefault();
                            const file = e.originalEvent.dataTransfer.files[0];
                            if (file) handleImage(file);
                        });

                        fileInput.on('change', function(e) {
                            if (e.target.files[0]) handleImage(e.target.files[0]);
                        });

                        $('#browseBtn').click(() => fileInput.click());


                        // ==========================
                        // HANDLE IMAGE
                        // ==========================
                        function handleImage(file) {

                            if (file.size > 7 * 1024 * 1024) {
                                Swal.fire('Error', 'Max size 7MB', 'error');
                                return;
                            }

                            if (!file.type.startsWith('image/')) {
                                Swal.fire('Error', 'Invalid image file', 'error');
                                return;
                            }

                            const reader = new FileReader();

                            reader.onload = function(e) {

                                updatePreview(e.target.result);

                                if (cropper) {
                                    cropper.destroy();
                                    cropper = null;
                                }
                            };

                            reader.readAsDataURL(file);
                        }


                        // ==========================
                        // UPDATE PREVIEW
                        // ==========================
                        function updatePreview(src) {

                            if ($('#imagePreview').length) {
                                $('#imagePreview').attr('src', src).removeClass('d-none');
                            } else {
                                $('#imageCardBody').prepend(`
                <div class="text-center mb-4" id="currentImageContainer">
                    <img src="${src}" id="imagePreview" class="rounded shadow" style="max-width:250px;">
                    <div class="mt-2">
                        <button type="button" id="openCropper" class="btn btn-warning btn-sm">Crop</button>
                        <button type="button" id="removePreview" class="btn btn-danger btn-sm">Remove</button>
                    </div>
                </div>
            `);
                            }

                            $('#openCropper, #removePreview').removeClass('d-none');
                        }


                        // ==========================
                        // OPEN CROPPER
                        // ==========================
                        $(document).on('click', '#openCropper', function() {

                            const src = $('#imagePreview').attr('src');
                            if (!src) return;

                            $('#cropperContainer').removeClass('d-none');
                            $('#cropperImage').attr('src', src);

                            if (cropper) cropper.destroy();

                            cropper = new Cropper(document.getElementById('cropperImage'), {
                                aspectRatio: 1,
                                viewMode: 1
                            });
                        });


                        // ==========================
                        // CROPPER CONTROLS
                        // ==========================
                        $('#rotateBtn').click(() => cropper?.rotate(90));
                        $('#zoomIn').click(() => cropper?.zoom(0.1));
                        $('#zoomOut').click(() => cropper?.zoom(-0.1));


                        // ==========================
                        // APPLY CROP (CIRCULAR)
                        // ==========================
                        $('#cropBtn').click(function() {

                            if (!cropper) return;

                            const canvas = cropper.getCroppedCanvas({
                                width: 500,
                                height: 500
                            });

                            const circleCanvas = document.createElement('canvas');
                            const size = 500;

                            circleCanvas.width = size;
                            circleCanvas.height = size;

                            const ctx = circleCanvas.getContext('2d');

                            ctx.beginPath();
                            ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
                            ctx.closePath();
                            ctx.clip();

                            ctx.drawImage(canvas, 0, 0, size, size);

                            circleCanvas.toBlob(blob => {

                                const file = new File([blob], "cropped.png", {
                                    type: "image/png"
                                });

                                const dt = new DataTransfer();
                                dt.items.add(file);
                                fileInput[0].files = dt.files;

                                updatePreview(URL.createObjectURL(blob));

                                $('#cropperContainer').addClass('d-none');

                                cropper.destroy();
                                cropper = null;
                            });
                        });


                        // ==========================
                        // CANCEL CROPPER
                        // ==========================
                        $('#cancelBtn').click(function() {
                            $('#cropperContainer').addClass('d-none');

                            if (cropper) {
                                cropper.destroy();
                                cropper = null;
                            }
                        });


                        // ==========================
                        // REMOVE IMAGE
                        // ==========================
                        $(document).on('click', '#removePreview', function() {

                            fileInput.val('');
                            $('#imagePreview').addClass('d-none');

                            $('#openCropper, #removePreview').addClass('d-none');

                            if (cropper) {
                                cropper.destroy();
                                cropper = null;
                            }
                        });


                        // ==========================
                        // FORM SUBMIT
                        // ==========================
                        $('#serviceForm').on('submit', function(e) {

                            if (isSubmitting) return true;

                            e.preventDefault();

                            let title = $('#serviceTitle').val().trim();

                            if (!title) {
                                Swal.fire('Error', 'Title required', 'error');
                                return;
                            }

                            Swal.fire({
                                title: 'Update Service?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Yes'
                            }).then((result) => {

                                if (!result.isConfirmed) return;

                                syncContent();

                                isSubmitting = true;
                                this.submit();
                            });
                        });

                    });
                </script>
            @endsection
