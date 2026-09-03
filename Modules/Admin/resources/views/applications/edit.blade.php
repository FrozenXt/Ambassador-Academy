@extends('admin::layouts.app')
@section('page_title', 'Application — ' . $application->student_name)

@section('admin_content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Back to Applications
        </a>
        <span class="badge badge-{{ $application->status_badge }} p-2">
            {{ ucfirst($application->status) }}
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- Application details --}}
        <div class="col-12 col-lg-7 mb-4 mb-lg-0">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-signature mr-2"></i> Student Details
                    </h3>
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small mb-1">Student Name</label>
                            <p class="font-weight-bold mb-0">{{ $application->student_name }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small mb-1">Applying For</label>
                            <p class="mb-0"><span class="badge badge-secondary">{{ $application->applying_for }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small mb-1">Date of Birth</label>
                            <p class="mb-0">{{ $application->dob->format('d M Y') }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small mb-1">Gender</label>
                            <p class="mb-0">{{ $application->gender }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small mb-1">Parent / Guardian</label>
                            <p class="font-weight-bold mb-0">{{ $application->guardian_name }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small mb-1">Submitted</label>
                            <p class="mb-0">{{ $application->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small mb-1">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                            </p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small mb-1">Phone</label>
                            <p class="mb-0">
                                <a href="tel:{{ $application->phone }}">{{ $application->phone }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small mb-1">Address</label>
                        <p class="mb-0">{{ $application->address }}</p>
                    </div>

                </div>
            </div>

            @if ($application->admin_reply)
                <div class="card card-outline card-success mt-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-reply mr-2"></i> Previous Reply
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">{{ $application->admin_reply }}</p>
                        <span class="text-muted small">
                            Sent {{ $application->replied_at?->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Actions sidebar --}}
        <div class="col-12 col-lg-5">

            @canEdit
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-paper-plane mr-2"></i> Send Reply
                    </h3>
                </div>
                <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Reply Message</label>
                            <textarea name="admin_reply" class="form-control" rows="6" placeholder="Write your reply to the guardian...">{{ old('admin_reply') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>

            <div class="card card-outline card-warning mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tag mr-2"></i> Update Status
                    </h3>
                </div>
                <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <select name="status" class="form-control">
                                <option value="unread" {{ $application->status == 'unread' ? 'selected' : '' }}>Unread
                                </option>
                                <option value="read" {{ $application->status == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="replied" {{ $application->status == 'replied' ? 'selected' : '' }}>Replied
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
            @endcanEdit

            @canEdit
            <div class="card card-outline card-danger mt-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Delete Application</h5>
                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="deleteApplication({{ $application->id }})">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </div>
            </div>
            @endcanEdit

        </div>
    </div>

    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteApplication(id) {
            Swal.fire({
                title: 'Delete this application?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = "{{ url('admin/applications') }}/" + id;
                    form.submit();
                }
            });
        }
    </script>
@endsection
