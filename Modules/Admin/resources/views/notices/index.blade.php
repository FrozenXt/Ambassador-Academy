@extends('admin::layouts.app')
@section('page_title', 'Notices & News')

@section('page_actions')
    <div class="btn-group">
        @canCreate
        <a href="{{ route('admin.notices.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Notice/News
        </a>
        @endcanCreate
        @canDelete
        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#orderModal">
            <i class="fas fa-sort-amount-down mr-1"></i> Reorder
        </button>
        @endcanDelete
    </div>
@endsection

@section('extra_css')
    <style>
        .sortable-row {
            cursor: move;
            transition: background-color 0.3s ease;
        }

        .sortable-row:hover {
            background-color: #f8f9fa;
        }

        .sortable-row.dragging {
            opacity: 0.5;
            background-color: #e9ecef;
        }

        .order-badge {
            display: inline-block;
            min-width: 30px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            background: #6c757d;
            color: white;
            border-radius: 12px;
            margin-right: 5px;
        }

        .drag-handle {
            cursor: grab;
            color: #6c757d;
            font-size: 16px;
        }

        .drag-handle:hover {
            color: #343a40;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .animated-row {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats cards */
        .small-box {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .small-box:hover {
            transform: translateY(-5px);
        }

        /* Badge styles */
        .badge-notice {
            background-color: #17a2b8;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-news {
            background-color: #6f42c1;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .priority-urgent {
            background-color: #343a40;
            color: white;
        }

        .priority-high {
            background-color: #dc3545;
            color: white;
        }

        .priority-medium {
            background-color: #ffc107;
            color: #333;
        }

        .priority-low {
            background-color: #6c757d;
            color: white;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        /* Image preview */
        .notice-image-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .notice-image-preview:hover {
            transform: scale(1.5);
            cursor: pointer;
        }

        /* Modal order list */
        .order-list-item {
            padding: 10px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: move;
            transition: all 0.3s ease;
        }

        .order-list-item:hover {
            background: #e9ecef;
        }

        .order-number {
            display: inline-block;
            width: 40px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $notices->total() }}</h3>
                    <p>Total Notices/News</p>
                </div>
                <div class="icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $notices->where('status', 1)->count() }}</h3>
                    <p>Active</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $notices->where('is_featured', 1)->count() }}</h3>
                    <p>Featured</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $notices->sum('views') }}</h3>
                    <p>Total Views</p>
                </div>
                <div class="icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.notices.index') }}" method="GET"
                class="d-flex gap-2 flex-wrap align-items-center">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                    style="max-width:220px;" placeholder="Search title, content..." />
                <select name="type" class="form-control form-control-sm" style="max-width:160px;"
                    onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="notice" {{ request('type') == 'notice' ? 'selected' : '' }}>Notice</option>
                    <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>News</option>
                </select>
                <select name="priority" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                <select name="status" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </form>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-newspaper mr-2"></i> All Notices & News
            </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" id="noticeSearch" class="form-control float-right" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-default" onclick="searchNotices()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <span class="badge badge-primary ml-2">{{ $notices->total() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="noticesTable">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px">
                                <i class="fas fa-sort"></i> Order
                            </th>
                            <th style="width: 80px">Image</th>
                            <th>Title / Details</th>
                            <th style="width: 100px">Type</th>
                            <th style="width: 100px">Priority</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 100px">Featured</th>
                            <th style="width: 100px">Views</th>
                            <th style="width: 100px">Published</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortableBody">
                        @forelse($notices as $notice)
                            <tr class="sortable-row animated-row" data-id="{{ $notice->id }}"
                                data-order="{{ $notice->order ?? $loop->iteration }}">
                                <td>
                                    <span class="order-badge">{{ $notice->order ?? $loop->iteration }}</span>
                                    <i class="fas fa-grip-vertical drag-handle ml-1"></i>
                                </td>
                                <td>
                                    @if ($notice->featured_image)
                                        <img src="{{ asset('storage/' . $notice->featured_image) }}"
                                            class="notice-image-preview"
                                            onclick="showImageModal('{{ asset('storage/' . $notice->featured_image) }}', '{{ $notice->title }}')"
                                            data-toggle="tooltip" title="Click to enlarge" />
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $notice->title }}</strong>
                                    @if ($notice->short_description)
                                        <div class="text-muted small">
                                            {{ Str::limit($notice->short_description, 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $notice->type == 'notice' ? 'badge-notice' : 'badge-news' }}">
                                        <i
                                            class="fas {{ $notice->type == 'notice' ? 'fa-bullhorn' : 'fa-newspaper' }} mr-1"></i>
                                        {{ ucfirst($notice->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge priority-{{ $notice->priority }}">
                                        {{ ucfirst($notice->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-xs toggle-status
                                        {{ $notice->status ? 'btn-success' : 'btn-secondary' }}"
                                        data-id="{{ $notice->id }}">
                                        <i
                                            class="fas {{ $notice->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $notice->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-xs toggle-featured
                                        {{ $notice->is_featured ? 'btn-warning' : 'btn-default' }}"
                                        data-id="{{ $notice->id }}">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $notice->is_featured ? 'Featured' : 'Not Featured' }}
                                    </button>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-eye mr-1"></i> {{ $notice->views ?? 0 }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $notice->formatted_published_date ?? $notice->created_at->format('d M Y') }}
                                    <br>
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notice->created_at->format('H:i') }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.notices.show', $notice->id) }}"
                                            class="btn btn-xs btn-info" data-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @canEdit
                                        <a href="{{ route('admin.notices.edit', $notice->id) }}"
                                            class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanEdit
                                        @canDelete
                                        <button class="btn btn-xs btn-danger"
                                            onclick="confirmDelete({{ $notice->id }})" data-toggle="tooltip"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcanDelete
                                    </div>
                                    @canDelete
                                    <form id="delete-form-{{ $notice->id }}"
                                        action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcanDelete
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="fas fa-newspaper fa-3x d-block mb-3 opacity-25"></i>
                                    <h5>No notices found</h5>
                                    <p class="mb-0">Click "Add Notice/News" to create your first notice.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Drag the <i class="fas fa-grip-vertical"></i> icon to reorder notices. Order is saved instantly.
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($notices->count() > 0)
                        <button type="button" class="btn btn-sm btn-primary" onclick="saveOrder()">
                            <i class="fas fa-save mr-1"></i> Save Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $notices->links() }}
    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="imageModalTitle"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img id="imageModalImg" src="" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

    {{-- Reorder Modal --}}
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reorder Notices & News</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Drag and drop to reorder notices, or click "Save Order" after dragging in the table.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Current order: {{ $notices->count() }} items
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Initialize tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Sortable drag and drop
        let sortable = null;
        const tbody = document.getElementById('sortableBody');

        if (tbody) {
            sortable = new Sortable(tbody, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'dragging',
                onEnd: function() {
                    updateOrderNumbers();
                }
            });
        }

        // Update order numbers after drag
        function updateOrderNumbers() {
            const rows = document.querySelectorAll('#sortableBody .sortable-row');
            rows.forEach((row, index) => {
                const orderBadge = row.querySelector('.order-badge');
                if (orderBadge) {
                    orderBadge.textContent = index + 1;
                }
            });
        }

        // Save order via AJAX
        function saveOrder() {
            const rows = document.querySelectorAll('#sortableBody .sortable-row');
            const ids = [];

            rows.forEach(row => {
                ids.push(row.getAttribute('data-id'));
            });

            if (ids.length === 0) {
                showNotification('error', 'No items to reorder.');
                return;
            }

            // Show loading state
            const saveBtn = document.querySelector('.card-footer .btn-primary');
            const originalText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

            fetch('{{ route('admin.notices.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: ids
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Order saved successfully!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('error', data.message || 'Failed to save order.');
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred while saving order.');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                });
        }

        // Search functionality
        function searchNotices() {
            const searchTerm = document.getElementById('noticeSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#sortableBody .sortable-row');

            rows.forEach(row => {
                const title = row.querySelector('td:nth-child(3) strong')?.textContent.toLowerCase() || '';
                const description = row.querySelector('td:nth-child(3) .text-muted')?.textContent.toLowerCase() ||
                    '';

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Enter key search
        document.getElementById('noticeSearch')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchNotices();
            }
        });

        // Toggle status with AJAX
        $(document).on('click', '.toggle-status', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let btn = $(this);
            let originalText = btn.html();

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/admin/notices/${id}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        btn.html(response.status ?
                            '<i class="fas fa-check-circle mr-1"></i> Active' :
                            '<i class="fas fa-times-circle mr-1"></i> Inactive');
                        btn.removeClass('btn-success btn-secondary')
                            .addClass(response.status ? 'btn-success' : 'btn-secondary');
                        showNotification('success', response.message || 'Status updated successfully');
                    } else {
                        showNotification('error', response.message || 'Something went wrong');
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to update status');
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Toggle featured with AJAX
        $(document).on('click', '.toggle-featured', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let btn = $(this);
            let originalText = btn.html();

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/admin/notices/${id}/toggle-featured`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        btn.html(response.is_featured ?
                            '<i class="fas fa-star mr-1"></i> Featured' :
                            '<i class="fas fa-star mr-1"></i> Not Featured');
                        btn.removeClass('btn-warning btn-default')
                            .addClass(response.is_featured ? 'btn-warning' : 'btn-default');
                        showNotification('success', response.message || 'Featured status updated');
                    } else {
                        showNotification('error', response.message || 'Something went wrong');
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to update featured status');
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Show image modal
        function showImageModal(src, title) {
            document.getElementById('imageModalImg').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            $('#imageModal').modal('show');
        }

        // Delete confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This notice/news will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Show notification
        function showNotification(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                Swal.fire({
                    title: type === 'success' ? 'Success!' : 'Error!',
                    text: message,
                    icon: type,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        }

        // Auto-refresh on success messages
        @if (session('success'))
            showNotification('success', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showNotification('error', '{{ session('error') }}');
        @endif
    </script>
@endsection
