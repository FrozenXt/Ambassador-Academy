@extends('admin::layouts.app')
@section('page_title', 'Contact Messages')

@section('admin_content')

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="small-box bg-info mb-0">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total</p>
                </div>
                <div class="icon"><i class="fas fa-envelope"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3 mb-md-0">
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
        <div class="card-body">
            <div class="row align-items-center">

                {{-- Search + Status filter --}}
                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                    <form action="{{ route('admin.contacts.index') }}" method="GET" id="filterForm">
                        <div class="form-row align-items-center">
                            <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                        class="form-control" placeholder="Search name, email..." />
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-8 col-sm-4 mb-2 mb-sm-0">
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="unread" {{ ($filters['status'] ?? '') == 'unread' ? 'selected' : '' }}>
                                        Unread
                                    </option>
                                    <option value="read" {{ ($filters['status'] ?? '') == 'read' ? 'selected' : '' }}>
                                        Read
                                    </option>
                                    <option value="replied" {{ ($filters['status'] ?? '') == 'replied' ? 'selected' : '' }}>
                                        Replied
                                    </option>
                                </select>
                            </div>

                            @if (!empty($filters['search']) || !empty($filters['status']))
                                <div class="col-4 col-sm-2">
                                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-default btn-block">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Export + Bulk actions --}}
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-wrap justify-content-lg-end align-items-center">

                        <div class="btn-group btn-group-sm mr-2 mb-2 mb-lg-0" role="group">
                            <a href="{{ route(
                                'admin.contacts.export.excel',
                                array_filter([
                                    'search' => $filters['search'] ?? null,
                                    'status' => $filters['status'] ?? null,
                                ]),
                            ) }}"
                                class="btn btn-success">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </a>

                            <a href="{{ route(
                                'admin.contacts.export.pdf',
                                array_filter([
                                    'search' => $filters['search'] ?? null,
                                    'status' => $filters['status'] ?? null,
                                ]),
                            ) }}"
                                class="btn btn-danger">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </a>
                        </div>

                        @canEdit
                        <div class="input-group input-group-sm mb-2 mb-lg-0" style="width: 220px;">
                            <select id="bulkActionSelect" class="form-control">
                                <option value="">Bulk Action</option>
                                <option value="mark_read">Mark as Read</option>
                                <option value="delete">Delete</option>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-warning" onclick="applyBulkAction()">
                                    Apply
                                </button>
                            </div>
                        </div>
                        @endcanEdit

                    </div>
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
                            <th style="width: 100px;">Actions</th>
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

@section('extra_css')
    <style>
        /*
                     * card-body is p-0 so the raw table sits flush against the card.
                     * DataTables injects its "Show entries" (length), search, info,
                     * and pagination controls as siblings above/below the table inside
                     * #contactsTable_wrapper, so with no card padding they end up
                     * glued to the card edges and to the table itself. Give the
                     * wrapper its own horizontal padding, and add vertical spacing
                     * around just the length/filter/info/paginate rows — leave the
                     * table itself untouched so column widths don't shift.
                     */
        #contactsTable_wrapper {
            display: block;
            padding: 0.9rem 1rem;
        }

        #contactsTable_wrapper .dataTables_length,
        #contactsTable_wrapper .dataTables_filter {
            margin-bottom: 0.85rem;
        }

        #contactsTable_wrapper .dataTables_info,
        #contactsTable_wrapper .dataTables_paginate {
            margin-top: 0.85rem;
        }

        #contactsTable_wrapper .dataTables_length select {
            display: inline-block;
            width: auto;
            margin: 0 0.35rem;
        }

        /* table-responsive + table already provide their own scroll/width;
                       make sure the wrapper doesn't add a second layer of margin */
        #contactsTable_wrapper>.table-responsive {
            margin: 0;
        }

        @media (max-width: 575.98px) {

            #contactsTable_wrapper .dataTables_length,
            #contactsTable_wrapper .dataTables_filter,
            #contactsTable_wrapper .dataTables_info,
            #contactsTable_wrapper .dataTables_paginate {
                text-align: left;
                float: none !important;
            }
        }
    </style>
@endsection

@section('extra_js')

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
