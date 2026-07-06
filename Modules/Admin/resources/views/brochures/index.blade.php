@extends('admin::layouts.app')

@section('page_title', 'Brochure Management')

@section('page_actions')
    <button type="submit" form="brochureForm" class="btn btn-primary btn-sm">
        <i class="fas fa-save mr-1"></i> Save Changes
    </button>
@endsection

@section('admin_content')

    <div class="row">
        <div class="col-lg-8">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Company Brochure
                    </h3>
                </div>

                <form id="brochureForm" action="{{ route('admin.brochures.update') }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            Upload the latest brochure, the previous will be replaced with this.
                        </div>

                        <div class="form-group">
                            <label>Brochure File <span class="text-danger">*</span></label>

                            <div class="custom-file">
                                <input type="file" name="brochure" id="brochureFile"
                                    class="custom-file-input @error('brochure') is-invalid @enderror" accept=".pdf">

                                <label class="custom-file-label" for="brochureFile">
                                    Choose PDF file...
                                </label>
                            </div>

                            @error('brochure')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Allowed format: PDF | Maximum size: 10MB
                            </small>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i>
                            Upload Brochure
                        </button>
                    </div>

                </form>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        Current Brochure
                    </h3>
                </div>

                <div class="card-body text-center">

                    @if ($brochure)
                        <div class="mb-3">
                            <i class="fas fa-file-pdf text-danger" style="font-size:80px;"></i>
                        </div>

                        <h6 class="font-weight-bold">
                            {{ $brochure->file_name }}
                        </h6>

                        <span class="badge badge-success">
                            Active
                        </span>

                        <hr>

                        <div class="d-flex justify-content-center">

                            <a href="{{ Storage::url($brochure->file_path) }}" target="_blank"
                                class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-eye mr-1"></i>
                                Preview
                            </a>

                            <a href="{{ Storage::url($brochure->file_path) }}" download class="btn btn-success btn-sm">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>

                        </div>
                    @else
                        <div class="py-4">
                            <i class="fas fa-file-upload text-muted" style="font-size:70px;"></i>

                            <p class="text-muted mt-3 mb-0">
                                No brochure uploaded yet.
                            </p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif
        });
    </script>
@endsection
