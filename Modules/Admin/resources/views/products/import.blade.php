@extends('admin::layouts.app')
@section('page_title', 'Bulk Product Import')

@section('admin_content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="card card-outline card-primary">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-import mr-2"></i>
                Bulk Product Import
            </h3>
        </div>

        <div class="card-body">

            {{-- STEP 1 --}}

            <h5 class="mb-3">
                <span class="badge badge-info">Step 1</span>
                Upload Product Images
            </h5>

            <form action="{{ route('admin.products.bulk-images.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="form-group">

                    <input type="file" id="bulkImageInput" name="images[]" class="form-control-file" multiple
                        accept="image/jpeg,image/png,image/webp">

                    <small class="text-muted">
                        JPG, PNG, WEBP • Max 4MB
                    </small>

                </div>

                <div id="filePreviewList" class="row mb-3"></div>

                <button class="btn btn-info btn-sm">
                    <i class="fas fa-upload mr-1"></i>
                    Upload Images
                </button>

            </form>

            @if ($existingImages->isNotEmpty())

                <hr>

                <h6 class="mb-3">
                    Uploaded Images
                    <span class="badge badge-secondary">
                        {{ $existingImages->count() }}
                    </span>
                </h6>

                <div class="row">

                    @foreach ($existingImages as $filename)
                        <div class="col-md-2 col-4 text-center mb-3">

                            <img src="{{ Storage::url('imports/temp/' . $filename) }}" class="img-fluid border rounded"
                                style="height:90px;object-fit:cover;">

                            <small class="d-block text-truncate mt-1">
                                {{ $filename }}
                            </small>

                            <form action="{{ route('admin.products.bulk-images.delete') }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="filename" value="{{ $filename }}">

                                <button class="btn btn-xs btn-danger mt-1">

                                    Delete

                                </button>

                            </form>

                        </div>
                    @endforeach

                </div>

            @endif

            <hr class="my-4">

            {{-- STEP 2 --}}

            <h5 class="mb-3">
                <span class="badge badge-primary">
                    Step 2
                </span>

                Import Excel
            </h5>

            <form action="{{ route('admin.products.import.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="form-group">

                    <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls,.csv" required>

                    <small class="text-muted d-block mt-2">

                        Columns:
                        name,
                        subtitle,
                        description,
                        price,
                        category,
                        base,
                        style,
                        served,
                        status,
                        image

                    </small>

                </div>

                <button class="btn btn-primary">

                    <i class="fas fa-file-import mr-1"></i>

                    Import Products

                </button>

            </form>

        </div>

    </div>

@endsection

@section('extra_js')

    <script>
        document.getElementById('bulkImageInput').addEventListener('change', function(e) {

            const preview = document.getElementById('filePreviewList');

            preview.innerHTML = '';

            [...e.target.files].forEach(file => {

                let reader = new FileReader();

                let col = document.createElement('div');

                col.className = 'col-md-2 col-4 text-center mb-3';

                reader.onload = function(ev) {

                    col.innerHTML = `
                <img src="${ev.target.result}"
                     class="img-fluid border rounded"
                     style="height:90px;object-fit:cover;">
                <small class="d-block text-truncate mt-1">
                    ${file.name}
                </small>
            `;

                };

                reader.readAsDataURL(file);

                preview.appendChild(col);

            });

        });
    </script>

@endsection
