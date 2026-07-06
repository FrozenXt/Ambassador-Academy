@extends('admin::layouts.app')
@section('page_title', 'Add FAQ')

@section('page_actions')
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i> Add New FAQ
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Question <span class="text-danger">*</span>
                            </label>
                            <textarea name="question" rows="3" class="form-control @error('question') is-invalid @enderror"
                                placeholder="Type the frequently asked question here...">{{ old('question') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Answer <span class="text-danger">*</span>
                            </label>
                            <textarea name="answer" rows="6" class="form-control @error('answer') is-invalid @enderror"
                                placeholder="Type the detailed answer here...">{{ old('answer') }}</textarea>
                            <small class="text-muted">You can use HTML tags for formatting.</small>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Category</label>
                                    <input type="text" name="category" value="{{ old('category') }}"
                                        class="form-control @error('category') is-invalid @enderror"
                                        placeholder="e.g. General, Billing, Technical" list="categoryList" />
                                    <datalist id="categoryList">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                        <option value="General">
                                        <option value="Billing">
                                        <option value="Technical">
                                        <option value="Shipping">
                                        <option value="Returns">
                                    </datalist>
                                    <small class="text-muted">
                                        Type or select an existing category.
                                    </small>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Order</label>
                                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                        min="0" />
                                    <small class="text-muted">Lower = first</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_featured" value="1" class="custom-control-input"
                                    id="isFeatured" {{ old('is_featured') ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isFeatured">
                                    Show on Homepage / Featured
                                </label>
                            </div>
                            <small class="text-muted ml-4">
                                Featured FAQs appear on the homepage FAQ section.
                            </small>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save FAQ
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-default ml-2">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tips --}}
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-2"></i> Tips
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Keep questions clear and concise
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Answers should be detailed but easy to read
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Group similar questions under the same category
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Use order to control display position
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-star text-warning mr-2"></i>
                            Mark as featured to show on homepage
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Categories --}}
            @if ($categories)
                <div class="card card-outline card-secondary mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags mr-2"></i> Existing Categories
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach ($categories as $cat)
                            <span class="badge badge-info mr-1 mb-1" style="cursor:pointer;font-size:.85rem;"
                                onclick="document.querySelector('[name=category]').value='{{ $cat }}'">
                                {{ $cat }}
                            </span>
                        @endforeach
                        @if (empty($categories))
                            <small class="text-muted">No categories yet.</small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
