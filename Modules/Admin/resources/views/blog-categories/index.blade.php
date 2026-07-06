@extends('admin::layouts.app')
@section('page_title', 'Blog Categories')

@section('page_actions')
    <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Category
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-folder mr-2"></i> Blog Categories
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ count($categories) }} total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($cat->image)
                                        <img src="{{ asset('storage/' . $cat->image) }}"
                                            style="width:36px;height:36px;object-fit:cover;border-radius:6px;" />
                                    @else
                                        <div
                                            style="width:36px;height:36px;background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                            border-radius:6px;display:flex;align-items:center;
                                            justify-content:center;color:white;font-size:.8rem;">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    @endif
                                    <span class="font-weight-bold">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>
                                <span class="badge badge-info">{{ $cat->blogs_count }} posts</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $cat->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($cat->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.blog-categories.edit', $cat->id) }}"
                                    class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.blog-categories.destroy', $cat->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Delete {{ addslashes($cat->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No categories yet.
                                <a href="{{ route('admin.blog-categories.create') }}">Add one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
