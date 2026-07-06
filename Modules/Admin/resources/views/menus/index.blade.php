@extends('admin::layouts.app')
@section('page_title', 'Menus')

@section('page_actions')
    @canCreate
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Add Menu
    </a>
    @endcanCreate
@endsection

@section('admin_content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bars mr-2"></i> All Menus
            </h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Location</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $i => $menu)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="font-weight-bold">{{ $menu->name }}</td>
                            <td><code>{{ $menu->slug }}</code></td>
                            <td>
                                @if ($menu->location)
                                    <span class="badge badge-info">{{ ucfirst($menu->location) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $menu->all_items_count }} items</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $menu->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($menu->status) }}
                                </span>
                            </td>
                            <td>
                                @canEdit
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i> Manage
                                </a>
                                @endcanEdit
                                @canDelete
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete {{ $menu->name }}?')">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                No menus yet.
                                @canCreate
                                <a href="{{ route('admin.menus.create') }}">Create one now</a>
                                @endcanCreate

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
