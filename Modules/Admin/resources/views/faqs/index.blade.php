@extends('admin::layouts.app')
@section('page_title', 'FAQs')

@section('page_actions')
    @can('create faqs')
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add FAQ
        </a>
    @endcan
@endsection

@section('extra_css')
    <style>
        .faq-row {
            cursor: grab;
        }

        .faq-row:active {
            cursor: grabbing;
        }

        .sortable-ghost {
            opacity: .4;
            background: #e8f4fd;
        }
    </style>
@endsection

@section('admin_content')

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.faqs.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">

                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;"
                    placeholder="Search question or answer..." />

                <select name="category" class="form-control form-control-sm" style="max-width:160px;"
                    onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ ($filters['category'] ?? '') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-control form-control-sm" style="max-width:130px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>

                @can('edit faqs')
                    <small class="text-muted ml-auto">
                        <i class="fas fa-arrows-alt mr-1"></i> Drag rows to reorder
                    </small>
                @endcan

            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-question-circle mr-2"></i> All FAQs
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $faqs->total() }} total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        @can('edit faqs')
                            <th style="width:40px;"></th>
                        @endcan
                        <th style="width:40px;">#</th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Order</th>
                        @canany(['edit faqs', 'delete faqs'])
                            <th>Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody id="faqSortable">
                    @forelse($faqs as $i => $faq)
                        <tr class="faq-row" data-id="{{ $faq->id }}">

                            {{-- Drag Handle --}}
                            @can('edit faqs')
                                <td class="text-center text-muted" style="cursor:grab;">
                                    <i class="fas fa-grip-vertical"></i>
                                </td>
                            @endcan

                            <td>{{ $faqs->firstItem() + $i }}</td>

                            {{-- Question --}}
                            <td>
                                <div class="font-weight-bold" style="max-width:400px;">
                                    {{ Str::limit($faq->question, 80) }}
                                </div>
                                <small class="text-muted">
                                    {{ Str::limit(strip_tags($faq->answer), 60) }}
                                </small>
                            </td>

                            {{-- Category --}}
                            <td>
                                @if ($faq->category)
                                    <span class="badge badge-info">{{ $faq->category }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Featured toggle --}}
                            <td>
                                @can('edit faqs')
                                    <form action="{{ route('admin.faqs.toggle-featured', $faq->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-xs btn-{{ $faq->is_featured ? 'warning' : 'outline-secondary' }}"
                                            title="{{ $faq->is_featured ? 'Featured' : 'Not Featured' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="btn btn-xs btn-{{ $faq->is_featured ? 'warning' : 'outline-secondary' }}"
                                        title="{{ $faq->is_featured ? 'Featured' : 'Not Featured' }}">
                                        <i class="fas fa-star"></i>
                                    </span>
                                @endcan
                            </td>

                            {{-- Status toggle --}}
                            <td>
                                @can('edit faqs')
                                    <form action="{{ route('admin.faqs.toggle-status', $faq->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-xs btn-{{ $faq->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($faq->status) }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-{{ $faq->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($faq->status) }}
                                    </span>
                                @endcan
                            </td>

                            {{-- Order --}}
                            <td>
                                <span class="badge badge-secondary">{{ $faq->order }}</span>
                            </td>

                            {{-- Actions --}}
                            @canany(['edit faqs', 'delete faqs'])
                                <td>
                                    @can('edit faqs')
                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('delete faqs')
                                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this FAQ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            @endcanany

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-question-circle fa-3x d-block mb-3 opacity-25"></i>
                                No FAQs yet.
                                @can('create faqs')
                                    <a href="{{ route('admin.faqs.create') }}">Add one now</a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $faqs->links() }}
        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        @can('edit faqs')
            var sortable = Sortable.create(document.getElementById('faqSortable'), {
                handle: 'td:first-child',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    var items = [];
                    document.querySelectorAll('#faqSortable tr[data-id]').forEach(function(row, index) {
                        items.push({
                            id: parseInt(row.dataset.id),
                            order: index + 1,
                        });
                    });

                    fetch('{{ route('admin.faqs.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                items: items
                            }),
                        }).then(function(r) {
                            return r.json();
                        })
                        .then(function(res) {
                            if (res.success) {
                                var toast = document.createElement('div');
                                toast.className = 'alert alert-success position-fixed';
                                toast.style.cssText =
                                'bottom:20px;right:20px;z-index:9999;min-width:200px;';
                                toast.innerHTML = '<i class="fas fa-check mr-2"></i> Order saved!';
                                document.body.appendChild(toast);
                                setTimeout(function() {
                                    toast.remove();
                                }, 2000);
                            }
                        });
                }
            });
        @endcan
    </script>
@endsection
