@extends('admin::layouts.app')
@section('page_title', 'Trash — Pages')

@section('page_actions')
    <a href="{{ route('admin.pages.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Pages
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-trash mr-2"></i> Trashed Pages
            </h3>
            <div class="card-tools">
                <span class="badge badge-danger">{{ $pages->count() }} in trash</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td class="font-weight-bold text-muted">{{ $page->title }}</td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>
                                <span class="badge badge-{{ $page->status == 'published' ? 'success' : 'warning' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                            </td>
                            <td>{{ $page->deleted_at->format('d M Y, h:i A') }}</td>
                            <td>
                                {{-- Restore --}}
                                <form action="{{ route('admin.pages.restore', $page->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-xs btn-success" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                                {{-- Force Delete --}}
                                <form action="{{ route('admin.pages.force-delete', $page->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Permanently delete this page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger" title="Delete Permanently">
                                        <i class="fas fa-times"></i> Delete Forever
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
