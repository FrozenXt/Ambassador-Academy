@extends('admin::layouts.app')
@section('page_title', 'Email Settings')

@section('page_actions')
    @canCreate
    <a href="{{ route('admin.email-settings.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Configuration
    </a>
    @endcanCreate

@endsection

@section('admin_content')

    {{-- Active Config Banner --}}
    @if ($active)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Active Config:</strong>
            {{ $active->from_name }} &lt;{{ $active->from_address }}&gt;
            via <strong>{{ $active->mailer_label }}</strong>
            ({{ $active->host }}:{{ $active->port }})
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            No active email configuration. Please add and activate one.
        </div>
    @endif

    {{-- Configs Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope-open-text mr-2"></i>
                Email Configurations
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $settings->count() }} configs</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Name / From</th>
                        <th>Mailer</th>
                        <th>Host</th>
                        <th>Port</th>
                        <th>Encryption</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $s)
                        <tr class="{{ $s->is_active ? 'table-success' : '' }}">
                            <td>
                                <div class="font-weight-bold">{{ $s->from_name }}</div>
                                <small class="text-muted">{{ $s->from_address }}</small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $s->mailer_label }}</span>
                            </td>
                            <td>
                                <code>{{ $s->host }}</code>
                            </td>
                            <td>
                                <code>{{ $s->port }}</code>
                            </td>
                            <td>
                                <span class="badge badge-{{ $s->encryption_badge }}">
                                    {{ strtoupper($s->encryption) }}
                                </span>
                            </td>
                            <td>
                                @if ($s->is_active)
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fas fa-check mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">

                                    {{-- Set Active --}}
                                    @if (!$s->is_active)
                                        <form action="{{ route('admin.email-settings.set-active', $s->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-xs btn-success" title="Set Active">
                                                <i class="fas fa-check"></i> Activate
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Write to .env --}}
                                    <form action="{{ route('admin.email-settings.write-env', $s->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Write these settings to .env file?')">
                                        @csrf
                                        <button class="btn btn-xs btn-info" title="Save to .env">
                                            <i class="fas fa-file-code"></i> .env
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    @canEdit
                                    <a href="{{ route('admin.email-settings.edit', $s->id) }}"
                                        class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcanEdit

                                    {{-- Delete --}}
                                    @canDelete
                                    @if (!$s->is_active)
                                        <form action="{{ route('admin.email-settings.destroy', $s->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this email configuration?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @endcanDelete

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-envelope fa-3x d-block mb-3 opacity-25"></i>
                                No email configurations yet.
                                @canCreate
                                <a href="{{ route('admin.email-settings.create') }}">Add one now</a>
                                @endcanCreate
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Test Email (for active config) --}}
    @if ($active)
        <div class="card card-outline card-warning mt-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Test Active Configuration
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email-settings.test', $active->id) }}" method="POST">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Send Test Email To</label>
                                <input type="email" name="test_email"
                                    value="{{ old('test_email', session('admin_email')) }}"
                                    class="form-control @error('test_email') is-invalid @enderror"
                                    placeholder="test@example.com" />
                                @error('test_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Test Email
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    This sends a test email using the currently active configuration to verify it's working correctly.
                </small>
            </div>
        </div>
    @endif

@endsection
