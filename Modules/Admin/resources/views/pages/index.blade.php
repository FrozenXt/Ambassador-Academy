@extends('admin::layouts.app')
@section('page_title', 'Pages')

@section('page_actions')
    @canDelete
    <a href="{{ route('admin.pages.trash') }}" class="btn btn-outline-danger btn-sm mr-2">
        <i class="fas fa-trash mr-1"></i> Trash
    </a>
    @endcanDelete
    @canCreate
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Page
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
            <form action="{{ route('admin.pages.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control"
                            placeholder="Search pages..." />
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">— All Status —</option>
                            <option value="published" {{ ($filters['status'] ?? '') == 'published' ? 'selected' : '' }}>
                                Published</option>
                            <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="layout" class="form-control">
                            <option value="">— All Layouts —</option>
                            <option value="default" {{ ($filters['layout'] ?? '') == 'default' ? 'selected' : '' }}>Default
                            </option>
                            <option value="full-width" {{ ($filters['layout'] ?? '') == 'full-width' ? 'selected' : '' }}>
                                Full Width</option>
                            <option value="sidebar" {{ ($filters['layout'] ?? '') == 'sidebar' ? 'selected' : '' }}>Sidebar
                            </option>
                            <option value="landing" {{ ($filters['layout'] ?? '') == 'landing' ? 'selected' : '' }}>Landing
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mr-1">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Pages Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-alt mr-2"></i> All Pages
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $pages->total() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:60px">Order</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Layout</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="sortable-pages">
                        @forelse($pages as $i => $page)
                            <tr class="sortable-row" data-id="{{ $page->id }}">
                                <td>
                                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                    <span
                                        class="badge badge-info order-badge">{{ $page->order ?? $pages->firstItem() + $i }}</span>
                                </td>

                                <td>
                                    <div class="font-weight-bold">{{ $page->title }}</div>
                                    @if ($page->featured_image)
                                        <small class="text-muted">
                                            <i class="fas fa-image mr-1"></i> Has image
                                        </small>
                                    @endif
                                </td>

                                <td><code>{{ $page->slug }}</code></td>
                                <td><span class="badge badge-info">{{ ucfirst($page->layout) }}</span></td>
                                <td><span
                                        class="badge badge-{{ $page->status == 'published' ? 'success' : 'warning' }}">{{ ucfirst($page->status) }}</span>
                                </td>
                                <td>{{ $page->created_at->format('d M Y') }}</td>

                                <td>
                                    @if ($page->status == 'published')
                                        <a href="{{ route('web.page', $page->slug) }}" class="btn btn-xs btn-info"
                                            target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    @canEdit
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-xs btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit

                                    @canDelete
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Move to trash?')">
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    No pages found.
                                    <a href="{{ route('admin.pages.create') }}">Create one now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> Drag rows to reorder pages
            </small>

            @if ($pages->count() > 0)
                <button type="button" class="btn btn-sm btn-primary" id="savePageOrder">
                    <i class="fas fa-save"></i> Save Order
                </button>
            @endif
        </div>
    </div>

    {{ $pages->links() }}

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tbody = document.getElementById('sortable-pages');

            if (tbody) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'dragging',
                    onEnd: updateOrderNumbers
                });
            }

            function updateOrderNumbers() {
                document.querySelectorAll('#sortable-pages .sortable-row').forEach((row, index) => {
                    row.querySelector('.order-badge').textContent = index + 1;
                });
            }

            document.getElementById('savePageOrder')?.addEventListener('click', function() {

                const orders = Array.from(document.querySelectorAll('#sortable-pages .sortable-row'))
                    .map((row, index) => ({
                        id: row.dataset.id,
                        order: index + 1
                    }));

                fetch("{{ route('admin.pages.update-order') }}", {
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
                            toastr.success('Page order updated!') :
                            toastr.error('Failed!');
                    })
                    .catch(() => toastr.error('Error'));
            });

        });
    </script>
@endsection
