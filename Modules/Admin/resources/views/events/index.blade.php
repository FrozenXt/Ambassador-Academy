@extends('admin::layouts.app')

@section('page_title', 'Events & Announcements')

@section('page_actions')
    @can('create events')
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Event
        </a>
    @endcan
@endsection

@section('extra_css')
    <style>
        .event-row {
            cursor: grab;
        }

        .event-row:active {
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
            <form action="{{ route('admin.events.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">

                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;" placeholder="Search title, location..." />

                <select name="type" class="form-control form-control-sm" style="max-width:160px;"
                    onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="event" {{ ($filters['type'] ?? '') == 'event' ? 'selected' : '' }}>Events</option>
                    <option value="announcement" {{ ($filters['type'] ?? '') == 'announcement' ? 'selected' : '' }}>
                        Announcements</option>
                </select>

                <select name="status" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="published" {{ ($filters['status'] ?? '') == 'published' ? 'selected' : '' }}>Published
                    </option>
                    <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <select name="period" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Periods</option>
                    <option value="upcoming" {{ ($filters['period'] ?? '') == 'upcoming' ? 'selected' : '' }}>Upcoming
                    </option>
                    <option value="past" {{ ($filters['period'] ?? '') == 'past' ? 'selected' : '' }}>Past</option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>

                <a href="{{ route('admin.events.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>

                @can('edit events')
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
                <i class="fas fa-calendar-alt mr-2"></i> All Events & Announcements
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $events->count() }} total</span>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        @can('edit events')
                            <th style="width:40px;"></th>
                        @endcan
                        <th style="width:80px">Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Order</th>
                        <th>Status</th>
                        @canany(['edit events', 'delete events'])
                            <th>Actions</th>
                        @endcanany
                    </tr>
                </thead>

                <tbody id="eventSortable">
                    @forelse($events as $event)
                        <tr class="event-row" data-id="{{ $event->id }}">

                            {{-- Drag Handle (edit permission only) --}}
                            @can('edit events')
                                <td class="text-center text-muted" style="cursor:grab;">
                                    <i class="fas fa-grip-vertical"></i>
                                </td>
                            @endcan

                            {{-- Image --}}
                            <td>
                                @if ($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}"
                                        style="width:60px;height:40px;object-fit:cover;border-radius:5px;" />
                                @else
                                    <div
                                        style="width:60px;height:40px;background:#f1f5f9;
                                        border-radius:5px;display:flex;align-items:center;
                                        justify-content:center;color:#94a3b8;">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Title --}}
                            <td>
                                <div class="font-weight-bold">{{ $event->title }}</div>
                                @if ($event->short_description)
                                    <small class="text-muted">
                                        {{ Str::limit($event->short_description, 50) }}
                                    </small>
                                @endif
                                @if ($event->is_featured)
                                    <span class="badge badge-warning ml-1" style="font-size:.6rem;">
                                        Featured
                                    </span>
                                @endif
                            </td>

                            {{-- Type --}}
                            <td>
                                <span class="badge badge-{{ $event->type == 'event' ? 'info' : 'secondary' }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td>
                                <div class="small">
                                    {{ $event->start_date->format('d M Y') }}
                                </div>
                                @if ($event->end_date)
                                    <div class="small text-muted">
                                        → {{ $event->end_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Location --}}
                            <td>
                                {{ $event->location ?? '—' }}
                            </td>

                            {{-- Order --}}
                            <td>
                                <span class="badge badge-secondary">{{ $event->order }}</span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @can('edit events')
                                    <form action="{{ route('admin.events.toggle-status', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-xs btn-{{ $event->status == 'published' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($event->status) }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-{{ $event->status == 'published' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                @endcan
                            </td>

                            {{-- Actions --}}
                            @canany(['edit events', 'delete events'])
                                <td>
                                    @can('edit events')
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('delete events')
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this event?')">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        @can('edit events')
            Sortable.create(document.getElementById('eventSortable'), {
                handle: 'td:first-child',
                animation: 150,
                ghostClass: 'sortable-ghost',

                onEnd: function() {
                    let items = [];

                    document.querySelectorAll('#eventSortable tr[data-id]').forEach(function(row, index) {
                        items.push({
                            id: parseInt(row.dataset.id),
                            order: index + 1
                        });
                    });

                    fetch('{{ route('admin.events.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                items: items
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                let toast = document.createElement('div');
                                toast.className = 'alert alert-success position-fixed';
                                toast.style.cssText = 'bottom:20px;right:20px;z-index:9999;';
                                toast.innerHTML = 'Order saved!';
                                document.body.appendChild(toast);

                                setTimeout(() => toast.remove(), 2000);
                            }
                        });
                }
            });
        @endcan
    </script>
@endsection
