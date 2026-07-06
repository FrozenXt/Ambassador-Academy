@extends('admin::layouts.app')
@section('page_title', 'Pricing Plans')

@section('page_actions')
    <div class="btn-group">
        @canCreate
        <a href="{{ route('admin.pricings.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Pricing Plan
        </a>
        @endcanCreate
    </div>
@endsection

@section('extra_css')
    <style>
        /* Drag handle */
        .drag-handle {
            cursor: move;
            color: #6c757d;
            font-size: 18px;
            margin-right: 5px;
        }

        /* Row being dragged */
        .sortable-row.dragging {
            background-color: #f4f6f9;
            opacity: 0.7;
        }

        /* Smooth transition */
        #sortable-table .sortable-row {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .order-badge {
            display: inline-block;
            min-width: 25px;
            padding: 4px 7px;
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('admin_content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">All Pricing Plans</h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $pricings->total() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 60px">Order</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Period</th>
                            <th>Active</th>
                            <th>Popular</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-table">
                        @foreach ($pricings as $plan)
                            <tr class="sortable-row" data-id="{{ $plan->id }}">
                                <td class="align-middle">
                                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                    <span
                                        class="order-badge badge badge-info">{{ $plan->sort_order ?? $loop->iteration }}</span>
                                </td>
                                <td class="align-middle">{{ $plan->name }}</td>
                                <td class="align-middle">{{ $plan->currency }}{{ $plan->price }}</td>
                                <td class="align-middle">{{ $plan->period }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-{{ $plan->is_popular ? 'warning' : 'secondary' }}">
                                        {{ $plan->is_popular ? 'Popular' : '-' }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        @canEdit
                                        <a href="{{ route('admin.pricings.edit', $plan->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanEdit
                                        @canDelete
                                        <form action="{{ route('admin.pricings.destroy', $plan->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Move this plan to trash?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcanDelete
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Drag rows to reorder pricing plans
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($pricings->count() > 0)
                        <button type="button" class="btn btn-sm btn-primary" id="saveOrder">
                            <i class="fas fa-save"></i> Save Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{ $pricings->links() }}
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {

            const tbody = document.getElementById('sortable-table');

            if (tbody) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'dragging',
                    onEnd: updateOrderNumbers
                });
            }

            function updateOrderNumbers() {
                const rows = document.querySelectorAll('#sortable-table .sortable-row');
                rows.forEach((row, index) => {
                    row.querySelector('.order-badge').textContent = index + 1;
                });
            }

            // Save order via AJAX
            $('#saveOrder').click(function() {
                const rows = document.querySelectorAll('#sortable-table .sortable-row');
                const orders = Array.from(rows).map((row, index) => ({
                    id: row.dataset.id,
                    sort_order: index + 1
                }));

                $.ajax({
                    url: '{{ route('admin.pricings.update-order') }}',
                    method: 'POST',
                    data: {
                        orders: orders,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Pricing order updated successfully!');
                        } else {
                            toastr.error('Failed to update order.');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred.');
                    }
                });
            });
        });
    </script>
@endsection
