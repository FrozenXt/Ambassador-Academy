@extends('admin::layouts.app')
@section('page_title', 'Food Items')

@section('page_actions')
    @canCreate
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Food Item
    </a>
    @endcanCreate
@endsection

@section('extra_css')
    <style>
        .drag-handle {
            cursor: move;
            color: #6c757d;
            font-size: 18px;
        }

        .sortable-row.dragging {
            background-color: #f4f6f9;
            opacity: 0.7;
        }

        .order-badge {
            min-width: 25px;
            padding: 4px 7px;
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Search & Filter --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control"
                            placeholder="Search food item name..." />
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-control">
                            <option value="">— All Categories —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">— All Status —</option>
                            <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                            class="form-control" placeholder="Min price" />
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                            class="form-control" placeholder="Max price" />
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search mr-1"></i> Search
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box mr-2"></i> All Food Items
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $products->total() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:60px">Order</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="sortable-products">
                        @forelse($products as $i => $product)
                            <tr class="sortable-row" data-id="{{ $product->id }}">
                                <td>
                                    <span class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                    <span class="badge badge-info order-badge">
                                        {{ $product->sort_order ?? $products->firstItem() + $i }}
                                    </span>
                                </td>

                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            style="width:45px;height:45px;object-fit:cover;border-radius:6px;" />
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                            style="width:45px;height:45px;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="font-weight-bold">{{ $product->name }}</td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>

                                <td class="font-weight-bold text-success">
                                    Rs. {{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    <span
                                        class="badge badge-{{ $product->stock > 5 ? 'primary' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>

                                <td>{{ $product->created_at->format('d M Y') }}</td>

                                <td>
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @canEdit
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit

                                    @canDelete
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger"
                                            onclick="return confirm('Delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcanDelete
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No food items found.
                                    <a href="{{ route('admin.products.create') }}">Add one now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> Drag rows to reorder food items
            </small>

            @if ($products->count() > 0)
                <button type="button" class="btn btn-sm btn-primary" id="saveProductOrder">
                    <i class="fas fa-save"></i> Save Order
                </button>
            @endif
        </div>
    </div>

    {{ $products->links() }}

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tbody = document.getElementById('sortable-products');

            if (tbody) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'dragging',
                    onEnd: updateOrderNumbers
                });
            }

            function updateOrderNumbers() {
                document.querySelectorAll('#sortable-products .sortable-row').forEach((row, index) => {
                    const badge = row.querySelector('.order-badge');
                    // If row.dataset.sort exists, use it, else use index+1
                    badge.textContent = index + 1;
                });
            }

            document.getElementById('saveProductOrder')?.addEventListener('click', function() {

                const orders = Array.from(document.querySelectorAll('#sortable-products .sortable-row'))
                    .map((row, index) => ({
                        id: row.dataset.id,
                        sort_order: index + 1
                    }));

                fetch("{{ route('admin.products.update-order') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            orders
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        data.success ?
                            toastr.success('Product order updated!') :
                            toastr.error('Failed to update');
                    })
                    .catch(() => toastr.error('Error occurred'));
            });

        });
    </script>
@endsection
