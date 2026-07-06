@extends('admin::layouts.app')

@section('page_title', 'Deleted Testimonials')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-danger">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-trash mr-2"></i> Trash Testimonials
            </h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Deleted At</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td>{{ $testimonial->id }}</td>

                                <td>
                                    @if ($testimonial->avatar)
                                        <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                            style="width:45px;height:45px;object-fit:cover;border-radius:50%;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width:45px;height:45px;border-radius:50%;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $testimonial->name }}</strong>
                                    @if ($testimonial->company)
                                        <br>
                                        <small class="text-muted">{{ $testimonial->company }}</small>
                                    @endif
                                </td>

                                <td>{{ $testimonial->position ?? '—' }}</td>

                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($testimonial->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $testimonial->deleted_at ? $testimonial->deleted_at->format('d M Y H:i') : '—' }}
                                </td>

                                <td class="d-flex">

                                    {{-- Restore --}}
                                    <form action="{{ route('admin.testimonials.restore', $testimonial->id) }}"
                                        method="POST" class="mr-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-trash-restore"></i> Restore
                                        </button>
                                    </form>

                                    {{-- Force Delete --}}
                                    <form action="{{ route('admin.testimonials.force-delete', $testimonial->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Permanently delete this testimonial?')"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                                    <p class="mb-0">No deleted testimonials found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
