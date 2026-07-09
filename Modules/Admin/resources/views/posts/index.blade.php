@extends('admin::layouts.app')
@section('page_title', 'Posts')

@section('page_actions')
    <a href="{{ route('admin.posts.trash') }}" class="btn btn-outline-danger btn-sm mr-2">
        <i class="fas fa-trash mr-1"></i> Trash
    </a>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> New Post
    </a>
@endsection

@section('admin_content')

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">

                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;"
                    placeholder="Search posts (server filter)..." />

                <select name="status" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="published" {{ ($filters['status'] ?? '') == 'published' ? 'selected' : '' }}>Published
                    </option>
                    <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>

                <a href="{{ route('admin.posts.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-newspaper mr-2"></i> All Posts
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $posts->count() }} posts</span>
            </div>
        </div>

        <div class="card-body p-0">

            <div class="alert alert-info py-2 px-3 mb-0 rounded-0"
                style="font-size:.85rem;border-left:none;border-right:none;">
                <i class="fas fa-info-circle mr-1"></i>
                Drag <i class="fas fa-grip-vertical"></i> to reorder posts. Changes save automatically.
            </div>

            <table class="table table-hover mb-0" id="postsTable">
                <thead class="thead-dark">
                    <tr>
                        <th width="30"></th>
                        <th style="width:70px;">Image</th>
                        <th>Title</th>
                        <th>Views</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Sort</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="sortablePosts">
                    @forelse($posts as $post)
                        <tr data-id="{{ $post->id }}">

                            {{-- drag --}}
                            <td class="drag-handle text-center" style="vertical-align:middle;">
                                <i class="fas fa-grip-vertical text-muted"></i>
                            </td>

                            {{-- image --}}
                            <td style="vertical-align:middle;">
                                @if ($post->image)
                                    <img src="{{ $post->image_url }}"
                                        style="width:55px;height:40px;object-fit:cover;border-radius:5px;" />
                                @else
                                    <div
                                        style="width:55px;height:40px;background:#f1f5f9;border-radius:5px;
                                        display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- title --}}
                            <td style="vertical-align:middle;">
                                <div class="font-weight-bold">{{ Str::limit($post->title, 45) }}</div>
                                @if ($post->description)
                                    <small class="text-muted">{{ Str::limit($post->description, 55) }}</small>
                                @endif
                            </td>

                            {{-- views --}}
                            <td style="vertical-align:middle;">
                                <span class="badge badge-secondary">
                                    <i class="fas fa-eye mr-1"></i>{{ $post->views }}
                                </span>
                            </td>

                            {{-- featured --}}
                            <td style="vertical-align:middle;">
                                <form action="{{ route('admin.posts.toggle-featured', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-xs btn-{{ $post->is_featured ? 'warning' : 'outline-secondary' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>

                            {{-- status --}}
                            <td style="vertical-align:middle;">
                                <form action="{{ route('admin.posts.toggle-status', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-xs btn-{{ $post->status == 'published' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($post->status) }}
                                    </button>
                                </form>
                            </td>

                            {{-- sort --}}
                            <td class="sort-cell" style="vertical-align:middle;">
                                {{ $post->sort_order }}
                            </td>

                            {{-- published date --}}
                            <td style="vertical-align:middle;">
                                <small class="text-muted">
                                    {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                                </small>
                            </td>

                            {{-- actions --}}
                            <td style="vertical-align:middle;">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No posts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

@endsection

@section('extra_js')

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#postsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 10,
                lengthChange: true
            });

            const tbody = document.getElementById('sortablePosts');

            if (tbody) {
                new Sortable(tbody, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: function() {

                        const orders = [];

                        document.querySelectorAll('#sortablePosts tr[data-id]').forEach(function(row,
                            index) {
                            const newOrder = index + 1;

                            orders.push({
                                id: parseInt(row.getAttribute('data-id')),
                                sort_order: newOrder
                            });

                            const cell = row.querySelector('.sort-cell');
                            if (cell) cell.textContent = newOrder;
                        });

                        $.ajax({
                            url: '{{ route('admin.posts.sort-order') }}',
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
            }

        });
    </script>

    <style>
        .drag-handle {
            cursor: grab;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .sortable-ghost {
            background: #e3f2fd !important;
            opacity: 0.6;
        }
    </style>

@endsection
