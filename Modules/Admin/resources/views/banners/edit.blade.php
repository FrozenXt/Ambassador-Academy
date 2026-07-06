@extends('admin::layouts.app')
@section('page_title', 'Edit Banner')

@section('page_actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i> Edit Banner — {{ $banner->title }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">

                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                                class="form-control @error('title') is-invalid @enderror" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                                class="form-control" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 1 Text</label>
                                    <input type="text" name="button_text"
                                        value="{{ old('button_text', $banner->button_text) }}" class="form-control"
                                        placeholder="e.g. Shop Now" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 1 URL</label>
                                    <input type="text" name="button_url"
                                        value="{{ old('button_url', $banner->button_url) }}" class="form-control"
                                        placeholder="e.g. /products" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 2 Text</label>
                                    <input type="text" name="button_text_2"
                                        value="{{ old('button_text_2', $banner->button_text_2) }}" class="form-control"
                                        placeholder="e.g. Learn More" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button 2 URL</label>
                                    <input type="text" name="button_url_2"
                                        value="{{ old('button_url_2', $banner->button_url_2) }}" class="form-control"
                                        placeholder="e.g. /contact" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">
                            <label>Banner Image</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="img-fluid rounded"
                                    style="width:100%;height:120px;object-fit:cover;" />
                                <small class="text-muted d-block mt-1">Current image</small>
                            </div>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input"
                                        accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="bannerImage"
                                        onchange="previewBanner(this)" />
                                    <label class="custom-file-label" for="bannerImage">
                                        Choose new image...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Leave empty to keep current.</small>
                            <div class="mt-2">
                                <img id="bannerPreviewImg" src="#" class="d-none img-fluid rounded"
                                    style="max-height:120px;width:100%;object-fit:cover;" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $banner->status) == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status', $banner->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="order" value="{{ old('order', $banner->order) }}"
                                class="form-control" min="0" />
                        </div>

                        <button type="submit" class="btn btn-warning btn-block mt-3">
                            <i class="fas fa-save mr-1"></i> Update Banner
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
            var preview = document.getElementById('bannerPreviewImg');
            var label = input.nextElementSibling;
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
