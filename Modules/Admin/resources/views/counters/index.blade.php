@extends('admin::layouts.app')
@section('page_title', 'Counters')

@section('page_actions')
    <a href="{{ route('admin.counters.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Counter
    </a>
@endsection

@section('extra_css')
    <style>
        .sortable-ghost {
            opacity: .4;
            background: #e8f4fd;
        }

        .drag-handle {
            cursor: grab;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .color-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            border: 2px solid rgba(0, 0, 0, .1);
            vertical-align: middle;
        }

        .preview-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .preview-number {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .preview-title {
            font-size: .95rem;
            opacity: .9;
            font-weight: bold;
        }

        .preview-description {
            font-size: .78rem;
            opacity: .7;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.counters.index') }}" method="GET"
                class="d-flex flex-wrap gap-2 align-items-center">

                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;" placeholder="Search counters..." />

                <select name="status" class="form-control form-control-sm" style="max-width:130px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    <iconify-icon icon="mdi:magnify"></iconify-icon> Search
                </button>

                <a href="{{ route('admin.counters.index') }}" class="btn btn-default btn-sm">
                    <iconify-icon icon="mdi:close"></iconify-icon> Reset
                </a>

                <small class="text-muted ml-auto">
                    <i class="fas fa-sort mr-1"></i> Drag to reorder
                </small>
            </form>
        </div>
    </div>

    {{-- Preview Bar --}}
    @php
        $activeCounters = \Modules\Common\Entities\Counter::where('status', 'active')->orderBy('order')->get();
    @endphp

    @if ($activeCounters->count())
        <div class="card card-outline card-info mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye mr-1"></i> Live Preview
                </h3>
            </div>

            <div class="card-body" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:0 0 4px 4px;">
                <div class="row text-center text-white">
                    @foreach ($activeCounters as $c)
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            {{-- ICON --}}
                            <div class="preview-icon">
                                @if (!empty($c->icon))
                                    <iconify-icon icon="{{ $c->icon }}" width="40"
                                        style="color: {{ $c->color ?? '#ffffff' }};"></iconify-icon>
                                @else
                                    <iconify-icon icon="mdi:counter" width="40"
                                        style="color: {{ $c->color ?? '#ffffff' }};"></iconify-icon>
                                @endif
                            </div>

                            {{-- NUMBER --}}
                            <div class="preview-number">
                                {{ $c->display_number }}
                            </div>

                            {{-- TITLE --}}
                            <div class="preview-title">
                                {{ $c->title }}
                            </div>

                            {{-- DESCRIPTION --}}
                            @if (!empty($c->description))
                                <div class="preview-description">
                                    {{ $c->description }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <iconify-icon icon="mdi:counter"></iconify-icon> All Counters
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $counters->total() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px;"></th>
                            <th style="width:40px;">#</th>
                            <th style="width:80px;">Icon</th>
                            <th>Title</th>
                            <th>Number</th>
                            <th>Description</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="counterSortable">
                        @forelse($counters as $i => $counter)
                            <tr data-id="{{ $counter->id }}">
                                <td class="text-center text-muted drag-handle">
                                    <i class="fas fa-grip-vertical"></i>
                                </td>
                                <td>{{ $counters->firstItem() + $i }}</td>
                                <td>
                                    @if ($counter->icon)
                                        <iconify-icon icon="{{ $counter->icon }}"
                                            style="font-size:1.4rem;color:{{ $counter->color ?? '#6c757d' }};"></iconify-icon>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ $counter->title }}</td>
                                <td>
                                    <span class="font-weight-bold text-primary" style="font-size:1.1rem;">
                                        {{ $counter->display_number }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($counter->description, 50) ?? '—' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="color-dot" style="background:{{ $counter->color ?? '#000000' }};"></span>
                                    <small class="ml-1">{{ $counter->color ?? '—' }}</small>
                                </td>
                                <td>
                                    @canEdit
                                    <form action="{{ route('admin.counters.toggle-status', $counter->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-xs btn-{{ $counter->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($counter->status) }}
                                        </button>
                                    </form>
                                    @endcanEdit
                                </td>
                                <td>
                                    @canEdit
                                    <a href="{{ route('admin.counters.edit', $counter->id) }}"
                                        class="btn btn-xs btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit

                                    @canDelete
                                    <form action="{{ route('admin.counters.destroy', $counter->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this counter?')">
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
                                <td colspan="9" class="text-center text-muted py-5">
                                    <iconify-icon icon="mdi:counter" style="font-size:40px;opacity:.3;"></iconify-icon>
                                    <div>No counters yet.</div>
                                    @canCreate
                                    <a href="{{ route('admin.counters.create') }}">Add one now</a>
                                    @endcanCreate
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($counters->hasPages())
            <div class="card-footer">
                {{ $counters->links() }}
            </div>
        @endif
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            '{{ csrf_token() }}';

        // Initialize Sortable for drag & drop reordering
        document.addEventListener('DOMContentLoaded', function() {
            const sortableContainer = document.getElementById('counterSortable');

            if (sortableContainer) {
                new Sortable(sortableContainer, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: function() {
                        saveOrder();
                    }
                });
            }
        });

        // Save order after drag & drop
        function saveOrder() {
            const items = [];
            const rows = document.querySelectorAll('#counterSortable tr[data-id]');

            rows.forEach((row, index) => {
                items.push({
                    id: parseInt(row.dataset.id),
                    order: index + 1
                });
            });

            fetch('{{ route('admin.counters.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items: items
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Order saved successfully!', 'success');
                    } else {
                        showToast('Failed to save order', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error saving order', 'error');
                });
        }

        // Toast notification helper
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = 'bottom:20px;right:20px;z-index:9999;min-width:200px;animation:fadeInOut 2s ease-in-out;';
            toast.innerHTML =
                `<iconify-icon icon="mdi:${type === 'success' ? 'check' : 'alert'}"></iconify-icon> ${message}`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 2000);
        }

        // Add fade animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(20px); }
                15% { opacity: 1; transform: translateY(0); }
                85% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(20px); }
            }
        `;
        document.head.appendChild(style);

        // Function to render icon dynamically (for create/edit forms)
        function renderIcon(elementId, icon, color = '#ffffff', size = 32) {
            const container = document.getElementById(elementId);
            if (!container) return;

            container.innerHTML = '';

            const iconName = (icon && icon.trim()) ? icon : 'mdi:help-circle-outline';
            const iconElement = document.createElement('iconify-icon');
            iconElement.setAttribute('icon', iconName);
            iconElement.setAttribute('width', size);
            iconElement.style.color = color;

            container.appendChild(iconElement);
        }

        // Update preview (for create/edit forms)
        function updatePreview() {
            const prefix = document.querySelector('[name=prefix]')?.value || '';
            const number = document.querySelector('[name=number]')?.value || '0';
            const suffix = document.querySelector('[name=suffix]')?.value || '';
            const title = document.querySelector('[name=title]')?.value || 'Counter Title';
            const icon = document.getElementById('iconInput')?.value || '';
            const color = document.querySelector('[name=color]')?.value || '#ffffff';

            const previewNumber = document.getElementById('previewNumber');
            const previewTitle = document.getElementById('previewTitle');

            if (previewNumber) previewNumber.innerText = prefix + number + suffix;
            if (previewTitle) previewTitle.innerText = title;

            renderIcon('previewIconWrap', icon, color, 40);
            renderIcon('iconPreviewSmallWrap', icon, '#6c757d', 30);
        }

        // Set icon from picker
        function setIcon(icon) {
            const iconInput = document.getElementById('iconInput');
            if (iconInput) iconInput.value = icon;
            updatePreview();
        }

        // Initialize preview on page load (for create/edit forms)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('previewIconWrap')) {
                updatePreview();
            }

            // Auto-refresh preview on input changes
            const inputs = ['[name=prefix]', '[name=number]', '[name=suffix]', '[name=title]', '[name=color]'];
            inputs.forEach(selector => {
                const element = document.querySelector(selector);
                if (element) {
                    element.addEventListener('input', updatePreview);
                }
            });
        });
    </script>
@endsection
