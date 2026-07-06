@extends('admin::layouts.app')

@section('page_title', 'Albums')

@section('admin_content')
    <div class="container-fluid">

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $statistics['total'] }}</h3>
                        <p>Total Albums</p>
                    </div>
                    <div class="icon"><i class="fas fa-images"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $statistics['active'] }}</h3>
                        <p>Active Albums</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $statistics['inactive'] }}</h3>
                        <p>Inactive Albums</p>
                    </div>
                    <div class="icon"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $statistics['featured'] }}</h3>
                        <p>Featured Albums</p>
                    </div>
                    <div class="icon"><i class="fas fa-star"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-book-open mr-2"></i> Manage Albums
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.albums.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Album
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- FILTER FORM -->
                        <form method="GET" action="{{ route('admin.albums.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search by title or slug...">
                                </div>

                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-2">
                                    <a href="{{ route('admin.albums.index') }}" class="btn btn-secondary btn-block">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Drag hint -->
                        <div class="alert alert-info py-2 px-3 mb-3" style="font-size:.85rem;">
                            <i class="fas fa-info-circle mr-1"></i>
                            Drag the <i class="fas fa-grip-vertical"></i> handle to reorder albums.
                        </div>

                        <!-- TABLE -->
                        <table class="table table-bordered table-hover" id="albumsTable">

                            <thead class="thead-light">
                                <tr>
                                    <th width="30"></th>
                                    <th width="50">ID</th>
                                    <th width="80">Cover</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th width="100">Status</th>
                                    <th width="100">Featured</th>
                                    <th width="100">Sort</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>

                            <tbody id="sortableAlbums">
                                @forelse($albums as $album)
                                    <tr data-id="{{ $album->id }}">

                                        <td class="text-center drag-handle" style="cursor:grab;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </td>

                                        <td>{{ $album->id }}</td>

                                        <td>
                                            <img src="{{ $album->cover_image_url }}"
                                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;">
                                        </td>

                                        <td>
                                            <strong>{{ $album->title }}</strong>
                                            @if ($album->description)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($album->description, 50) }}</small>
                                            @endif
                                        </td>

                                        <td><code>{{ $album->slug }}</code></td>

                                        <td>
                                            <span
                                                class="badge badge-{{ $album->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($album->status) }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if ($album->is_featured)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        </td>

                                        <td class="sort-order-cell">{{ $album->sort_order }}</td>

                                        <td>
                                            <a href="{{ route('admin.albums.edit', $album) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.albums.destroy', $album) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this album?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No albums found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                        <!-- Laravel Pagination (kept as fallback) -->
                        <div class="mt-3">
                            {{ $albums->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('extra_js')

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {

            /* =========================
               DATATABLE (FULL FEATURED)
               ========================= */
            $('#albumsTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                lengthChange: true,
                pageLength: 10,
                ordering: false, // important (because drag sort)
                dom: 'lfrtip', // length, filter, table, info, pagination
                language: {
                    search: "Search albums:",
                    lengthMenu: "Show _MENU_ albums",
                    info: "Showing _START_ to _END_ of _TOTAL_ albums"
                }
            });

            /* =========================
               DRAG SORT (UNCHANGED)
               ========================= */
            const tbody = document.getElementById('sortableAlbums');

            new Sortable(tbody, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',

                onEnd: function() {
                    const orders = [];

                    document.querySelectorAll('#sortableAlbums tr[data-id]').forEach((row, index) => {
                        orders.push({
                            id: parseInt(row.dataset.id),
                            sort_order: index + 1
                        });

                        const cell = row.querySelector('.sort-order-cell');
                        if (cell) cell.textContent = index + 1;
                    });

                    $.ajax({
                        url: '{{ route('admin.albums.sort-order') }}',
                        method: 'POST',
                        data: {
                            orders: orders,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            toastr.success('Sort updated!');
                        },
                        error: function() {
                            toastr.error('Error updating sort order');
                        }

                    });
                }
            });

        });
    </script>

    <style>
        .drag-handle {
            cursor: grab;
        }

        .drag-handle:hover {
            color: #6c757d;
        }

        .sortable-ghost {
            background: #e3f2fd !important;
            opacity: 0.6;
        }
    </style>

@endsection
