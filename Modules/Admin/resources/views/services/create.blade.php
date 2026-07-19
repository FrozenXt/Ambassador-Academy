@extends('admin::layouts.app')
@section('page_title', 'Add Service')

@section('page_actions')
    <a href="{{ route('admin.services.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/iconify@1.0.7/dist/iconify.min.css" rel="stylesheet">

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

        .image-preview,
        #imagePreview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
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

        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }

        .upload-zone {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-zone:hover {
            border-color: #4f46e5 !important;
            background: rgba(79, 70, 229, .05) !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .cropper-actions {
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .preview-zone {
            transition: all 0.3s ease;
        }

        .preview-zone:hover {
            transform: scale(1.02);
        }

        #removePreview:hover {
            background: #dc3545 !important;
            color: white !important;
        }
    </style>
@endsection

@section('admin_content')
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
        @csrf
        @canCreate
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
            @csrf

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
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    id="serviceTitle" placeholder="Enter service title" autofocus>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="help-text">This will be displayed as the main heading for the service.</div>
                            </div>

                            <div class="form-group">
                                <label class="required-field">Service Type</label>
                                <select class="form-control form-control-lg @error('type') is-invalid @enderror"
                                    name="type" id="serviceType">
                                    <option value="">— Select Type —</option>
                                    @foreach (\Modules\Common\Entities\Service::SERVICE_TYPES as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="help-text">Determines which page section this service card appears in.</div>
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
                                    placeholder="Brief description of the service...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="help-text">A short summary (max 500 characters) displayed in service listings.
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Full Content</label>
                                <div id="quill-editor"></div>
                                <textarea name="content" id="contentHidden" style="display:none;">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="help-text">Detailed description of the service. You can format text, add images,
                                    and
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
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                    class="form-control" placeholder="Page title for search engines">
                                <div class="help-text">Leave empty to use service title. Recommended length: 50-60
                                    characters.
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control"
                                    placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                                <div class="help-text">Recommended length: 150-160 characters. This appears in search
                                    results.
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                    class="form-control" placeholder="service, web development, design, etc.">
                                <div class="help-text">Separate keywords with commas. Not used by most search engines but
                                    still
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
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        <i class="fas fa-check-circle"></i> Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </option>
                                </select>
                                <div class="help-text">Active services will be displayed on the website.</div>
                            </div>

                            <div class="form-group">
                                <label>Display Order</label>
                                <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                    min="0">
                                <div class="help-text">Lower numbers appear first. Services are ordered by this value.
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-save mr-2"></i> Create Service
                            </button>
                        </div>
                    </div>

                    {{-- Icon Selection --}}
                    <div class="card card-info card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-icons mr-2"></i> Font Awesome Icon or Iconify Icon
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="icon-preview" id="iconPreview">
                                <i class="fas fa-cog fa-3x text-muted"></i>
                            </div>
                            <div class="form-group mt-3">
                                <label>Icon Class</label>
                                <input type="text" name="icon" value="{{ old('icon', 'fas fa-cog') }}"
                                    class="form-control" id="iconInput" placeholder="fas fa-cog">
                                <div class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    <a href="https://fontawesome.com/icons" target="_blank" rel="noopener">
                                        Browse Font Awesome Icons
                                    </a>
                                    <br>
                                    <i class="fas fa-info-circle"></i>
                                    <a href="https://icon-sets.iconify.design/" target="_blank" rel="noopener">
                                        Browse Iconify Icons
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="card-body p-4">

                        <!-- Upload Zone -->
                        <div id="dropZone"
                            class="upload-zone border border-dashed border-secondary rounded-3 p-5 text-center mb-4 position-relative">

                            <input type="file" name="image" id="serviceImage" class="d-none"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif">

                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>

                            <h6>
                                Drop image here or
                                <button type="button" id="browseBtn"
                                    class="btn btn-link p-0 text-primary">browse</button>
                            </h6>

                            <small class="text-muted">
                                JPG, PNG, WebP, SVG, GIF, AVIF • Max 7MB
                            </small>
                        </div>

                        <!-- Preview -->
                        <div class="text-center mb-3">
                            <img id="imagePreview" class="d-none rounded shadow"
                                style="max-width: 250px; object-fit: contain;">
                        </div>

                        <!-- Actions -->
                        <div class="text-center mb-4">
                            <button type="button" id="openCropper" class="btn btn-warning d-none">Crop Image</button>
                            <button type="button" id="removePreview" class="btn btn-danger d-none">Remove</button>
                        </div>

                        <!-- Cropper -->
                        <div id="cropperContainer" class="d-none">

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
            </div>
        </form>
        @endcanCreate
    </form>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        $(function() {

            let cropper = null;
            let isSubmitting = false;

            const fileInput = $('#serviceImage');
            const dropZone = $('#dropZone');
            const cropContainer = $('#cropperContainer');

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
                        }, {
                            list: 'check'
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
                let html = quill.root.innerHTML;
                contentHidden.val(html === '<p><br></p>' ? '' : html);
            }

            quill.on('text-change', syncContent);

            // ==========================
            // ICON PREVIEW
            // ==========================
            $('#iconInput').on('input', function() {
                const icon = $(this).val();
                $('#iconPreview').html(
                    icon ?
                    `<i class="${icon} fa-3x"></i>` :
                    `<i class="fas fa-cog fa-3x text-muted"></i>`
                );
            });

            // ==========================
            // BROWSE BUTTON
            // ==========================
            $('#browseBtn').click(() => fileInput.click());

            // ==========================
            // DRAG & DROP
            // ==========================
            dropZone.on('dragover dragenter', function(e) {
                e.preventDefault();
                $(this).addClass('border-primary bg-primary bg-opacity-10');
            });

            dropZone.on('dragleave dragend drop', function(e) {
                e.preventDefault();
                $(this).removeClass('border-primary bg-primary bg-opacity-10');
            });

            dropZone.on('drop', function(e) {
                const file = e.originalEvent.dataTransfer.files[0];
                if (file) handleImage(file);
            });

            fileInput.on('change', function(e) {
                if (e.target.files[0]) handleImage(e.target.files[0]);
            });

            // ==========================
            // HANDLE IMAGE (DEFAULT UPLOAD)
            // ==========================
            function handleImage(file) {

                if (file.size > 7 * 1024 * 1024) {
                    Swal.fire('Error', 'Image too large (max 7MB)', 'error');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {

                    $('#imagePreview')
                        .attr('src', e.target.result)
                        .removeClass('d-none');

                    $('#openCropper, #removePreview').removeClass('d-none');

                    // destroy old cropper
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                };

                reader.readAsDataURL(file);
            }

            // ==========================
            // OPEN CROPPER (OPTIONAL)
            // ==========================
            $('#openCropper').click(function() {

                const src = $('#imagePreview').attr('src');
                if (!src) return;

                cropContainer.removeClass('d-none');
                $('#cropperImage').attr('src', src);

                if (cropper) cropper.destroy();

                cropper = new Cropper(document.getElementById('cropperImage'), {
                    aspectRatio: NaN,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8
                });

                $('#cropActions').removeClass('d-none');
            });

            // ==========================
            // CROPPER CONTROLS
            // ==========================
            $('#rotateBtn').click(() => cropper?.rotate(90));
            $('#zoomIn').click(() => cropper?.zoom(0.1));
            $('#zoomOut').click(() => cropper?.zoom(-0.1));

            // ==========================
            // APPLY CROP
            // ==========================
            $('#cropBtn').click(function() {

                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas();

                canvas.toBlob(blob => {

                    const file = new File([blob], "cropped.png", {
                        type: "image/png"
                    });

                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput[0].files = dt.files;

                    $('#imagePreview').attr('src', URL.createObjectURL(blob));

                    cropContainer.addClass('d-none');

                    cropper.destroy();
                    cropper = null;
                });
            });

            // ==========================
            // CANCEL CROPPING
            // ==========================
            $('#cancelBtn').click(function() {
                cropContainer.addClass('d-none');

                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // ==========================
            // REMOVE IMAGE
            // ==========================
            $('#removePreview').click(function() {

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

                const title = $('#serviceTitle').val().trim();

                if (!title) {
                    Swal.fire('Validation Error', 'Please enter a service title.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Create Service?',
                    text: 'Are you sure?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, create it!'
                }).then(result => {

                    if (!result.isConfirmed) return;

                    syncContent();

                    isSubmitting = true;
                    this.submit();
                });
            });

            // ==========================
            // AUTO SLUG
            // ==========================
            $('#serviceTitle').on('input', function() {
                const slug = $(this).val().toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');

                $('#serviceSlug').val(slug);
            });

        });
    </script>
@endsection
