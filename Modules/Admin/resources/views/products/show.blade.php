@extends('admin::layouts.app')
@section('page_title', $product->name)

@section('page_actions')
    @canEdit
    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm mr-2">
        <i class="fas fa-edit mr-1"></i> Edit
    </a>
    @endcanEdit
    <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')
    <div class="row">

        {{-- Product Image --}}
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-body text-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded"
                            style="max-height:300px;object-fit:cover;" />
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                            style="height:200px;">
                            <i class="fas fa-box fa-4x text-muted"></i>
                        </div>
                    @endif

                    <hr>

                    <span class="badge badge-{{ $product->status == 'active' ? 'success' : 'danger' }} badge-lg px-3 py-2">
                        {{ ucfirst($product->status) }}
                    </span>

                    <hr>
                    @canDelete
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Delete this product permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-1"></i> Delete Product
                        </button>
                    </form>
                    @endcanDelete
                </div>
            </div>
        </div>

        {{-- Product Details --}}
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Product Details
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width:35%">Product Name</th>
                                <td class="font-weight-bold">{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Category</th>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Price</th>
                                <td class="font-weight-bold text-success h5">
                                    Rs. {{ number_format($product->price, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Stock</th>
                                <td>
                                    <span
                                        class="badge badge-{{ $product->stock > 5 ? 'primary' : ($product->stock > 0 ? 'warning' : 'danger') }} px-3 py-2">
                                        {{ $product->stock }} units
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Status</th>
                                <td>
                                    <span class="badge badge-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Created At</th>
                                <td>{{ $product->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Updated At</th>
                                <td>{{ $product->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Description</th>
                                <td>{{ $product->description ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
