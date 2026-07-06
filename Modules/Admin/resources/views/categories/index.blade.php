@extends('admin::layouts.app')
@section('page_title', 'Categories')

@section('page_actions')
    @canCreate
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Category
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
            <form action="{{ route('admin.categories.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control"
                            placeholder="Search category name..." />
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">— All Status —</option>
                            <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search mr-1"></i> Search
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-default">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tags mr-2"></i> All Categories
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $categories->total() }} total</span>
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
                            <th>Description</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="sortable-categories">
                        @forelse($categories as $i => $cat)
                            <tr class="sortable-row" data-id="{{ $cat->id }}">
                                <td>
                                    <span class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                    <span class="badge badge-info order-badge">
                                        {{ $cat->sort_order ?? $categories->firstItem() + $i }}
                                    </span>
                                </td>

                                <td>
                                    @if ($cat->image)
                                        <img src="{{ asset('storage/' . $cat->image) }}"
                                            style="width:40px;height:40px;object-fit:cover;border-radius:6px;" />
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                            style="width:40px;height:40px;">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="font-weight-bold">{{ $cat->name }}</td>
                                <td>{{ Str::limit($cat->description, 40) ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $cat->products_count ?? 0 }} products</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $cat->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($cat->status) }}
                                    </span>
                                </td>
                                <td>{{ $cat->created_at->format('d M Y') }}</td>
                                <td>
                                    @canEdit
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-xs btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit

                                    @canDelete
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete {{ $cat->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcanDelete
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No categories found.
                                    @canCreate
                                    <a href="{{ route('admin.categories.create') }}">Add one now</a>
                                    @endcanCreate
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> Drag rows to reorder categories
            </small>

            @if ($categories->count() > 0)
                <button type="button" class="btn btn-sm btn-primary" id="saveCategoryOrder">
                    <i class="fas fa-save"></i> Save Order
                </button>
            @endif
        </div>
    </div>

    {{ $categories->links() }}

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tbody = document.getElementById('sortable-categories');

            if (tbody) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'dragging',
                    onEnd: updateOrderNumbers
                });
            }

            function updateOrderNumbers() {
                document.querySelectorAll('#sortable-categories .sortable-row').forEach((row, index) => {
                    row.querySelector('.order-badge').textContent = index + 1;
                });
            }

            document.getElementById('saveCategoryOrder')?.addEventListener('click', function() {

                const orders = Array.from(document.querySelectorAll('#sortable-categories .sortable-row'))
                    .map((row, index) => ({
                        id: row.dataset.id,
                        sort_order: index + 1
                    }));

                fetch("{{ route('admin.categories.update-order') }}", {
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
                            toastr.success('Category order updated!') :
                            toastr.error('Failed!');
                    })
                    .catch(() => toastr.error('Error'));
            });

        });
    </script>
@endsection
