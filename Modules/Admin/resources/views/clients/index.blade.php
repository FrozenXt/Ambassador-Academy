@extends('admin::layouts.app')
@section('page_title', 'Clients')

@section('page_actions')
    @canCreate
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Client
    </a>
    @endcanCreate
@endsection

@section('admin_content')

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="small-box bg-gradient-info mb-0">
                <div class="inner">
                    <h3>{{ $statistics['total'] }}</h3>
                    <p>Total Clients</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="small-box bg-gradient-success mb-0">
                <div class="inner">
                    <h3>{{ $statistics['active'] }}</h3>
                    <p>Active</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="small-box bg-gradient-warning mb-0">
                <div class="inner">
                    <h3>{{ $statistics['verified'] }}</h3>
                    <p>Verified</p>
                </div>
                <div class="icon"><i class="fas fa-user-shield"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="small-box bg-gradient-danger mb-0">
                <div class="inner">
                    <h3>{{ $statistics['inactive'] }}</h3>
                    <p>Inactive</p>
                </div>
                <div class="icon"><i class="fas fa-user-times"></i></div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body py-2">
            <form action="{{ route('admin.clients.index') }}" method="GET"
                class="d-flex flex-wrap gap-2 align-items-center">

                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    class="form-control form-control-sm" style="max-width:220px;"
                    placeholder="Search name, email, code..." />

                <select name="client_type" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="individual" {{ ($filters['client_type'] ?? '') == 'individual' ? 'selected' : '' }}>
                        Individual</option>
                    <option value="business" {{ ($filters['client_type'] ?? '') == 'business' ? 'selected' : '' }}>
                        Business</option>
                    <option value="government" {{ ($filters['client_type'] ?? '') == 'government' ? 'selected' : '' }}>
                        Government</option>
                    <option value="nonprofit" {{ ($filters['client_type'] ?? '') == 'nonprofit' ? 'selected' : '' }}>
                        Non-Profit</option>
                </select>

                <select name="is_active" class="form-control form-control-sm" style="max-width:130px;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="is_verified" class="form-control form-control-sm" style="max-width:140px;"
                    onchange="this.form.submit()">
                    <option value="">All Verified</option>
                    <option value="1" {{ ($filters['is_verified'] ?? '') === '1' ? 'selected' : '' }}>Verified
                    </option>
                    <option value="0" {{ ($filters['is_verified'] ?? '') === '0' ? 'selected' : '' }}>Unverified
                    </option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>

                {{-- Bulk action --}}
                <div class="ml-auto d-flex gap-2">
                    <select id="bulkAction" class="form-control form-control-sm" style="max-width:160px;">
                        <option value="">Bulk Action</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="button" class="btn btn-warning btn-sm" onclick="applyBulk()">
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-2"></i> All Clients
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $clients->total() }} total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulkForm" action="{{ route('admin.clients.bulk-action') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="bulkActionInput" />
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll" onclick="toggleAll(this)" />
                            </th>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Industry</th>
                            <th>Country</th>
                            <th>Verified</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $client->id }}" class="client-cb" />
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-gradient-primary text-white
                                            d-flex align-items-center justify-content-center
                                            font-weight-bold flex-shrink-0"
                                            style="width:38px;height:38px;font-size:.85rem;">
                                            {{ $client->initials }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                {{ $client->display_name }}
                                            </div>
                                            <small class="text-muted">
                                                <code>{{ $client->client_code }}</code>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <a href="mailto:{{ $client->email }}">
                                            {{ $client->email }}
                                        </a>
                                    </div>
                                    @if ($client->phone)
                                        <div class="small text-muted">
                                            {{ $client->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $client->client_type_badge }}">
                                        {{ $client->client_type_label }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $client->industry_type ?? '—' }}</small>
                                </td>
                                <td>
                                    @if ($client->country)
                                        <small>
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            {{ $client->country }}
                                        </small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($client->is_verified)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i> Verified
                                        </span>
                                    @else
                                        <form action="{{ route('admin.clients.verify', $client->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-primary">
                                                Verify
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-xs btn-{{ $client->is_active ? 'success' : 'secondary' }}">
                                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @canEdit
                                    <a href="{{ route('admin.clients.edit', $client->id) }}"
                                        class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit
                                    @canDelete
                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete {{ addslashes($client->display_name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcanDelete
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-users fa-3x d-block mb-3 opacity-25"></i>
                                    No clients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Showing {{ $clients->firstItem() }}–{{ $clients->lastItem() }}
                of {{ $clients->total() }} clients
            </small>
            {{ $clients->links() }}
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function toggleAll(cb) {
            document.querySelectorAll('.client-cb').forEach(function(c) {
                c.checked = cb.checked;
            });
        }

        function applyBulk() {
            var action = document.getElementById('bulkAction').value;
            if (!action) {
                alert('Select a bulk action.');
                return;
            }

            var checked = document.querySelectorAll('.client-cb:checked');
            if (!checked.length) {
                alert('Select at least one client.');
                return;
            }

            if (action === 'delete' && !confirm('Delete selected clients?')) return;

            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkForm').submit();
        }
    </script>
@endsection
