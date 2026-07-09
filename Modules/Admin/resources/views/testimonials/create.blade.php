@extends('admin::layouts.app')
@section('page_title', 'Add Testimonial')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus mr-2"></i> Add New Testimonial
            </h3>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    {{-- Left --}}
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. John Cena" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position / Role</label>
                                    <input type="text" name="position" value="{{ old('position') }}" class="form-control"
                                        placeholder="e.g. CEO, Manager" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}" class="form-control"
                                        placeholder="e.g. jorpati, Kathmandu" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" name="company" value="{{ old('company') }}" class="form-control"
                                        placeholder="e.g. Acme Corp" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rating <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <select name="rating" id="ratingSelect"
                                            class="form-control @error('rating') is-invalid @enderror"
                                            onchange="updateStars(this.value)">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}"
                                                    {{ old('rating', 5) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                        <div id="starPreview" class="ml-2">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    @error('rating')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Testimonial Content <span class="text-danger">*</span></label>
                                    <textarea name="content" rows="5" class="form-control @error('content') is-invalid @enderror"
                                        placeholder="What did they say about your service...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Avatar / Photo</label>
                            @if (old('avatar'))
                                <div class="mb-2 text-center">
                                    <img id="avatarPreview" src="#" class="img-circle elevation-2"
                                        style="width:80px;height:80px;object-fit:cover;" />
                                </div>
                            @else
                                <div class="text-center mb-2">
                                    <div id="avatarPlaceholder"
                                        class="img-circle bg-gradient-primary text-white
                                            d-flex align-items-center justify-content-center
                                            font-weight-bold mx-auto"
                                        style="width:80px;height:80px;font-size:1.5rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <img id="avatarPreview" src="#"
                                        class="img-circle elevation-2 d-none mx-auto d-block"
                                        style="width:80px;height:80px;object-fit:cover;" />
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar"
                                        class="custom-file-input @error('avatar') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif" id="avatarInput"
                                        onchange="previewAvatar(this)" />
                                    <label class="custom-file-label" for="avatarInput">
                                        Choose photo...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Optional. JPG, PNG, WebP, SVG, GIF. Max 2MB.</small>
                            @error('avatar')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
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
                            <label>Display Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                min="0" />
                            <small class="text-muted">Lower = appears first</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="is_featured" value="0">
                                <!-- ensures false is sent when unchecked -->
                                <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured"
                                    value="1" {{ old('is_featured') == '1' ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">
                                    Featured on Homepage
                                </label>
                            </div>
                            <small class="text-muted">Show in homepage testimonials section.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            <i class="fas fa-save mr-1"></i> Save Testimonial
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('avatarPreview');
                    var placeholder = document.getElementById('avatarPlaceholder');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (placeholder) placeholder.style.display = 'none';
                    input.nextElementSibling.textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateStars(rating) {
            var preview = document.getElementById('starPreview');
            var html = '';
            for (var i = 1; i <= 5; i++) {
                html += i <= rating ?
                    '<i class="fas fa-star text-warning"></i>' :
                    '<i class="far fa-star text-muted"></i>';
            }
            preview.innerHTML = html;
        }
    </script>
@endsection
