@extends('admin::layouts.app')
@section('page_title', 'Edit — ' . $page->title)

@section('page_actions')
    @if ($page->status == 'published')
        <a href="{{ route('web.page', $page->slug) }}" class="btn btn-info btn-sm mr-2" target="_blank">
            <i class="fas fa-eye mr-1"></i> Preview
        </a>
    @endif
    <a href="{{ route('admin.pages.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css" rel="stylesheet" />
    <style>
        #quill-editor {
            min-height: 400px;
            font-size: 15px;
            background: #fff;
            color: #212529;
        }

        .ql-container.ql-snow {
            font-size: 15px;
            min-height: 400px;
            border-radius: 0 0 4px 4px;
            border-color: #ced4da;
        }

        .ql-toolbar.ql-snow {
            border-radius: 4px 4px 0 0;
            border-color: #ced4da;
            background: #f8f9fa;
            flex-wrap: wrap;
        }

        .ql-editor {
            min-height: 400px;
            line-height: 1.8;
            font-size: 15px;
            color: #212529;
        }

        .ql-editor.ql-blank::before {
            font-style: italic;
            color: #adb5bd;
        }

        .ql-editor h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: .5rem;
        }

        .ql-editor h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: .5rem;
        }

        .ql-editor h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .ql-editor h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .ql-editor p {
            margin-bottom: .75rem;
        }

        .ql-editor blockquote {
            border-left: 4px solid #4f46e5;
            padding: .5rem 1rem;
            color: #6b7280;
            font-style: italic;
            background: #f9fafb;
            margin: 1rem 0;
            border-radius: 0 4px 4px 0;
        }

        .ql-editor pre.ql-syntax {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }

        .ql-editor img {
            max-width: 100%;
            border-radius: 6px;
            height: auto;
        }

        .ql-editor a {
            color: #4f46e5;
        }

        .ql-editor ul,
        .ql-editor ol {
            padding-left: 1.5rem;
            margin-bottom: .75rem;
        }

        .ql-editor table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
        }

        .ql-editor table td,
        .ql-editor table th {
            border: 1px solid #dee2e6;
            padding: .5rem .75rem;
        }

        .ql-editor table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        #editorStats {
            font-size: 12px;
            color: #6c757d;
            padding: 4px 12px;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 4px 4px;
            background: #f8f9fa;
            display: flex;
            gap: 16px;
        }

        /* ── Featured image drop zone ── */
        #dropZone {
            border: 2px dashed #ced4da;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #fafafa;
        }

        #dropZone.dragover {
            border-color: #007bff;
            background: #e8f4ff;
        }

        #dropZone i {
            font-size: 1.8rem;
            color: #adb5bd;
            display: block;
            margin-bottom: 6px;
        }

        #dropZone p {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
        }

        .char-counter {
            font-size: 11px;
            float: right;
            color: #adb5bd;
        }

        .char-counter.warn {
            color: #e67e22;
        }

        .char-counter.over {
            color: #dc3545;
        }

        .sortable-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sortable-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 6px;
            background: #fff;
            cursor: grab;
            user-select: none;
            font-size: 13px;
        }

        .sortable-item:active {
            cursor: grabbing;
        }

        .sortable-item.sortable-ghost {
            opacity: .4;
            background: #e9ecef;
        }

        .sortable-item .handle {
            color: #adb5bd;
            font-size: 16px;
            cursor: grab;
        }

        #slugPreview {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
            word-break: break-all;
        }

        #slugPreview span {
            color: #007bff;
        }

        #unsavedBadge {
            display: none;
            font-size: 11px;
            vertical-align: middle;
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
            max-width: 1000px;
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

        .resize-controls {
            margin-top: 20px;
            padding: 16px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .resize-controls label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #495057;
        }

        .resize-controls .row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }

        .resize-controls .col-6 {
            flex: 1;
        }

        .resize-controls input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
        }

        .resize-controls input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .resize-controls small {
            display: block;
            margin-top: 8px;
            color: #6c757d;
            font-size: 12px;
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
        }

        .cropper-modal-footer .btn-secondary {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .cropper-modal-footer .btn-secondary:hover {
            background: #e9ecef;
        }

        .cropper-modal-footer .btn-primary {
            background: #007bff;
            border: 1px solid #007bff;
            color: #fff;
        }

        .cropper-modal-footer .btn-primary:hover {
            background: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection

@section('admin_content')
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" id="pageForm"
        novalidate>
        @csrf
        @method('PUT')
        @canEdit
        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data"
            id="pageForm" novalidate>
            @csrf
            @method('PUT')

            <div class="row">

                {{-- ── Main Column ── --}}
                <div class="col-md-8">

                    <div class="card card-outline card-warning mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-edit mr-2"></i> Edit Page
                            </h3>
                            <span class="badge badge-warning" id="unsavedBadge">
                                <i class="fas fa-circle mr-1" style="font-size:8px;"></i> Unsaved changes
                            </span>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Page Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="pageTitle" value="{{ old('title', $page->title) }}"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    placeholder="Enter page title…" autocomplete="off" />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Slug</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-muted">/page/</span>
                                    </div>
                                    <input type="text" name="slug" id="pageSlug"
                                        value="{{ old('slug', $page->slug) }}"
                                        class="form-control @error('slug') is-invalid @enderror" autocomplete="off" />
                                </div>
                                <div id="slugPreview">
                                    Preview: <span id="slugPreviewText">/page/{{ old('slug', $page->slug) }}</span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Content</label>
                                <div id="quill-editor"></div>
                                <div id="editorStats">
                                    <span id="wordCount">0 words</span>
                                    <span id="charCount">0 characters</span>
                                    <span id="readTime">~0 min read</span>
                                </div>
                                <textarea name="content" id="contentHidden" style="display:none;">{{ old('content', $page->content) }}</textarea>
                                @error('content')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card card-outline card-success mb-3">
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
                                <label class="d-flex justify-content-between">
                                    Meta Title
                                    <span class="char-counter" id="metaTitleCounter">0 / 60</span>
                                </label>
                                <input type="text" name="meta_title" id="metaTitle"
                                    value="{{ old('meta_title', $page->meta_title) }}" class="form-control"
                                    placeholder="Page meta title…" maxlength="90" />
                                <small class="text-muted">Recommended: under 60 characters. Leave empty to use page
                                    title.</small>
                            </div>

                            <div class="form-group">
                                <label class="d-flex justify-content-between">
                                    Meta Description
                                    <span class="char-counter" id="metaDescCounter">0 / 160</span>
                                </label>
                                <textarea name="meta_description" id="metaDesc" rows="3" class="form-control"
                                    placeholder="Page meta description…" maxlength="250">{{ old('meta_description', $page->meta_description) }}</textarea>
                                <small class="text-muted">Recommended: under 160 characters.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label>Meta Keywords</label>
                                <input type="text" name="meta_keywords"
                                    value="{{ old('meta_keywords', $page->meta_keywords) }}" class="form-control"
                                    placeholder="keyword1, keyword2, keyword3" />
                                <small class="text-muted">Comma-separated.</small>
                            </div>

                            <div class="mt-3 p-3 border rounded" style="background:#fff;">
                                <small class="text-muted d-block mb-1 font-weight-bold">
                                    <i class="fab fa-google mr-1"></i> Search preview
                                </small>
                                <div style="font-size:18px;color:#1a0dab;line-height:1.3;" id="serpTitle">
                                    {{ $page->meta_title ?: $page->title }}
                                </div>
                                <div style="font-size:13px;color:#006621;">
                                    {{ config('app.url') }}/page/<span id="serpSlug">{{ $page->slug }}</span>
                                </div>
                                <div style="font-size:13px;color:#545454;margin-top:2px;" id="serpDesc">
                                    {{ $page->meta_description ?: 'Meta description will appear here…' }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-md-4">

                    <div class="card card-outline card-warning mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control">
                                    <option value="draft"
                                        {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="published"
                                        {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Layout <span class="text-danger">*</span></label>
                                <select name="layout" class="form-control">
                                    <option value="default"
                                        {{ old('layout', $page->layout) == 'default' ? 'selected' : '' }}>
                                        Default</option>
                                    <option value="full-width"
                                        {{ old('layout', $page->layout) == 'full-width' ? 'selected' : '' }}>Full Width
                                    </option>
                                    <option value="sidebar"
                                        {{ old('layout', $page->layout) == 'sidebar' ? 'selected' : '' }}>
                                        With Sidebar</option>
                                    <option value="landing"
                                        {{ old('layout', $page->layout) == 'landing' ? 'selected' : '' }}>
                                        Landing Page</option>
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label>Menu Order</label>
                                <input type="number" name="order" value="{{ old('order', $page->order) }}"
                                    class="form-control" min="0" />
                                <small class="text-muted">Lower number = higher priority.</small>
                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button type="submit" name="save_action" value="publish" class="btn btn-warning flex-fill">
                                <i class="fas fa-save mr-1"></i> Update
                            </button>
                            <button type="submit" name="save_action" value="draft" class="btn btn-default flex-fill">
                                <i class="fas fa-file mr-1"></i> Save Draft
                            </button>
                        </div>
                    </div>

                    {{-- Featured Image with Cropper --}}
                    <div class="card card-outline card-info mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-image mr-2"></i> Featured Image
                            </h3>
                        </div>
                        <div class="card-body">

                            @if ($page->featured_image)
                                <div id="currentImageWrap" class="mb-3">
                                    <img id="currentFeaturedImage" src="{{ asset('storage/' . $page->featured_image) }}"
                                        class="img-fluid rounded mb-2"
                                        style="max-height:180px;object-fit:cover;width:100%;" />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Current image</small>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" id="cropExistingBtn" class="btn btn-info"
                                                title="Crop Existing Image">
                                                <i class="fas fa-crop-alt"></i> Crop
                                            </button>
                                            <button type="button" id="removeImage" class="btn btn-danger"
                                                title="Remove Image">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-2 mb-2">
                                <small class="text-muted d-block mb-2">Upload new image to replace:</small>
                                <input type="hidden" name="crop_existing" id="cropExisting" value="0" />
                                <input type="hidden" name="cropped_image_data" id="croppedImageData" value="" />
                            @endif

                            <div id="dropZone" class="{{ $page->featured_image ? 'd-none' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p><strong>Drag & drop</strong> or click to upload</p>
                                <p class="mt-1">JPG or PNG · Max 2 MB</p>
                            </div>

                            @if ($page->featured_image)
                                <div id="dropZoneSmall"
                                    style="border:2px dashed #ced4da;border-radius:6px;padding:12px;text-align:center;cursor:pointer;background:#fafafa;">
                                    <i class="fas fa-cloud-upload-alt text-muted mr-1"></i>
                                    <small class="text-muted">Drag & drop or click to replace</small>
                                </div>
                            @endif

                            <input type="file" name="featured_image" id="featuredImage"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml"
                                class="d-none @error('featured_image') is-invalid @enderror" />
                            @error('featured_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            <div id="previewWrap" class="mt-3 d-none">
                                <div class="position-relative"
                                    style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px; background: #f8f9fa;">
                                    <img id="featuredPreview" src="#" class="img-fluid rounded"
                                        style="width: 100%; max-height: 200px; object-fit: contain;" />
                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        <small id="imageFileName" class="text-muted"></small>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" id="cropImageBtn" class="btn btn-info"
                                                title="Crop Image">
                                                <i class="fas fa-crop-alt"></i> Crop
                                            </button>
                                            <button type="button" id="removeNewImage" class="btn btn-danger"
                                                title="Remove Image">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Page Info --}}
                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i> Page Info
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted pl-3" style="width:80px;">Created</td>
                                    <td>{{ $page->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-3">Updated</td>
                                    <td>{{ $page->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @if ($page->published_at)
                                    <tr>
                                        <td class="text-muted pl-3">Published</td>
                                        <td>{{ $page->published_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-muted pl-3">Status</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $page->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($page->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Sortable sections --}}
                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-grip-vertical mr-2"></i> Page Sections
                            </h3>
                            <div class="card-tools">
                                <small class="text-muted">Drag to reorder</small>
                            </div>
                        </div>
                        <div class="card-body pb-1">
                            <ul class="sortable-list" id="sectionList">
                                <li class="sortable-item" data-id="hero">
                                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                                    <i class="fas fa-star text-warning mr-1" style="font-size:13px;"></i>
                                    Hero Section
                                </li>
                                <li class="sortable-item" data-id="about">
                                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                                    <i class="fas fa-info-circle text-info mr-1" style="font-size:13px;"></i>
                                    About Section
                                </li>
                                <li class="sortable-item" data-id="features">
                                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                                    <i class="fas fa-th-large text-primary mr-1" style="font-size:13px;"></i>
                                    Features
                                </li>
                                <li class="sortable-item" data-id="cta">
                                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                                    <i class="fas fa-bullhorn text-success mr-1" style="font-size:13px;"></i>
                                    Call to Action
                                </li>
                                <li class="sortable-item" data-id="footer">
                                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                                    <i class="fas fa-shoe-prints text-secondary mr-1" style="font-size:13px;"></i>
                                    Footer
                                </li>
                            </ul>
                            <input type="hidden" name="section_order" id="sectionOrder" />
                        </div>
                    </div>

                </div>
            </div>
        </form>

        {{-- Cropper Modal --}}
        <div id="cropperModal" class="cropper-modal-overlay">
            <div class="cropper-modal-container">
                <div class="cropper-modal-header">
                    <h5><i class="fas fa-crop-alt mr-2"></i>Crop & Resize Image</h5>
                    <button type="button" class="close" id="closeCropperModal">&times;</button>
                </div>
                <div class="cropper-modal-body">
                    <div class="aspect-buttons">
                        <button type="button" class="aspect-btn active" data-aspect="free">
                            <i class="fas fa-arrows-alt mr-1"></i>Free
                        </button>
                        <button type="button" class="aspect-btn" data-aspect="1/1">
                            <i class="fas fa-square mr-1"></i>1:1
                        </button>
                        <button type="button" class="aspect-btn" data-aspect="4/3">
                            <i class="fas fa-image mr-1"></i>4:3
                        </button>
                        <button type="button" class="aspect-btn" data-aspect="16/9">
                            <i class="fas fa-tv mr-1"></i>16:9
                        </button>
                        <button type="button" class="aspect-btn" data-aspect="3/2">
                            <i class="fas fa-rectangle-ad mr-1"></i>3:2
                        </button>
                    </div>

                    <div class="cropper-image-wrapper">
                        <img id="cropperImage" src="#" alt="Image to crop">
                    </div>

                    <div class="resize-controls">
                        <label><i class="fas fa-expand-alt mr-1"></i>Resize After Crop (Optional)</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" id="resizeWidth" class="form-control"
                                    placeholder="Width in pixels">
                            </div>
                            <div class="col-6">
                                <input type="number" id="resizeHeight" class="form-control"
                                    placeholder="Height in pixels">
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Leave empty to keep original dimensions. Enter one value to maintain aspect ratio.
                        </small>
                    </div>
                </div>
                <div class="cropper-modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelCrop">
                        <i class="fas fa-times mr-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="applyCrop">
                        <i class="fas fa-check mr-1"></i>Apply Crop & Resize
                    </button>
                </div>
            </div>
        </div>
    </form>
    @endcanEdit
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ══════════════════════════════════════════
               1. QUILL EDITOR
            ══════════════════════════════════════════ */
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
                        ['clean'],
                    ],
                    history: {
                        delay: 1000,
                        maxStack: 100,
                        userOnly: true
                    },
                },
            });

            const contentHidden = document.getElementById('contentHidden');

            // Load existing content
            const existing = contentHidden.value.trim();
            if (existing) {
                quill.root.innerHTML = existing;
            }

            function syncContent() {
                const html = quill.root.innerHTML;
                contentHidden.value = (html === '<p><br></p>' || !html) ? '' : html;
            }

            function updateStats() {
                const text = quill.getText().trim();
                const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
                const chars = text.length;
                document.getElementById('wordCount').textContent = words + ' word' + (words !== 1 ? 's' : '');
                document.getElementById('charCount').textContent = chars + ' character' + (chars !== 1 ? 's' : '');
                document.getElementById('readTime').textContent = '~' + Math.max(1, Math.ceil(words / 200)) +
                    ' min read';
            }

            quill.on('text-change', function() {
                syncContent();
                updateStats();
                markUnsaved();
            });

            updateStats();
            document.getElementById('pageForm').addEventListener('submit', syncContent);


            /* ══════════════════════════════════════════
               2. UNSAVED CHANGES INDICATOR
            ══════════════════════════════════════════ */



            /* ══════════════════════════════════════════
               3. SLUG PREVIEW
            ══════════════════════════════════════════ */
            const titleInput = document.getElementById('pageTitle');
            const slugInput = document.getElementById('pageSlug');
            const slugPreviewText = document.getElementById('slugPreviewText');
            const serpSlug = document.getElementById('serpSlug');
            const serpTitle = document.getElementById('serpTitle');

            // slug is NOT locked — always follows title unless manually edited
            let slugManuallyEdited = false;

            function toSlug(str) {
                return str.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            function updateSlugPreview(val) {
                slugPreviewText.textContent = '/page/' + (val || '…');
                serpSlug.textContent = val;
            }

            titleInput.addEventListener('input', function() {
                serpTitle.textContent = this.value || 'Page Title';

                if (!slugManuallyEdited) {
                    if (!this.value.trim()) {
                        // title cleared → clear slug too
                        slugInput.value = '';
                        updateSlugPreview('');
                    } else {
                        const s = toSlug(this.value);
                        slugInput.value = s;
                        updateSlugPreview(s);
                    }
                } else if (!this.value.trim()) {
                    // even if manually edited, clear slug when title is fully cleared
                    slugInput.value = '';
                    slugManuallyEdited = false;
                    updateSlugPreview('');
                }
            });

            slugInput.addEventListener('input', function() {
                // only mark as manually edited if user actually typed something
                slugManuallyEdited = this.value.trim() !== '';
                updateSlugPreview(this.value);
            });

            updateSlugPreview(slugInput.value);
            /* ══════════════════════════════════════════
               4. META CHAR COUNTERS + SERP PREVIEW
            ══════════════════════════════════════════ */
            function makeCounter(inputId, counterId, limit) {
                const el = document.getElementById(inputId);
                const ctr = document.getElementById(counterId);
                if (!el || !ctr) return;

                function update() {
                    const len = el.value.length;
                    ctr.textContent = len + ' / ' + limit;
                    ctr.className = 'char-counter' +
                        (len > limit ? ' over' : len > limit * 0.85 ? ' warn' : '');
                }
                el.addEventListener('input', update);
                update();
            }
            makeCounter('metaTitle', 'metaTitleCounter', 60);
            makeCounter('metaDesc', 'metaDescCounter', 160);

            document.getElementById('metaTitle').addEventListener('input', function() {
                serpTitle.textContent = this.value || titleInput.value || 'Page Title';
            });
            document.getElementById('metaDesc').addEventListener('input', function() {
                document.getElementById('serpDesc').textContent =
                    this.value || 'Meta description will appear here…';
            });

            /* ══════════════════════════════════════════
               5. FEATURED IMAGE — CROPPER & RESIZER
            ══════════════════════════════════════════ */
            /* ══════════════════════════════════════════
           5. FEATURED IMAGE — CROPPER & RESIZER (with existing image support)
        ══════════════════════════════════════════ */
            const fileInput = document.getElementById('featuredImage');
            const previewWrap = document.getElementById('previewWrap');
            const preview = document.getElementById('featuredPreview');
            const removeBtn = document.getElementById('removeNewImage');
            const cropBtn = document.getElementById('cropImageBtn');
            const cropExistingBtn = document.getElementById('cropExistingBtn');
            const fileNameEl = document.getElementById('imageFileName');
            const dropZone = document.getElementById('dropZone');
            const dropZoneSmall = document.getElementById('dropZoneSmall');
            const currentImageWrap = document.getElementById('currentImageWrap');
            const currentFeaturedImage = document.getElementById('currentFeaturedImage');
            const cropExistingInput = document.getElementById('cropExisting');
            const croppedImageData = document.getElementById('croppedImageData');

            // Cropper elements
            const cropperModal = document.getElementById('cropperModal');
            const cropperImage = document.getElementById('cropperImage');
            const closeCropperModal = document.getElementById('closeCropperModal');
            const cancelCrop = document.getElementById('cancelCrop');
            const applyCrop = document.getElementById('applyCrop');
            const resizeWidth = document.getElementById('resizeWidth');
            const resizeHeight = document.getElementById('resizeHeight');

            let cropper = null;
            let currentImageFile = null;
            let isCroppingExisting = false;

            function bindDropZone(zone) {
                if (!zone) return;
                zone.addEventListener('click', () => fileInput.click());
                zone.addEventListener('dragover', e => {
                    e.preventDefault();
                    zone.classList.add('dragover');
                });
                zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
                zone.addEventListener('drop', e => {
                    e.preventDefault();
                    zone.classList.remove('dragover');
                    const file = e.dataTransfer.files[0];
                    if (file) injectAndPreview(file);
                });
            }

            bindDropZone(dropZone);
            bindDropZone(dropZoneSmall);

            function injectAndPreview(file) {
                if (!file.type.startsWith('image/')) return;
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                showPreview(file);

                // Reset cropping flags
                cropExistingInput.value = '0';
                croppedImageData.value = '';
                isCroppingExisting = false;
            }

            function showPreview(file) {
                if (!file || !file.type.startsWith('image/')) return;
                currentImageFile = file;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewWrap.classList.remove('d-none');
                    fileNameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';

                    // Hide current image display if exists
                    if (currentImageWrap) {
                        currentImageWrap.style.display = 'none';
                    }
                    if (dropZone) dropZone.classList.add('d-none');
                    if (dropZoneSmall) dropZoneSmall.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }

            function openCropper(imageUrl, filename, isExisting = false) {
                cropperImage.src = imageUrl;
                cropperModal.classList.add('active');
                isCroppingExisting = isExisting;

                setTimeout(() => {
                    if (cropper) cropper.destroy();

                    cropper = new Cropper(cropperImage, {
                        viewMode: 2,
                        dragMode: 'move',
                        aspectRatio: NaN,
                        autoCropArea: 0.8,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        background: true,
                        responsive: true,
                        checkCrossOrigin: true,
                        checkOrientation: false,
                        modal: true,
                        zoomable: true,
                        zoomOnTouch: true,
                        zoomOnWheel: true,
                        wheelZoomRatio: 0.1,
                        rotatable: false,
                        scalable: false,
                    });
                }, 100);

                resizeWidth.value = '';
                resizeHeight.value = '';

                // Store filename for existing image cropping
                if (isExisting) {
                    currentImageFile = {
                        name: filename,
                        isExisting: true,
                        url: imageUrl
                    };
                }
            }

            // Crop existing image button click
            if (cropExistingBtn) {
                cropExistingBtn.addEventListener('click', () => {
                    if (currentFeaturedImage && currentFeaturedImage.src) {
                        // Get filename from current image
                        const imageUrl = currentFeaturedImage.src;
                        const filename =
                            "{{ $page->featured_image ? basename($page->featured_image) : 'image.jpg' }}";
                        openCropper(imageUrl, filename, true);
                    }
                });
            }

            // Crop new image button click
            if (cropBtn) {
                cropBtn.addEventListener('click', () => {
                    if (currentImageFile && !currentImageFile.isExisting) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            openCropper(e.target.result, currentImageFile.name, false);
                        };
                        reader.readAsDataURL(currentImageFile);
                    } else if (currentImageFile && currentImageFile.isExisting) {
                        openCropper(currentImageFile.url, currentImageFile.name, true);
                    }
                });
            }

            // Aspect ratio buttons
            document.querySelectorAll('.aspect-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!cropper) return;
                    const aspect = this.dataset.aspect;
                    if (aspect === 'free') {
                        cropper.setAspectRatio(NaN);
                    } else {
                        const [w, h] = aspect.split('/').map(Number);
                        cropper.setAspectRatio(w / h);
                    }
                    document.querySelectorAll('.aspect-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                });
            });

            // Apply crop and resize
            applyCrop.addEventListener('click', () => {
                if (!cropper) return;

                let croppedCanvas = cropper.getCroppedCanvas();
                if (!croppedCanvas) return;

                const targetWidth = resizeWidth.value ? parseInt(resizeWidth.value) : null;
                const targetHeight = resizeHeight.value ? parseInt(resizeHeight.value) : null;

                if ((targetWidth && targetWidth > 0) || (targetHeight && targetHeight > 0)) {
                    let width = croppedCanvas.width;
                    let height = croppedCanvas.height;

                    if (targetWidth && targetHeight) {
                        width = targetWidth;
                        height = targetHeight;
                    } else if (targetWidth) {
                        const ratio = targetWidth / width;
                        width = targetWidth;
                        height = height * ratio;
                    } else if (targetHeight) {
                        const ratio = targetHeight / height;
                        width = width * ratio;
                        height = targetHeight;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(croppedCanvas, 0, 0, width, height);
                    croppedCanvas = canvas;
                }

                if (isCroppingExisting) {
                    // For existing image, convert canvas to blob and create a file
                    croppedCanvas.toBlob((blob) => {
                        const newFile = new File([blob], currentImageFile.name, {
                            type: blob.type,
                            lastModified: Date.now(),
                        });

                        // Create a FormData to send the cropped image
                        const formData = new FormData();
                        formData.append('cropped_image', newFile);
                        formData.append('_token', document.querySelector('input[name="_token"]')
                            .value);
                        formData.append('_method', 'PUT');

                        // Show loading state
                        applyCrop.disabled = true;
                        applyCrop.innerHTML =
                            '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

                        // Send to server to replace existing image
                        fetch('{{ route('admin.pages.update', $page->id) }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the displayed image
                                    currentFeaturedImage.src = data.image_url + '?t=' + Date
                                        .now();

                                    // Show success message
                                    const alert = document.createElement('div');
                                    alert.className =
                                        'alert alert-success alert-dismissible fade show mt-2';
                                    alert.innerHTML =
                                        'Image cropped successfully! <button type="button" class="close" data-dismiss="alert">&times;</button>';
                                    document.querySelector('.card-body').insertBefore(alert,
                                        document.querySelector('.card-body').firstChild);

                                    // Hide preview wrap if visible
                                    previewWrap.classList.add('d-none');

                                    // Clear file input
                                    fileInput.value = '';
                                    currentImageFile = null;

                                    setTimeout(() => alert.remove(), 3000);
                                } else {
                                    throw new Error(data.message || 'Failed to crop image');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                const alert = document.createElement('div');
                                alert.className =
                                    'alert alert-danger alert-dismissible fade show mt-2';
                                alert.innerHTML =
                                    'Failed to crop image. Please try again. <button type="button" class="close" data-dismiss="alert">&times;</button>';
                                document.querySelector('.card-body').insertBefore(alert,
                                    document.querySelector('.card-body').firstChild);
                                setTimeout(() => alert.remove(), 3000);
                            })
                            .finally(() => {
                                applyCrop.disabled = false;
                                applyCrop.innerHTML =
                                    '<i class="fas fa-check mr-1"></i>Apply Crop & Resize';
                                closeCropper();
                            });
                    }, croppedCanvas.toDataURL().split(';')[0].split(':')[1]);
                } else {
                    // For new image, just update the preview
                    croppedCanvas.toBlob((blob) => {
                        const newFile = new File([blob], currentImageFile.name, {
                            type: blob.type,
                            lastModified: Date.now(),
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(newFile);
                        fileInput.files = dataTransfer.files;

                        const reader = new FileReader();
                        reader.onload = e => {
                            preview.src = e.target.result;
                            currentImageFile = newFile;
                            fileNameEl.textContent = newFile.name + ' (' + (newFile.size / 1024)
                                .toFixed(0) + ' KB)';
                        };
                        reader.readAsDataURL(newFile);

                        closeCropper();
                    }, croppedCanvas.toDataURL().split(';')[0].split(':')[1]);
                }
            });

            // Close cropper
            function closeCropper() {
                cropperModal.classList.remove('active');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                isCroppingExisting = false;
            }

            closeCropperModal.addEventListener('click', closeCropper);
            cancelCrop.addEventListener('click', closeCropper);

            // Handle file input change
            fileInput.addEventListener('change', () => {
                if (fileInput.files[0]) {
                    showPreview(fileInput.files[0]);
                    cropExistingInput.value = '0';
                    croppedImageData.value = '';
                    isCroppingExisting = false;
                }
            });

            // Remove new image button
            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    preview.src = '#';
                    previewWrap.classList.add('d-none');
                    currentImageFile = null;
                    fileNameEl.textContent = '';
                    cropExistingInput.value = '0';
                    croppedImageData.value = '';
                    isCroppingExisting = false;

                    // Show current image again if exists
                    if (currentImageWrap) {
                        currentImageWrap.style.display = 'block';
                    }
                    if (dropZone) dropZone.classList.remove('d-none');
                    if (dropZoneSmall) dropZoneSmall.style.display = 'block';
                });
            }

            // Remove image
            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                preview.src = '#';
                previewWrap.classList.add('d-none');
                dropZone.classList.remove('d-none');
                currentImageFile = null;
                fileNameEl.textContent = '';
            });


            /* ══════════════════════════════════════════
               6. SORTABLE SECTIONS
            ══════════════════════════════════════════ */
            const sectionList = document.getElementById('sectionList');
            const sectionOrder = document.getElementById('sectionOrder');

            function saveSectionOrder() {
                sectionOrder.value = JSON.stringify(
                    Array.from(sectionList.querySelectorAll('.sortable-item')).map(el => el.dataset.id)
                );
            }

            Sortable.create(sectionList, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: saveSectionOrder,
            });

            saveSectionOrder();

        });
    </script>
@endsection
