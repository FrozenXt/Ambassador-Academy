@extends('admin::layouts.app')
@section('page_title', 'View Testimonial')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <a href="{{ route('admin.testimonials.edit', ['id' => $testimonial->id]) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit mr-1"></i> Edit
    </a>
@endsection

@section('admin_content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-eye mr-2"></i> Testimonial Details
            </h3>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Left Content --}}
                <div class="col-md-8">

                    <div class="mb-3">
                        <h4 class="font-weight-bold">{{ $testimonial->name }}</h4>
                        <p class="text-muted mb-1">
                            {{ $testimonial->position ?? '-' }}
                            @if ($testimonial->company)
                                at {{ $testimonial->company }}
                            @endif
                        </p>
                    </div>

                    {{-- Rating --}}
                    <div class="mb-3">
                        <label class="font-weight-bold">Rating:</label><br>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $testimonial->rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-muted">({{ $testimonial->rating }}/5)</span>
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="font-weight-bold">Testimonial:</label>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($testimonial->content)) !!}
                        </div>
                    </div>

                </div>

                {{-- Right Sidebar --}}
                <div class="col-md-4">

                    {{-- Avatar --}}
                    <div class="text-center mb-4">
                        @if ($testimonial->avatar)
                            <img src="{{ asset($testimonial->avatar) }}" class="img-circle elevation-2"
                                style="width:100px;height:100px;object-fit:cover;">
                        @else
                            <div class="img-circle bg-gradient-primary text-white
                            d-flex align-items-center justify-content-center
                            font-weight-bold mx-auto"
                                style="width:100px;height:100px;font-size:2rem;">
                                {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Meta Info --}}
                    <ul class="list-group mb-3">

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Status</span>
                            <span class="badge badge-{{ $testimonial->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($testimonial->status) }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Featured</span>
                            <span>
                                @if ($testimonial->is_featured)
                                    <span class="badge badge-primary">Yes</span>
                                @else
                                    <span class="badge badge-light">No</span>
                                @endif
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Order</span>
                            <span>{{ $testimonial->order }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Created</span>
                            <span>
                                {{ optional($testimonial->created_at)->format('d M Y, h:i A') ?? '-' }}
                            </span>
                        </li>

                    </ul>

                </div>

            </div>

        </div>
    </div>

@endsection
