@extends('admin::layouts.app')
@section('page_title', 'Add Food Item')

@section('page_actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus mr-2"></i> Add New Food Item
            </h3>
        </div>
        <div class="card-body">
            @canCreate
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Food Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="e.g. iPhone 15" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                placeholder="e.g. Classic gin cocktail" />
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Food Categories <span class="text-danger">*</span>
                            </label>

                            <div class="border rounded p-3 @error('category_ids') is-invalid @enderror"
                                style="background:#f8f9fa;">
                                <div class="row">
                                    @php
                                        $selectedCategories = old('category_ids', []);
                                    @endphp

                                    @foreach ($categories as $cat)
                                        <div class="col-md-3 col-sm-4 col-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                                    class="custom-control-input" id="cat_{{ $cat->id }}"
                                                    {{ in_array($cat->id, $selectedCategories) ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="cat_{{ $cat->id }}">
                                                    {{ $cat->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <small class="text-muted">Tick all categories this item belongs to.</small>

                            @error('category_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('category_ids.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rs.</span>
                                </div>
                                <input type="number" name="price" value="{{ old('price') }}"
                                    class="form-control @error('price') is-invalid @enderror" placeholder="0.00"
                                    step="0.01" min="0" />
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                class="form-control @error('stock') is-invalid @enderror" placeholder="0" min="0" />
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
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

                    {{-- ── Base / Style / Served ── --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Base</label>
                            <input type="text" name="base" value="{{ old('base') }}"
                                class="form-control @error('base') is-invalid @enderror"
                                placeholder="e.g. Vodka, Gin, Rum" />
                            <small class="text-muted">The base spirit or main ingredient.</small>
                            @error('base')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Style</label>
                            <input type="text" name="style" value="{{ old('style') }}"
                                class="form-control @error('style') is-invalid @enderror"
                                placeholder="e.g. Stirred, Smoked, Shaken" />
                            @error('style')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Served</label>
                            <input type="text" name="served" value="{{ old('served') }}"
                                class="form-control @error('served') is-invalid @enderror"
                                placeholder="e.g. On the Rocks, Neat, Chilled" />
                            @error('served')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Product description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Food Item URL</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-link"></i>
                                </span>
                            </div>
                            <input type="url" name="url" value="{{ old('url', $product->url ?? '') }}"
                                class="form-control @error('url') is-invalid @enderror"
                                placeholder="https://example.com/product-page" />
                        </div>
                        <small class="text-muted">
                            Optional. External link for this product.
                        </small>
                        @error('url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Features --}}
                    <div class="form-group">
                        <label class="font-weight-bold">
                            Features
                            <small class="text-muted font-weight-normal">(one per line)</small>
                        </label>

                        <div id="featuresContainer">
                            @php
                                $features = old('features', $product->features ?? ['']);
                                if (empty($features)) {
                                    $features = [''];
                                }
                            @endphp

                            @foreach ($features as $i => $feature)
                                <div class="input-group mb-2 feature-row">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-check text-success" style="font-size:.8rem;"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="features[]" value="{{ $feature }}"
                                        class="form-control" placeholder="e.g. High quality material" />
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="removeFeature(this)" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-success btn-sm mt-1" onclick="addFeature()">
                            <i class="fas fa-plus mr-1"></i> Add Feature
                        </button>

                        <small class="text-muted d-block mt-1">
                            Add key features or highlights of this product.
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image"
                                        class="custom-file-input @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/gif,image/svg+xml,image/webp,image/avif"
                                        id="productImage" onchange="previewImage(this, 'productPreview')" />
                                    <label class="custom-file-label" for="productImage">
                                        Choose image...
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">JPG, PNG, GIF or SVG. Max 2MB.</small>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="productPreview" src="#" class="d-none"
                                    style="width:150px;height:150px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;" />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Food Item
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-default ml-2">Cancel</a>
            </form>
            @endcanCreate
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function addFeature() {
            var container = document.getElementById('featuresContainer');
            var row = document.createElement('div');
            row.className = 'input-group mb-2 feature-row';
            row.innerHTML =
                '<div class="input-group-prepend">' +
                '<span class="input-group-text bg-light">' +
                '<i class="fas fa-check text-success" style="font-size:.8rem;"></i>' +
                '</span>' +
                '</div>' +
                '<input type="text" name="features[]" class="form-control" ' +
                'placeholder="e.g. High quality material" />' +
                '<div class="input-group-append">' +
                '<button type="button" class="btn btn-outline-danger btn-sm" ' +
                'onclick="removeFeature(this)" title="Remove">' +
                '<i class="fas fa-times"></i>' +
                '</button>' +
                '</div>';
            container.appendChild(row);

            // Focus on new input
            row.querySelector('input').focus();
        }

        function removeFeature(btn) {
            var rows = document.querySelectorAll('.feature-row');
            if (rows.length > 1) {
                btn.closest('.feature-row').remove();
            } else {
                // Keep at least one row but clear it
                btn.closest('.feature-row').querySelector('input').value = '';
            }
        }

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
