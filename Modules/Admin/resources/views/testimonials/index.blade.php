@extends('admin::layouts.app')
@section('page_title', 'Testimonials')

@section('page_actions')
    <div class="btn-group">
        @canCreate
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Testimonial
        </a>
        @endcanCreate
        @canDelete
        <a href="{{ route('admin.testimonials.trash') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-trash mr-1"></i> Trash
        </a>
        @endcanDelete
    </div>
@endsection

@section('extra_css')
    <style>
        .sortable-handle {
            cursor: move;
        }

        .sortable-row.dragging {
            opacity: 0.5;
            background: #f0f0f0;
        }

        /* Avatar circle fallback */
        .avatar-fallback {
            width: 40px;
            height: 40px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            color: #fff;
        }

        .testimonial-content {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.testimonials.index') }}" method="GET"
                class="d-flex gap-2 flex-wrap align-items-center">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;" placeholder="Search name, company..." />
                <select name="status" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
                <select name="rating" class="form-control form-control-sm" style="max-width:130px;"
                    onchange="this.form.submit()">
                    <option value="">All Ratings</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ ($filters['rating'] ?? '') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-quote-left mr-2"></i> All Testimonials
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $testimonials->total() }} total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px">Order</th>
                            <th style="width: 60px">Avatar</th>
                            <th>Name / Details</th>
                            <th>Content</th>
                            <th>Rating</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-table">
                        @forelse($testimonials as $t)
                            <tr class="sortable-row" data-id="{{ $t->id }}">
                                <td>
                                    <i class="fas fa-grip-vertical text-muted sortable-handle"></i>
                                    <span class="order-badge">{{ $t->order ?? $loop->iteration }}</span>
                                </td>
                                <td>
                                    @if ($t->avatar)
                                        <img src="{{ asset('storage/' . $t->avatar) }}" class="img-circle elevation-1"
                                            style="width:40px;height:40px;object-fit:cover; border-radius: 50%;" />
                                    @else
                                        <div class="avatar-fallback bg-gradient-primary">
                                            {{ $t->initials ?? substr($t->name, 0, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $t->name }}</div>
                                    @if ($t->position || $t->company)
                                        <small class="text-muted">
                                            {{ $t->position }}
                                            @if ($t->position && $t->company)
                                                ·
                                            @endif
                                            {{ $t->company }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="testimonial-content">
                                        <small class="text-muted">
                                            "{{ Str::limit($t->content, 60) }}"
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:.85rem; white-space:nowrap;">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= $t->rating ? 'text-warning' : 'text-muted' }}"
                                                style="font-size:.8rem;"></i>
                                        @endfor
                                        <small class="text-muted ml-1">{{ $t->rating }}/5</small>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('admin.testimonials.toggle-featured', $t->id) }}" method="POST"
                                        class="toggle-featured-form d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-{{ $t->is_featured ? 'warning' : 'secondary' }}"
                                            title="{{ $t->is_featured ? 'Featured' : 'Not Featured' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.testimonials.toggle-status', $t->id) }}" method="POST"
                                        class="toggle-status-form">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-{{ $t->status === 'active' ? 'success' : 'secondary' }}">
                                            <i
                                                class="fas fa-{{ $t->status === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ ucfirst($t->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <small>
                                        {{ $t->created_at->format('d M Y') }}
                                        <br>
                                        {{ $t->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">

                                        @canEdit
                                        <a href="{{ route('admin.testimonials.edit', $t->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanEdit

                                        @canDelete
                                        <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST"
                                            style="display:inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to move this testimonial to trash?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcanDelete


                                    </div>
                                </td>
                            </tr>
                        @empty

                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-quote-left fa-3x mb-3 text-muted"></i>
                                    <p>No testimonials found.</p>
                                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                                        Add your first testimonial
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
                        <i class="fas fa-info-circle"></i> Drag the <i class="fas fa-grip-vertical"></i> icon to reorder
                        testimonials
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($testimonials->count() > 0)
                        <button type="button" class="btn btn-sm btn-primary" id="saveOrder">
                            <i class="fas fa-save"></i> Save Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{ $testimonials->links() }}
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sortable drag and drop
            const tbody = document.getElementById('sortable-table');
            if (tbody) {
                new Sortable(tbody, {
                    handle: '.sortable-handle',
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
                const rows = document.querySelectorAll('#sortable-table .sortable-row');
                const orders = [];

                rows.forEach((row, index) => {
                    orders.push({
                        id: row.getAttribute('data-id'),
                        order: index + 1
                    });
                });

                $.ajax({
                    url: '{{ route('admin.testimonials.update-order') }}',
                    method: 'POST',
                    data: {
                        orders: orders,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to update order.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
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

            // Toggle featured with AJAX
            $('.toggle-featured-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const button = form.find('button');
                const originalText = button.html();

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

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
                        toastr.error('Failed to update featured status.');
                        button.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endsection
