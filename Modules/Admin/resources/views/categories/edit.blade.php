@extends('admin::layouts.app')
@section('page_title', 'Edit Category')

@section('page_actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-default btn-sm">
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

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                                class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Category description...">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label>
                            @if ($category->image)
                                <div class="mb-2 d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:6px;" />
                                    <small class="text-muted ml-2">Current image</small>
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image"
                                        class="custom-file-input @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/gif,image/svg+xml" id="editCategoryImage"
                                        onchange="previewImage(this, 'editCategoryPreview')" />
                                    <label class="custom-file-label" for="editCategoryImage">
                                        Choose new image...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Leave empty to keep current image.</small>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="editCategoryPreview" src="#" class="d-none"
                                    style="width:120px;height:120px;object-fit:cover;border-radius:8px;" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i> Update Category
                </button>
                @canEdit
                <a href="{{ route('admin.categories.index') }}" class="btn btn-default ml-2">Cancel</a>
                @endcanEdit
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
