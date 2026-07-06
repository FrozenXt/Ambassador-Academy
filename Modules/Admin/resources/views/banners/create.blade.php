@extends('admin::layouts.app')
@section('page_title', 'Add Banner')

@section('page_actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus mr-2"></i> Add New Gallery
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">

                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="e.g. Welcome to Our Store" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control"
                                placeholder="e.g. Discover amazing products at great prices" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 1 Text</label>
                                    <input type="text" name="button_text" value="{{ old('button_text') }}"
                                        class="form-control" placeholder="e.g. Shop Now" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 1 URL</label>
                                    <input type="text" name="button_url" value="{{ old('button_url') }}"
                                        class="form-control" placeholder="e.g. /products" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 2 Text</label>
                                    <input type="text" name="button_text_2" value="{{ old('button_text_2') }}"
                                        class="form-control" placeholder="e.g. Learn More" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 2 URL</label>
                                    <input type="text" name="button_url_2" value="{{ old('button_url_2') }}"
                                        class="form-control" placeholder="e.g. /contact" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">
                            <label>Gallery Image <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image"
                                        class="custom-file-input @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="bannerImage"
                                        onchange="previewBanner(this)" />
                                    <label class="custom-file-label" for="bannerImage">
                                        Choose image...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">
                                JPG, PNG, WEBP, GIF or SVG. Recommended: 1920×600px
                            </small>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="bannerPreviewImg" src="#" class="d-none img-fluid rounded"
                                    style="max-height:150px;width:100%;object-fit:cover;" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                min="0" />
                            <small class="text-muted">Lower = shows first</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            <i class="fas fa-save mr-1"></i> Save Gallery
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function previewBanner(input) {
            const preview = document.getElementById('bannerPreviewImg');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
