@extends('admin::layouts.app')
@section('page_title', 'Services')

@section('page_actions')
    <div class="btn-group">
        @canCreate
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm" style="margin-right: 5px;">
            <i class="fas fa-plus mr-1"></i> Add Service
        </a>
        @endcanCreate

        @canDelete
        <a href="{{ route('admin.services.trash') }}" class="btn btn-danger btn-sm" style="margin-right: 5px;">
            <i class="fas fa-trash mr-1"></i> Trash
        </a>
        @endcanDelete
    </div>
@endsection

@section('extra_css')
    <style>
        .service-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .sortable-row {
            cursor: move;
        }

        .sortable-row.dragging {
            opacity: 0.5;
            background: #f0f0f0;
        }
    </style>
@endsection

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cogs mr-2"></i> Manage Services
            </h3>
            <div class="card-tools">
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Search services..."
                            value="{{ request('search') }}">
                        <select name="status" class="form-control ml-2">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">Order</th>
                            <th style="width: 80px">Image</th>
                            <th>Title</th>
                            <th> Type </th>
                            {{-- <th>Icon</th> --}}
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-table">
                        @forelse($services as $service)
                            <tr class="sortable-row" data-id="{{ $service->id }}">
                                <td>
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                    <span class="order-badge">{{ $service->order }}</span>
                                </td>
                                <td>
                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" class="service-image"
                                            alt="{{ $service->title }}">
                                    @else
                                        <div
                                            class="service-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $service->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                </td>
                                <td>
                                    @if ($service->type)
                                        <i class="{{ $service->type }} mr-2"></i>
                                        <small class="text-muted">
                                            {{ ucwords(str_replace(['fas fa-', 'far fa-', 'fab fa-', '-'], ['', '', '', ' '], $service->type)) }}
                                        </small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.services.toggle-status', $service->id) }}" method="POST"
                                        class="toggle-status-form">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-{{ $service->status === 'active' ? 'success' : 'secondary' }}">
                                            <i
                                                class="fas fa-{{ $service->status === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ ucfirst($service->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <small>
                                        {{ $service->created_at->format('d M Y') }}
                                        <br>
                                        {{ $service->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.services.show', $service->id) }}"
                                            class="btn btn-sm btn-info" style="margin-right: 5px;">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @canEdit
                                        <a href="{{ route('admin.services.edit', $service->id) }}"
                                            class="btn btn-sm btn-warning" style="margin-right: 5px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanEdit

                                        @canDelete
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                                            style="margin-right: 5px; display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this service?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcanDelete
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-cogs fa-3x mb-3 text-muted"></i>
                                    <p>No services found.</p>
                                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                                        Add your first service
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Drag rows to reorder services
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($services->count() > 0)
                        <button type="button" class="btn btn-sm btn-primary" id="saveOrder">
                            <i class="fas fa-save"></i> Save Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{ $services->links() }}
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sortable drag and drop
            const tbody = document.getElementById('sortable-table');
            if (tbody) {
                new Sortable(tbody, {
                    handle: '.fa-grip-vertical',
                    animation: 150,
                    ghostClass: 'dragging',
                    onEnd: function() {
                        updateOrderNumbers();
                    }
                });
            }

            // Update order numbers
            function updateOrderNumbers() {
                const rows = document.querySelectorAll('#sortable-table .sortable-row');
                rows.forEach((row, index) => {
                    const orderBadge = row.querySelector('.order-badge');
                    if (orderBadge) {
                        orderBadge.textContent = index + 1;
                    }
                });
            }

            // Save order
            $('#saveOrder').click(function() {
                console.log('Button clicked');
                const rows = document.querySelectorAll('#sortable-table .sortable-row');
                const orders = [];

                rows.forEach((row, index) => {
                    orders.push({
                        id: row.getAttribute('data-id'),
                        order: index + 1
                    });
                });

                $.ajax({
                    url: '{{ route('admin.services.reorder') }}',
                    method: 'POST',
                    data: {
                        orders: orders,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Order saved successfully!');
                        } else {
                            toastr.error('Failed to save order.');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred.');
                    }
                });
            });

            // Toggle status with AJAX
            $('.toggle-status-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const button = form.find('button');
                const originalText = button.html();

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(() => location.reload(), 500);
                        } else {
                            toastr.error(response.message);
                            button.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to update status.');
                        button.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Delete confirmation
            $('.delete-form').submit(function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete Service?',
                    text: "This service will be moved to trash.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
