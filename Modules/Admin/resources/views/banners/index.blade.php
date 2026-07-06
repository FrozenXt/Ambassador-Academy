@extends('admin::layouts.app')
@section('page_title', 'Galleries')

@section('page_actions')
    <div class="btn-group">
        @canCreate
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Gallery
        </a>
        @endcanCreate

        <button type="button" class="btn btn-info btn-sm" onclick="refreshPreview()">
            <i class="fas fa-sync-alt mr-1"></i> Refresh Preview
        </button>
        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#orderModal">
            <i class="fas fa-sort-amount-down mr-1"></i> Reorder
        </button>
    </div>
@endsection

@section('extra_css')
    <style>
        .banner-image-preview {
            width: 100px;
            height: 55px;
            object-fit: cover;
            border-radius: 6px;
            transition: transform 0.3s ease;
        }

        .banner-image-preview:hover {
            transform: scale(1.5);
            cursor: pointer;
        }

        .banner-status-badge {
            font-size: 12px;
            padding: 4px 8px;
        }

        /* Sortable styles */
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

        /* Preview enhancements */
        .carousel-caption {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            border-radius: 8px;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: left;
            padding: 20px;
        }

        .carousel-caption h5 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .carousel-caption p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .preview-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            background: rgba(0, 0, 0, 0.7);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Animation */
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

        .animated-row {
            animation: slideIn 0.3s ease;
        }

        /* Button groups */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        /* Modal styles */
        .order-number {
            width: 60px;
            text-align: center;
            font-weight: bold;
        }

        .order-input {
            width: 80px;
            text-align: center;
        }
    </style>
@endsection

