@extends('admin::layouts.app')
@section('page_title', 'Admission Applications')

@section('admin_content')

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="small-box bg-info mb-0">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total</p>
                </div>
                <div class="icon"><i class="fas fa-file-signature"></i></div>
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
                <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                    <form action="{{ route('admin.applications.index') }}" method="GET" id="filterForm">
                        <div class="form-row align-items-center">
                            <div class="col-12 col-sm-5 mb-2 mb-sm-0">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                        class="form-control" placeholder="Search name, email, phone..." />
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
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

                            <div class="col-6 col-sm-2 mb-2 mb-sm-0">
                                <select name="applying_for" class="form-control form-control-sm"
                                    onchange="this.form.submit()">
                                    <option value="">All Grades</option>
                                    @foreach (['Pre-KG', 'KG', 'Grade 1 - 5', 'Grade 6 - 8', 'Grade 9 - 12'] as $grade)
                                        <option value="{{ $grade }}"
                                            {{ ($filters['applying_for'] ?? '') == $grade ? 'selected' : '' }}>
                                            {{ $grade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['applying_for']))
                                <div class="col-12 col-sm-2">
                                    <a href="{{ route('admin.applications.index') }}"
                                        class="btn btn-sm btn-default btn-block">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Bulk actions --}}
                <div class="col-12 col-lg-4">
                    <div class="d-flex flex-wrap justify-content-lg-end align-items-center">
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
                <i class="fas fa-file-signature mr-2"></i> Applications
            </h3>
            <span class="badge badge-primary">{{ $applications->total() }} total</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover table-sm mb-0" id="applicationsTable">

                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" />
                            </th>
                            <th>Student</th>
                            <th>Applying For</th>
                            <th>Guardian</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($applications as $application)
                            <tr class="{{ $application->status == 'unread' ? 'font-weight-bold' : '' }}">
                                <td>
                                    <input type="checkbox" class="application-checkbox" value="{{ $application->id }}" />
                                </td>

                                <td>
                                    @if ($application->status == 'unread')
                                        <span class="badge badge-danger mr-1">New</span>
                                    @endif
                                    {{ $application->student_name }}
                                </td>

                                <td>
                                    <span class="badge badge-secondary">{{ $application->applying_for }}</span>
                                </td>

                                <td>{{ $application->guardian_name }}</td>

                                <td>
                                    <a href="mailto:{{ $application->email }}" class="text-info">
                                        {{ $application->email }}
                                    </a>
                                </td>

                                <td class="text-muted">{{ $application->phone }}</td>

                                <td>
                                    <span class="badge badge-{{ $application->status_badge }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>

                                <td class="text-muted small">
                                    {{ $application->created_at->diffForHumans() }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.applications.edit', $application->id) }}"
                                        class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @canEdit
                                    <button type="button" class="btn btn-xs btn-danger"
                                        onclick="deleteSingleApplication({{ $application->id }})">
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
    <form id="bulkForm" action="{{ route('admin.applications.bulk-action') }}" method="POST" style="display:none;">
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
        #applicationsTable_wrapper {
            display: block;
            padding: 0.9rem 1rem;
        }

        #applicationsTable_wrapper .dataTables_length,
        #applicationsTable_wrapper .dataTables_filter {
            margin-bottom: 0.85rem;
        }

        #applicationsTable_wrapper .dataTables_info,
        #applicationsTable_wrapper .dataTables_paginate {
            margin-top: 0.85rem;
        }

        #applicationsTable_wrapper .dataTables_length select {
            display: inline-block;
            width: auto;
            margin: 0 0.35rem;
        }

        #applicationsTable_wrapper>.table-responsive {
            margin: 0;
        }

        @media (max-width: 575.98px) {

            #applicationsTable_wrapper .dataTables_length,
            #applicationsTable_wrapper .dataTables_filter,
            #applicationsTable_wrapper .dataTables_info,
            #applicationsTable_wrapper .dataTables_paginate {
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
            if ($('#applicationsTable').length) {
                $('#applicationsTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    ordering: true,
                    searching: true,
                    paging: true,
                    info: true,
                    language: {
                        emptyTable: "No applications found"
                    }
                });
            }
        });
    </script>

    <script>
        function toggleSelectAll(source) {
            document.querySelectorAll('.application-checkbox').forEach(cb => cb.checked = source.checked);
        }

        function applyBulkAction() {
            const action = document.getElementById('bulkActionSelect').value;
            const ids = Array.from(document.querySelectorAll('.application-checkbox:checked')).map(cb => cb.value);

            if (!action || ids.length === 0) return;

            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkIdsInput').value = JSON.stringify(ids);
            document.getElementById('bulkForm').submit();
        }

        function deleteSingleApplication(id) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/applications') }}/" + id;
            form.submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
