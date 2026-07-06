@extends('admin::layouts.app')

@section('page_title', 'Services Trash')

@section('page_actions')
    <a href="{{ route('admin.services.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Services
    </a>
@endsection

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-trash mr-2"></i> Deleted Services
            </h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>

                                <td>
                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}"
                                            style="width:50px;height:50px;object-fit:cover;">
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $service->title }}</strong>
                                    <br>
                                    <small>{{ \Illuminate\Support\Str::limit($service->description, 50) }}</small>
                                </td>

                                <td>{{ $service->deleted_at }}</td>

                                <td>
                                    <form action="{{ route('admin.services.restore', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Restore</button>
                                    </form>

                                    <form action="{{ route('admin.services.force-delete', $service->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete permanently?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">
                                    Trash is empty
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $services->links() }}
@endsection