@section('admin_content')
    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $banners->count() }}</h3>
                    <p>Total Galleries</p>
                </div>
                <div class="icon">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $banners->where('status', 'active')->count() }}</h3>
                    <p>Active Galleries</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $banners->where('status', 'inactive')->count() }}</h3>
                    <p>Inactive Galleries</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $banners->where('button_text', '!=', null)->count() }}</h3>
                    <p>With CTAs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-images mr-2"></i> Homepage Galleries
            </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="bannerSearch" class="form-control float-right"
                        placeholder="Search banners...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-default" onclick="searchBanners()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <span class="badge badge-primary ml-2">{{ $banners->count() }} gallery</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="bannersTable">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:60px">
                                <i class="fas fa-sort"></i> Order
                            </th>
                            <th style="width:120px">Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Buttons / Links</th>
                            <th style="width:100px">Status</th>
                            <th style="width:100px">Created</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bannersTableBody">
                        @forelse($banners as $banner)
                            <tr class="sortable-row animated-row" data-id="{{ $banner->id }}"
                                data-order="{{ $banner->order }}">
                                <td>
                                    <span class="badge badge-secondary order-badge">{{ $banner->order }}</span>
                                    <i class="fas fa-grip-vertical text-muted ml-1"></i>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $banner->image) }}" class="banner-image-preview"
                                        onclick="showImageModal('{{ asset('storage/' . $banner->image) }}', '{{ $banner->title }}')"
                                        data-toggle="tooltip" title="Click to enlarge" />
                                </td>
                                <td>
                                    <strong>{{ $banner->title }}</strong>
                                    @if ($banner->subtitle)
                                        <small class="text-muted d-block">{{ Str::limit($banner->subtitle, 50) }}</small>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $banner->subtitle ? Str::limit($banner->subtitle, 60) : '—' }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if ($banner->button_text)
                                            <span class="badge badge-primary mb-1">
                                                <i class="fas fa-link mr-1"></i> {{ $banner->button_text }}
                                            </span>
                                            <small class="text-muted">{{ $banner->button_link ?: 'No link' }}</small>
                                        @endif
                                        @if ($banner->button_text_2)
                                            <span class="badge badge-outline-secondary mt-1">
                                                <i class="fas fa-link mr-1"></i> {{ $banner->button_text_2 }}
                                            </span>
                                        @endif
                                        @if (!$banner->button_text && !$banner->button_text_2)
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST"
                                        class="d-inline toggle-form">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-{{ $banner->status === 'active' ? 'success' : 'secondary' }} banner-status-badge"
                                            data-toggle="tooltip"
                                            title="{{ $banner->status === 'active' ? 'Click to deactivate' : 'Click to activate' }}">
                                            <i
                                                class="fas fa-{{ $banner->status === 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                            {{ ucfirst($banner->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $banner->created_at->format('d M Y') }}
                                        <br>
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $banner->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @canEdit
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                            class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanEdit
                                        <a href="#" class="btn btn-xs btn-info"
                                            onclick="previewBanner({{ $banner->id }})" data-toggle="modal"
                                            data-target="#quickPreviewModal" title="Quick preview">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @canDelete
                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                            class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcanDelete
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-images fa-3x mb-3 d-block"></i>
                                    <p class="mb-2">No Gallery found.</p>
                                    @canCreate
                                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i> Add your first gallery
                                    </a>
                                    @endcanCreate
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
                        Drag rows to reorder Gallery. Active galleries will appear in the homepage slider.
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($banners->count() > 0)
                        <button type="button" class="btn btn-sm btn-primary" onclick="saveOrder()">
                            <i class="fas fa-save mr-1"></i> Save Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Live Preview Section --}}
    @if ($banners->where('status', 'active')->count())
        <div class="card card-outline card-info mt-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye mr-2"></i> Live Preview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" onclick="refreshPreview()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="bannerPreview" class="carousel slide" data-ride="carousel" data-interval="5000">
                    <ol class="carousel-indicators">
                        @foreach ($banners->where('status', 'active') as $i => $banner)
                            <li data-target="#bannerPreview" data-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach ($banners->where('status', 'active') as $i => $banner)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <div style="position:relative;">
                                    <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100"
                                        style="height:400px;object-fit:cover;" alt="{{ $banner->title }}">
                                    <div class="preview-badge">
                                        <span class="badge badge-dark">Banner {{ $i + 1 }}</span>
                                    </div>
                                    <div class="carousel-caption d-block">
                                        <h5 class="animate__animated animate__fadeInUp">{{ $banner->title }}</h5>
                                        @if ($banner->subtitle)
                                            <p class="animate__animated animate__fadeInUp animate__delay-1s">
                                                {{ $banner->subtitle }}</p>
                                        @endif
                                        <div class="mt-3">
                                            @if ($banner->button_text)
                                                <a href="{{ $banner->button_link ?? '#' }}"
                                                    class="btn btn-primary btn-sm mr-2"
                                                    target="{{ $banner->button_link ? '_blank' : '_self' }}">
                                                    {{ $banner->button_text }}
                                                </a>
                                            @endif
                                            @if ($banner->button_text_2)
                                                <a href="{{ $banner->button_link_2 ?? '#' }}"
                                                    class="btn btn-outline-light btn-sm"
                                                    target="{{ $banner->button_link_2 ? '_blank' : '_self' }}">
                                                    {{ $banner->button_text_2 }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($banners->where('status', 'active')->count() > 1)
                        <a class="carousel-control-prev" href="#bannerPreview" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#bannerPreview" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    Showing {{ $banners->where('status', 'active')->count() }} active galleries.
                    <a href="#" onclick="refreshPreview()">Refresh preview</a> to see latest changes.
                </small>
            </div>
        </div>
    @endif

    {{-- Quick Preview Modal --}}
    <div class="modal fade" id="quickPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gallery Preview</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img id="quickPreviewImage" src="" class="img-fluid w-100"
                        style="max-height: 400px; object-fit: cover;">
                    <div class="p-3">
                        <h4 id="quickPreviewTitle"></h4>
                        <p id="quickPreviewSubtitle"></p>
                        <div id="quickPreviewButtons"></div>
                    </div>
                </div>
            </div>
        </div>
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
                    <h5 class="modal-title">Reorder Galleries</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Drag and drop to reorder galleries, or enter order numbers manually.</p>
                    <div id="orderList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveManualOrder()">Save Order</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /* ── Initialize tooltips ── */
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        /* ── Sortable drag & drop ── */
        if (document.getElementById('bannersTableBody')) {
            new Sortable(document.getElementById('bannersTableBody'), {
                handle: '.fa-grip-vertical',
                animation: 150,
                ghostClass: 'dragging',
                onEnd: function() {
                    updateOrderNumbers();
                }
            });
        }

        function updateOrderNumbers() {
            document.querySelectorAll('#bannersTableBody .sortable-row').forEach(function(row, index) {
                var badge = row.querySelector('.order-badge');
                if (badge) badge.textContent = index + 1;
            });
        }

        /* ── Save Order ── */
        function saveOrder() {
            var rows = document.querySelectorAll('#bannersTableBody .sortable-row');
            var orders = [];
            rows.forEach(function(row, index) {
                orders.push({
                    id: parseInt(row.getAttribute('data-id')),
                    order: index + 1
                });
            });

            var btn = document.querySelector('.card-footer .btn-primary');
            var originalHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
            }

            fetch('{{ route('admin.banners.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        orders: orders
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        showToast('success', 'Order saved successfully!');
                        // Reload after short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1200);
                    } else {
                        showToast('error', 'Failed to save order.');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                        }
                    }
                })
                .catch(function() {
                    showToast('error', 'An error occurred.');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                });
        }

        /* ── Toggle Status ── */
        document.querySelectorAll('.toggle-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                var button = this.querySelector('button');
                var originalHtml = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(this),
                    })
                    .then(function(r) {
                        if (!r.ok) throw new Error('Request failed');
                        return r.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            showToast('success', data.message || 'Status updated!');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to update status.');
                        }
                    })
                    .catch(function(err) {
                        showToast('error', err.message || 'An error occurred.');
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                    });
            });
        });

        /* ── Delete confirmation ── */
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this banner?')) {
                    e.preventDefault();
                }
            });
        });

        /* ── Search ── */
        function searchBanners() {
            var term = document.getElementById('bannerSearch').value.toLowerCase();
            document.querySelectorAll('#bannersTableBody .sortable-row').forEach(function(row) {
                var title = row.querySelector('td:nth-child(3) strong')?.textContent.toLowerCase() || '';
                var subtitle = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                row.style.display = (title.includes(term) || subtitle.includes(term)) ? '' : 'none';
            });
        }

        document.getElementById('bannerSearch').addEventListener('keyup', function(e) {
            searchBanners();
        });

        /* ── Preview banner in modal ── */
        function previewBanner(id) {
            var banners = @json($banners);
            var banner = banners.find(function(b) {
                return b.id === id;
            });
            if (!banner) return;

            document.getElementById('quickPreviewImage').src = '{{ asset('storage') }}/' + banner.image;
            document.getElementById('quickPreviewTitle').textContent = banner.title;
            document.getElementById('quickPreviewSubtitle').textContent = banner.subtitle || '';

            var btns = '';
            if (banner.button_text) {
                btns += '<a href="' + (banner.button_url || '#') + '" class="btn btn-primary mr-2" target="_blank">' +
                    banner.button_text + '</a>';
            }
            if (banner.button_text_2) {
                btns += '<a href="' + (banner.button_url_2 || '#') + '" class="btn btn-secondary" target="_blank">' +
                    banner.button_text_2 + '</a>';
            }
            document.getElementById('quickPreviewButtons').innerHTML = btns;
        }

        /* ── Image enlarge modal ── */
        function showImageModal(src, title) {
            document.getElementById('imageModalImg').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            $('#imageModal').modal('show');
        }

        /* ── Refresh preview ── */
        function refreshPreview() {
            location.reload();
        }

        /* ── Simple toast (no SweetAlert dependency) ── */
        function showToast(type, message) {
            // Remove existing toasts
            var existing = document.querySelectorAll('.custom-toast');
            existing.forEach(function(t) {
                t.remove();
            });

            var colors = {
                success: '#28a745',
                error: '#dc3545',
                warning: '#ffc107',
                info: '#17a2b8'
            };
            var icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            var toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.style.cssText = [
                'position:fixed',
                'top:20px',
                'right:20px',
                'z-index:99999',
                'background:' + (colors[type] || colors.info),
                'color:#fff',
                'padding:12px 20px',
                'border-radius:8px',
                'box-shadow:0 4px 16px rgba(0,0,0,.2)',
                'display:flex',
                'align-items:center',
                'gap:10px',
                'font-weight:600',
                'font-size:.9rem',
                'min-width:240px',
                'max-width:380px',
                'opacity:0',
                'transition:opacity .3s',
            ].join(';');

            toast.innerHTML = '<i class="fas ' + (icons[type] || icons.info) + '"></i><span>' + message + '</span>';
            document.body.appendChild(toast);

            // Fade in
            requestAnimationFrame(function() {
                toast.style.opacity = '1';
            });

            // Auto remove after 3s
            setTimeout(function() {
                toast.style.opacity = '0';
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
@endsection
