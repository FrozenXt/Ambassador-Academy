@extends('admin::layouts.app')
@section('page_title', 'Manage Menu — ' . $menu->name)

@section('page_actions')
    <a href="{{ route('admin.menus.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Menus
    </a>
@endsection

@section('extra_css')
    <style>
        .menu-item-row {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 14px;
            gap: 10px;
            margin-bottom: 6px;
            transition: box-shadow .15s;
        }

        .menu-item-row:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
            border-color: #adb5bd;
        }

        .drag-handle {
            cursor: grab;
            color: #adb5bd;
            font-size: 1rem;
            padding: 0 2px;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .menu-item-label {
            font-weight: 600;
            font-size: .92rem;
            color: #212529;
        }

        .menu-item-url {
            font-size: .78rem;
            color: #6c757d;
        }

        .submenu-list {
            list-style: none;
            padding: 4px 0 0 36px;
            margin: 0;
        }

        .submenu-list .menu-item-row {
            background: #f8f9fa;
            border-color: #e9ecef;
        }

        .menu-tree {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>
@endsection

@section('admin_content')

    <div class="row">

        {{-- ── LEFT PANEL ── --}}
        <div class="col-md-4">

            {{-- Menu Settings --}}
            <div class="card card-outline card-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog mr-2"></i> Menu Settings
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" id="menuEditForm">
                        @csrf
                        @method('PUT')

                        {{-- NAME --}}
                        <div class="form-group">
                            <label class="font-weight-bold small">Menu Name</label>
                            <input type="text" id="menu_name_edit" name="name" value="{{ old('name', $menu->name) }}"
                                class="form-control form-control-sm" />
                        </div>

                        {{-- SLUG --}}
                        <div class="form-group">
                            <label class="font-weight-bold small">Slug</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">/menu/</span>
                                </div>

                                <input type="text" id="menu_slug_edit" name="slug"
                                    value="{{ old('slug', $menu->slug) }}" class="form-control form-control-sm" />
                            </div>

                            <small id="slug_status_edit" class="text-muted"></small>
                        </div>

                        {{-- LOCATION --}}
                        <div class="form-group">
                            <label class="font-weight-bold small">Location</label>
                            <select name="location" class="form-control form-control-sm">
                                <option value="">— None —</option>
                                <option value="header" {{ $menu->location == 'header' ? 'selected' : '' }}>Header</option>
                                <option value="footer" {{ $menu->location == 'footer' ? 'selected' : '' }}>Footer</option>
                                <option value="sidebar" {{ $menu->location == 'sidebar' ? 'selected' : '' }}>Sidebar
                                </option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group mb-0">
                            <label class="font-weight-bold small">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="active" {{ $menu->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $menu->status == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning btn-sm btn-block">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
            {{-- Add Menu Item --}}
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i> Add New Item
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.items.add', $menu->id) }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold small">
                                Label <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="label" value="{{ old('label') }}"
                                class="form-control form-control-sm @error('label') is-invalid @enderror"
                                placeholder="e.g. Home, About Us" />
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small">URL</label>
                            <input type="text" name="url" value="{{ old('url') }}"
                                class="form-control form-control-sm" placeholder="e.g. /products or https://..." />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small">
                                Icon
                                <small class="text-muted font-weight-normal">(FontAwesome)</small>
                            </label>
                            <input type="text" name="icon" value="{{ old('icon') }}"
                                class="form-control form-control-sm" placeholder="e.g. fas fa-home" />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small">
                                Parent Item
                                <small class="text-muted font-weight-normal">(for submenu)</small>
                            </label>
                            <select name="parent_id" class="form-control form-control-sm">
                                <option value="">— Top Level —</option>
                                @foreach ($parentItems as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold small">Target</label>
                                    <select name="target" class="form-control form-control-sm">
                                        <option value="_self">Same Tab</option>
                                        <option value="_blank">New Tab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold small">Status</label>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- ── RIGHT PANEL — Menu Tree ── --}}
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sitemap mr-2"></i>
                        Menu Structure
                        <span class="badge badge-primary ml-2">
                            {{ $menu->allItems->count() }} items
                        </span>
                    </h3>
                    <div class="card-tools">
                        <small class="text-muted">
                            <i class="fas fa-arrows-alt mr-1"></i> Drag to reorder
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    @if ($menu->items->count())
                        <ul class="menu-tree" id="sortableMenu">
                            @foreach ($menu->items as $item)
                                <li data-id="{{ $item->id }}" data-parent="">

                                    {{-- Parent Item Row --}}
                                    <div class="menu-item-row">
                                        <span class="drag-handle">
                                            <i class="fas fa-grip-vertical"></i>
                                        </span>

                                        @if ($item->icon)
                                            <i class="{{ $item->icon }} text-primary"
                                                style="width:18px;text-align:center;"></i>
                                        @else
                                            <i class="fas fa-link text-muted"
                                                style="width:18px;text-align:center;font-size:.8rem;"></i>
                                        @endif

                                        <div class="flex-grow-1">
                                            <div class="menu-item-label">
                                                {{ $item->label }}
                                                @if ($item->status == 'inactive')
                                                    <span class="badge badge-secondary ml-1"
                                                        style="font-size:.6rem;">Hidden</span>
                                                @endif
                                                @if ($item->children->count())
                                                    <span class="badge badge-info ml-1" style="font-size:.6rem;">
                                                        {{ $item->children->count() }} sub
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="menu-item-url">
                                                {{ $item->url ?? '#' }}
                                                @if ($item->target == '_blank')
                                                    <i class="fas fa-external-link-alt ml-1"
                                                        style="font-size:.65rem;"></i>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Edit / Delete --}}
                                        <button type="button" class="btn btn-xs btn-outline-warning"
                                            onclick="openEdit(
                                        {{ $item->id }},
                                        {{ json_encode($item->label) }},
                                        {{ json_encode($item->url ?? '') }},
                                        {{ json_encode($item->icon ?? '') }},
                                        {{ json_encode($item->target) }},
                                        {{ json_encode($item->status) }}
                                    )">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>

                                        <form action="{{ route('admin.menus.items.delete', [$menu->id, $item->id]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete {{ addslashes($item->label) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Children --}}
                                    @if ($item->children->count())
                                        <ul class="submenu-list" id="submenu-{{ $item->id }}">
                                            @foreach ($item->children as $child)
                                                <li data-id="{{ $child->id }}" data-parent="{{ $item->id }}">
                                                    <div class="menu-item-row">
                                                        <span class="text-muted" style="font-size:.75rem;">
                                                            <i class="fas fa-level-up-alt fa-rotate-90"></i>
                                                        </span>
                                                        <span class="drag-handle">
                                                            <i class="fas fa-grip-vertical"></i>
                                                        </span>
                                                        @if ($child->icon)
                                                            <i class="{{ $child->icon }} text-info"
                                                                style="width:16px;font-size:.85rem;text-align:center;"></i>
                                                        @else
                                                            <i class="fas fa-link text-muted"
                                                                style="width:16px;font-size:.75rem;text-align:center;"></i>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <div class="menu-item-label" style="font-size:.88rem;">
                                                                {{ $child->label }}
                                                                @if ($child->status == 'inactive')
                                                                    <span class="badge badge-secondary ml-1"
                                                                        style="font-size:.6rem;">Hidden</span>
                                                                @endif
                                                            </div>
                                                            <div class="menu-item-url" style="font-size:.75rem;">
                                                                {{ $child->url ?? '#' }}
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-xs btn-outline-warning"
                                                            onclick="openEdit(
                                                {{ $child->id }},
                                                {{ json_encode($child->label) }},
                                                {{ json_encode($child->url ?? '') }},
                                                {{ json_encode($child->icon ?? '') }},
                                                {{ json_encode($child->target) }},
                                                {{ json_encode($child->status) }}
                                            )">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </button>

                                                        <form
                                                            action="{{ route('admin.menus.items.delete', [$menu->id, $child->id]) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Delete {{ addslashes($child->label) }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bars fa-3x d-block mb-3 opacity-25"></i>
                            <p class="mb-0">No items yet.</p>
                            <small>Add items from the left panel.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── EDIT ITEM MODAL ── --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">

                <div class="modal-header border-0" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                    <h5 class="modal-title font-weight-bold text-white">
                        <i class="fas fa-pencil-alt mr-2"></i> Edit Menu Item
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div class="form-group">
                            <label class="font-weight-bold small">
                                Label <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="label" id="eLabel" class="form-control" required
                                placeholder="Menu item label" />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small">URL</label>
                            <input type="text" name="url" id="eUrl" class="form-control"
                                placeholder="/products or https://..." />
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small">
                                Icon
                                <small class="text-muted font-weight-normal">
                                    (FontAwesome class e.g. fas fa-home)
                                </small>
                            </label>
                            <div class="input-group">
                                <input type="text" name="icon" id="eIcon" class="form-control"
                                    placeholder="fas fa-home" oninput="updateIconPreview(this.value)" />
                                <div class="input-group-append">
                                    <span class="input-group-text" id="iconPreview"
                                        style="min-width:42px;justify-content:center;">
                                        <i id="iconPreviewEl" class="fas fa-question text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold small">Target</label>
                                    <select name="target" id="eTarget" class="form-control">
                                        <option value="_self">Same Tab</option>
                                        <option value="_blank">New Tab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold small">Status</label>
                                    <select name="status" id="eStatus" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-warning font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Update Item
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        var menuId = {{ $menu->id }};
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /* ── Drag & Drop Sortable ── */
        if (document.getElementById('sortableMenu')) {
            Sortable.create(document.getElementById('sortableMenu'), {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: saveOrder,
            });
        }

        document.querySelectorAll('[id^="submenu-"]').forEach(function(el) {
            Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: saveOrder,
            });
        });

        function saveOrder() {
            var items = [];
            var order = 0;

            document.querySelectorAll('#sortableMenu > li').forEach(function(li) {
                items.push({
                    id: parseInt(li.dataset.id),
                    order: ++order,
                    parent_id: null,
                });

                var subOrder = 0;
                var subList = li.querySelector('[id^="submenu-"]');
                if (subList) {
                    subList.querySelectorAll('li').forEach(function(child) {
                        items.push({
                            id: parseInt(child.dataset.id),
                            order: ++subOrder,
                            parent_id: parseInt(li.dataset.id),
                        });
                    });
                }
            });

            fetch('{{ route('admin.menus.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    items: items
                }),
            });
        }

        /* ── Edit Modal ── */
        function openEdit(id, label, url, icon, target, status) {
            // Set form action
            document.getElementById('editForm').action =
                '/admin/menus/' + menuId + '/items/' + id;

            // Fill fields
            document.getElementById('eLabel').value = label || '';
            document.getElementById('eUrl').value = url || '';
            document.getElementById('eIcon').value = icon || '';
            document.getElementById('eTarget').value = target || '_self';
            document.getElementById('eStatus').value = status || 'active';

            // Update icon preview
            updateIconPreview(icon);

            // Show modal
            $('#editModal').modal('show');
        }

        function updateIconPreview(iconClass) {
            var el = document.getElementById('iconPreviewEl');
            if (el) {
                el.className = iconClass || 'fas fa-question text-muted';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {

            let slugEdited = false;
            let debounceTimer;

            const nameInput = document.getElementById('menu_name_edit');
            const slugInput = document.getElementById('menu_slug_edit');
            const slugStatus = document.getElementById('slug_status_edit');

            function slugify(text) {
                return text.toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            // Detect manual edit (IMPORTANT)
            slugInput.addEventListener('input', function() {
                slugEdited = true;
                checkSlug(this.value);
            });

            // Auto-generate while typing (with backspace support)
            nameInput.addEventListener('input', function() {

                if (!slugEdited) {
                    let slug = slugify(this.value);
                    slugInput.value = slug;
                    checkSlug(slug);
                }

            });

            // AJAX check (ignore current menu slug)
            function checkSlug(slug) {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {

                    if (!slug) {
                        slugStatus.innerHTML = '';
                        return;
                    }

                    fetch(`{{ route('admin.menus.checkSlug') }}?slug=${slug}&ignore={{ $menu->id }}`)
                        .then(res => res.json())
                        .then(data => {

                            if (data.exists) {
                                slugStatus.innerHTML =
                                    '<span class="text-danger">Slug already taken</span>';
                            } else {
                                slugStatus.innerHTML =
                                    '<span class="text-success">Slug available</span>';
                            }

                        });

                }, 400);
            }

        });
    </script>
@endsection
