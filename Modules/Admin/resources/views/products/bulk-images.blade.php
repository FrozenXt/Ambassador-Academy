@extends('admin::layouts.app')
@section('page_title', 'Bulk Image Upload')

@section('page_actions')
    <a href="{{ route('admin.products.import') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Import
    </a>
@endsection

@section('admin_content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-images mr-2"></i> Step 1: Upload Product Images
            </h3>
        </div>
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Upload all product images here first. Then in your Excel sheet, put the exact filename
                (e.g. <code>lamb-kofta.jpg</code>) in the <strong>image</strong> column for each product.
                Once images are uploaded, go to <a href="{{ route('admin.products.import') }}">Bulk Menu Upload</a> to
                import the Excel sheet.
            </div>

            <form action="{{ route('admin.products.bulk-images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Select Images (multiple allowed)</label>
                    <input type="file" name="images[]" id="bulkImageInput" class="form-control-file"
                        accept="image/jpeg,image/png,image/webp" multiple required>
                    <small class="text-muted">JPG, PNG, or WEBP. Max 4MB each. Original filenames are kept.</small>
                    @error('images')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div id="filePreviewList" class="row mt-3"></div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-upload mr-1"></i> Upload Images
                </button>
            </form>

        </div>
    </div>

    @if ($existingImages->isNotEmpty())
        <div class="card card-outline card-secondary mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-folder-open mr-2"></i> Uploaded & Waiting ({{ $existingImages->count() }})
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($existingImages as $filename)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3 text-center">
                            <img src="{{ Storage::url('imports/temp/' . $filename) }}"
                                style="width:100%;height:90px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                            <small class="d-block text-truncate mt-1"
                                title="{{ $filename }}">{{ $filename }}</small>
                            <form action="{{ route('admin.products.bulk-images.delete') }}" method="POST" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="filename" value="{{ $filename }}">
                                <button type="submit" class="btn btn-xs btn-outline-danger"
                                    onclick="return confirm('Remove this image?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection

@section('extra_js')
    <script>
        document.getElementById('bulkImageInput').addEventListener('change', function(e) {
            const list = document.getElementById('filePreviewList');
            list.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3 col-lg-2 mb-2 text-center';

                const reader = new FileReader();
                reader.onload = function(ev) {
                    col.innerHTML = `
                    <img src="${ev.target.result}" style="width:100%;height:80px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                    <small class="d-block text-truncate mt-1">${file.name}</small>
                `;
                };
                reader.readAsDataURL(file);

                list.appendChild(col);
            });
        });
    </script>
@endsection
