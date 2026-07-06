@extends('admin::layouts.app')

@section('page_title', 'Edit Pricing Plan')

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pricing Plan: {{ $pricing->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pricings.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        @canEdit
        <form action="{{ route('admin.pricings.update', $pricing->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $pricing->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Short Description</label>
                            <input type="text" name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description', $pricing->short_description) }}">
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price <span class="text-danger">*</span></label>
                            <input type="number" step="1" name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $pricing->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="currency"
                                class="form-control @error('currency') is-invalid @enderror"
                                value="{{ old('currency', $pricing->currency) }}" required>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Period <span class="text-danger">*</span></label>
                            <input type="text" name="period" class="form-control @error('period') is-invalid @enderror"
                                value="{{ old('period', $pricing->period) }}" required>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Button Text <span class="text-danger">*</span></label>
                            <input type="text" name="button_text"
                                class="form-control @error('button_text') is-invalid @enderror"
                                value="{{ old('button_text', $pricing->button_text) }}" required>
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Button URL</label>
                            <input type="text" name="button_url"
                                class="form-control @error('button_url') is-invalid @enderror"
                                value="{{ old('button_url', $pricing->button_url) }}">
                            @error('button_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Features List</label>
                            <div id="features-wrapper">
                                @php
                                    $features = old('features', $pricing->features_list);
                                    if (empty($features)) {
                                        $features = [''];
                                    }
                                @endphp
                                @foreach ($features as $feature)
                                    <div class="input-group mb-2 feature-item">
                                        <input type="text" name="features[]" class="form-control"
                                            value="{{ $feature }}" placeholder="Enter feature">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger remove-feature" type="button"
                                                onclick="this.closest('.feature-item').remove()">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                            <small class="text-muted d-block mt-1">Click "Add Feature" to add more features.</small>
                        </div>
                    </div>
                </div>

                <script>
                    (function() {
                        var btn = document.getElementById('add-feature');
                        if (btn) {
                            btn.addEventListener('click', function() {
                                var wrapper = document.getElementById('features-wrapper');
                                var first = wrapper.querySelector('.feature-item');
                                var clone = first.cloneNode(true);
                                clone.querySelector('input').value = '';
                                clone.querySelector('button').setAttribute('onclick',
                                    "this.closest('.feature-item').remove()");
                                wrapper.appendChild(clone);
                                clone.querySelector('input').focus();
                            });
                        }
                    })();
                </script>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', $pricing->sort_order) }}">
                            <small class="text-muted">Lower numbers appear first</small>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="is_popular" name="is_popular"
                                    value="1" {{ old('is_popular', $pricing->is_popular) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_popular">Mark as Popular Plan</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $pricing->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active Plan</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Plan
                </button>
                <a href="{{ route('admin.pricings.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        @endcanEdit
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Add feature
                $('#add-feature').click(function() {
                    let html = `
            <div class="input-group mb-2 feature-item">
                <input type="text" name="features[]" class="form-control" placeholder="Enter feature">
                <div class="input-group-append">
                    <button class="btn btn-danger remove-feature" type="button">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
                    $('#features-wrapper').append(html);
                });

                // Remove feature
                $(document).on('click', '.remove-feature', function() {
                    $(this).closest('.feature-item').remove();
                });
            });
        </script>
    @endpush
@endsection
