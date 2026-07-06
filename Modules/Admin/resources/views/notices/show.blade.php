@extends('admin::layouts.app')
@section('page_title', 'View Notice')

@section('page_actions')
    <a href="{{ route('admin.notices.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit mr-1"></i> Edit</a>
@endsection

@section('admin_content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-eye mr-2"></i> Notice Details</h3>
        </div>
        <div class="card-body">

            {{-- Title & Type --}}
            <div class="mb-3">
                <h4>{{ $notice->title }}</h4>
                <span class="badge badge-{{ $notice->type == 'news' ? 'info' : 'primary' }}">
                    {{ ucfirst($notice->type) }}
                </span>
                @if ($notice->is_featured)
                    <span class="badge badge-warning ml-1">Featured</span>
                @endif
            </div>

            {{-- Dates --}}
            <div class="mb-3">
                <p>
                    <strong>Published Date:</strong>
                    {{ $notice->published_date ? $notice->published_date->format('d M Y') : 'N/A' }}
                </p>
                <p>
                    <strong>Expiry Date:</strong>
                    {{ $notice->expiry_date ? $notice->expiry_date->format('d M Y') : 'N/A' }}
                </p>
            </div>

            {{-- Priority & Status --}}
            <div class="mb-3">
                <p>
                    <strong>Priority:</strong> {{ ucfirst($notice->priority) }}
                </p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $notice->status == 'published' ? 'success' : 'secondary' }}">
                        {{ ucfirst($notice->status) }}
                    </span>
                </p>
            </div>

            {{-- Tags --}}
            @if ($notice->tags)
                <div class="mb-3">
                    <strong>Tags:</strong>
                    @foreach (explode(',', $notice->tags) as $tag)
                        <span class="badge badge-info">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif

            {{-- Featured Image --}}
            @if ($notice->featured_image)
                <div class="mb-3">
                    <strong>Featured Image:</strong>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $notice->featured_image) }}" alt="{{ $notice->title }}"
                            style="max-width: 300px; border-radius: 5px;">
                    </div>
                </div>
            @endif

            {{-- Short Description --}}
            @if ($notice->short_description)
                <div class="mb-3">
                    <strong>Short Description:</strong>
                    <p>{{ $notice->short_description }}</p>
                </div>
            @endif

            {{-- Full Content --}}
            <div class="mb-3">
                <strong>Content:</strong>
                <div class="border p-3 rounded" style="background:#f9f9f9;">
                    {!! $notice->content !!}
                </div>
            </div>

        </div>
    </div>
@endsection
