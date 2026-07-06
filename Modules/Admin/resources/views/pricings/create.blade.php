@extends('admin::layouts.app')

@section('page_title', 'Create Pricing Plan')

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Pricing Plan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pricings.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <form action="{{ route('admin.pricings.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g., Basic, Business, Advance" required>
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
                                value="{{ old('short_description') }}"
                                placeholder="Essential tools to manage and store your documents securely">
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
                                class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}"
                                required>
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
                                value="{{ old('currency', 'Rs.') }}" placeholder="Rs., $, €" required>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Period <span class="text-danger">*</span></label>
                            <input type="text" name="period" class="form-control @error('period') is-invalid @enderror"
                                value="{{ old('period', '/ year') }}" placeholder="/ year, / month" required>
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
                                value="{{ old('button_text', 'Get Started') }}" required>
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
                                value="{{ old('button_url', '#') }}" placeholder="/checkout or #">
                            @error('button_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Features List --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Features List</label>
                            <div id="featuresWrapper">
                                <div class="input-group mb-2 feature-item">
                                    <input type="text" name="features[]" class="form-control"
                                        placeholder="e.g. Secure Document Storage">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger"
                                            onclick="this.closest('.feature-item').remove()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-success mt-2" id="addFeatureBtn">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                            <small class="text-muted d-block mt-1">Click "Add Feature" to add more features.</small>
                        </div>
                    </div>
                </div>

                <script>
                    (function() {
                        var btn = document.getElementById('addFeatureBtn');
                        if (btn) {
                            btn.addEventListener('click', function() {
                                var wrapper = document.getElementById('featuresWrapper');
                                var first = wrapper.querySelector('.feature-item');
                                var clone = first.cloneNode(true);
                                clone.querySelector('input').value = '';
                                wrapper.appendChild(clone);
                                clone.querySelector('input').focus();
                            });
                        }
                    })
                    ();
                </script>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', 0) }}">
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
                                    value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_popular">
                                    Mark as Popular Plan
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Active Plan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end card-body --}}

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Plan
                </button>
                <a href="{{ route('admin.pricings.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>

    </div>
@endsection


@push('scripts')
    <script>
        document.getElementById('add-feature-btn').addEventListener('click', function() {
            const first = document.querySelector('.feature-item');
            const clone = first.cloneNode(true);

            // Clear the cloned input value
            clone.querySelectorAll('input').forEach(el => el.value = '');

            // Replace the trash button with one that removes this clone
            const trashBtn = clone.querySelector('button');
            trashBtn.setAttribute('onclick', "removeFeature(this)");

            document.getElementById('features-wrapper').appendChild(clone);

            // Focus the new input
            clone.querySelector('input').focus();
        });

        function removeFeature(btn) {
            const items = document.querySelectorAll('.feature-item');
            if (items.length === 1) {
                alert('At least one feature is required.');
                return;
            }
            btn.closest('.feature-item').remove();
        }
    </script>
@endpush
