@extends('admin::layouts.app')
@section('page_title', 'Edit FAQ')

@section('page_actions')
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">

        {{-- LEFT FORM --}}
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i> Edit FAQ
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- QUESTION --}}
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Question <span class="text-danger">*</span>
                            </label>
                            <textarea name="question" rows="3" class="form-control @error('question') is-invalid @enderror"
                                placeholder="Type the frequently asked question...">{{ old('question', $faq->question) }}</textarea>

                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ANSWER --}}
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Answer <span class="text-danger">*</span>
                            </label>
                            <textarea name="answer" rows="6" class="form-control @error('answer') is-invalid @enderror">{{ old('answer', $faq->answer) }}</textarea>

                            <small class="text-muted">You can use HTML tags for formatting.</small>

                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CATEGORY + STATUS + ORDER --}}
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Category</label>
                                    <input type="text" name="category" value="{{ old('category', $faq->category) }}"
                                        class="form-control" placeholder="e.g. General, Billing" list="categoryList" />

                                    <datalist id="categoryList">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active"
                                            {{ old('status', $faq->status) == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $faq->status) == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Order</label>
                                    <input type="number" name="order" value="{{ old('order', $faq->order) }}"
                                        class="form-control" min="0" />
                                </div>
                            </div>

                        </div>

                        {{-- FEATURED SWITCH (FIXED) --}}
                        <div class="form-group">
                            <div class="custom-control custom-switch">

                                {{-- Always send 0 --}}
                                <input type="hidden" name="is_featured" value="0">

                                <input type="checkbox" name="is_featured" value="1" class="custom-control-input"
                                    id="isFeatured" {{ old('is_featured', $faq->is_featured) ? 'checked' : '' }}>

                                <label class="custom-control-label font-weight-bold" for="isFeatured">
                                    Show on Homepage / Featured
                                </label>
                            </div>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update FAQ
                        </button>

                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-default ml-2">
                            Cancel
                        </a>

                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE INFO --}}
        <div class="col-md-4">
            <div class="card card-outline card-secondary">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> FAQ Info
                    </h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0">

                        <tr>
                            <td class="text-muted pl-3">ID</td>
                            <td><code>#{{ $faq->id }}</code></td>
                        </tr>

                        <tr>
                            <td class="text-muted pl-3">Category</td>
                            <td>
                                @if ($faq->category)
                                    <span class="badge badge-info">{{ $faq->category }}</span>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted pl-3">Status</td>
                            <td>
                                <span class="badge badge-{{ $faq->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($faq->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted pl-3">Featured</td>
                            <td>
                                <span class="badge badge-{{ $faq->is_featured ? 'warning' : 'secondary' }}">
                                    {{ $faq->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted pl-3">Created</td>
                            <td>{{ $faq->created_at->format('d M Y') }}</td>
                        </tr>

                        <tr>
                            <td class="text-muted pl-3">Updated</td>
                            <td>{{ $faq->updated_at->format('d M Y') }}</td>
                        </tr>

                    </table>
                </div>

                <div class="card-footer">
                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                        onsubmit="return confirm('Delete this FAQ permanently?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger btn-sm btn-block">
                            <i class="fas fa-trash mr-1"></i> Delete FAQ
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

@endsection
