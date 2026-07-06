@extends('admin::layouts.app')
@section('page_title', 'Add Category')

@section('page_actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus mr-2"></i> Add New Category
            </h3>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Electronics" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Category description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image"
                                        class="custom-file-input @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/gif,image/svg+xml" id="categoryImage"
                                        onchange="previewImage(this, 'categoryPreview')" />
                                    <label class="custom-file-label" for="categoryImage">
                                        Choose image...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">JPG, PNG, GIF or SVG. Max 2MB.</small>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="categoryPreview" src="#" class="d-none"
                                    style="width:120px;height:120px;object-fit:cover;border-radius:8px;" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-default ml-2">Cancel</a>
            </form>

        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function previewImage(input, previewId) {
            var preview = document.getElementById(previewId);
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
