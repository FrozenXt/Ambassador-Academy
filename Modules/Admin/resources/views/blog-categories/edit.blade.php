@extends('admin::layouts.app')
@section('page_title', 'Edit Blog Category')

@section('page_actions')
    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i> Edit — {{ $category->name }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.blog-categories.update', $category->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                                class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                                class="form-control @error('slug') is-invalid @enderror" />
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" rows="2" class="form-control">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Image</label>
                            @if ($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $category->image) }}" class="img-fluid rounded"
                                        style="max-height:60px;" />
                                    <small class="text-muted d-block">Current image</small>
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input"
                                        accept="image/jpeg,image/png,image/webp" id="imageInput"
                                        onchange="previewImg(this)" />
                                    <label class="custom-file-label" for="imageInput">
                                        Change image...
                                    </label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <img id="imgPreview" src="#" class="d-none img-fluid rounded"
                                    style="max-height:60px;" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"
                                    {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Order</label>
                            <input type="number" name="order" value="{{ old('order', $category->order) }}"
                                class="form-control" min="0" />
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i> Update Category
                </button>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-default ml-2">Cancel</a>
            </form>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function previewImg(input) {
            var preview = document.getElementById('imgPreview');
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
