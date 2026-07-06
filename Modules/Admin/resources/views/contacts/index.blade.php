@extends('admin::layouts.app')
@section('page_title', 'Contact Messages')

@section('admin_content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="small-box bg-info mb-0">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total</p>
                </div>
                <div class="icon"><i class="fas fa-envelope"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="small-box bg-danger mb-0">
                <div class="inner">
                    <h3>{{ $stats['unread'] }}</h3>
                    <p>Unread</p>
                </div>
                <div class="icon"><i class="fas fa-envelope-open"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="small-box bg-warning mb-0">
                <div class="inner">
                    <h3>{{ $stats['read'] }}</h3>
                    <p>Read</p>
                </div>
                <div class="icon"><i class="fas fa-eye"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="small-box bg-success mb-0">
                <div class="inner">
                    <h3>{{ $stats['replied'] }}</h3>
                    <p>Replied</p>
                </div>
                <div class="icon"><i class="fas fa-reply"></i></div>
            </div>
        </div>
    </div>

    {{-- Filters + Actions --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center g-2">

                <div class="col-12 col-md-auto">
                    <form action="{{ route('admin.contacts.index') }}" method="GET"
                        class="d-flex flex-wrap gap-2 align-items-center" id="filterForm">

                        <div class="input-group input-group-sm" style="width: 220px; margin-right: 5px;">
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control"
                                placeholder="Search name, email..." />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <select name="status" class="form-control form-control-sm" style="width: 140px; margin-left: 5px;"
                            onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="unread" {{ ($filters['status'] ?? '') == 'unread' ? 'selected' : '' }}>Unread
                            </option>
                            <option value="read" {{ ($filters['status'] ?? '') == 'read' ? 'selected' : '' }}>Read
                            </option>
                            <option value="replied" {{ ($filters['status'] ?? '') == 'replied' ? 'selected' : '' }}>Replied
                            </option>
                        </select>

                        @if (!empty($filters['search']) || !empty($filters['status']))
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-default">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="col"></div>

                <div class="col-12 col-md-auto d-flex flex-wrap gap-2 align-items-center">

                    <a href="{{ route(
                        'admin.contacts.export.excel',
                        array_filter([
                            'search' => $filters['search'] ?? null,
                            'status' => $filters['status'] ?? null,
                        ]),
                    ) }}"
                        class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>

                    <a href="{{ route(
                        'admin.contacts.export.pdf',
                        array_filter([
                            'search' => $filters['search'] ?? null,
                            'status' => $filters['status'] ?? null,
                        ]),
                    ) }}"
                        class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>

                    @canEdit
                    <div class="input-group input-group-sm">
                        <select id="bulkActionSelect" class="form-control form-control-sm">
                            <option value="">Bulk Action</option>
                            <option value="mark_read">Mark as Read</option>
                            <option value="delete">Delete</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-warning btn-sm" onclick="applyBulkAction()">
                                Apply
                            </button>
                        </div>
                    </div>
                    @endcanEdit

                </div>

            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title mb-0">
                <i class="fas fa-envelope mr-2"></i> Messages
            </h3>
            <span class="badge badge-primary">{{ $contacts->total() }} total</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                {{-- IMPORTANT: DataTable ID --}}
                <table class="table table-hover table-sm mb-0" id="contactsTable">

                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" />
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr class="{{ $contact->status == 'unread' ? 'font-weight-bold' : '' }}">
                                <td>
                                    <input type="checkbox" class="contact-checkbox" value="{{ $contact->id }}" />
                                </td>

                                <td>
                                    @if ($contact->status == 'unread')
                                        <span class="badge badge-danger mr-1">New</span>
                                    @endif
                                    {{ $contact->name }}
                                </td>

                                <td>
                                    <a href="mailto:{{ $contact->email }}" class="text-info">
                                        {{ $contact->email }}
                                    </a>
                                </td>

                                <td class="text-muted">
                                    {{ Str::limit($contact->message, 40) ?? '—' }}
                                </td>

                                <td>
                                    <span class="badge badge-{{ $contact->status_badge }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                </td>

                                <td class="text-muted small">
                                    {{ $contact->created_at->diffForHumans() }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @canEdit
                                    <button type="button" class="btn btn-xs btn-danger"
                                        onclick="deleteSingleContact({{ $contact->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcanEdit
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Hidden Forms --}}
    <form id="bulkForm" action="{{ route('admin.contacts.bulk-action') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput">
        <input type="hidden" name="ids" id="bulkIdsInput">
    </form>

    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('extra_js')

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {

            if ($('#contactsTable').length) {

                $('#contactsTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    ordering: true,
                    searching: true,
                    paging: true,
                    info: true,
                    language: {
                        emptyTable: "No contact messages found"
                    }

                });

            }

        });
    </script>

    {{-- Your existing scripts (UNCHANGED) --}}
    <script>
        function toggleSelectAll(source) {
            document.querySelectorAll('.contact-checkbox').forEach(cb => cb.checked = source.checked);
        }

        function applyBulkAction() {
            const action = document.getElementById('bulkActionSelect').value;
            const ids = Array.from(document.querySelectorAll('.contact-checkbox:checked')).map(cb => cb.value);

            if (!action || ids.length === 0) return;

            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkIdsInput').value = JSON.stringify(ids);
            document.getElementById('bulkForm').submit();
        }

        function deleteSingleContact(id) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/contacts') }}/" + id;
            form.submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
