@extends('admin::layouts.app')
@section('page_title', 'Blog Trash')

@section('page_actions')
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Posts
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-trash mr-2"></i> Trashed Blog Posts
            </h3>
            <div class="card-tools">
                <span class="badge badge-danger">{{ $blogs->count() }} in trash</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="font-weight-bold text-muted">{{ $blog->title }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $blog->category->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $blog->status == 'published' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($blog->status) }}
                                </span>
                            </td>
                            <td>{{ $blog->deleted_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <form action="{{ route('admin.blogs.restore', $blog->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-xs btn-success">
                                        <i class="fas fa-undo mr-1"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.blogs.force-delete', $blog->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Permanently delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger">
                                        <i class="fas fa-times mr-1"></i> Delete Forever
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                Trash is empty!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
