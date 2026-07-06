@extends('admin::layouts.app')
@section('page_title', 'Media Details')

@section('extra_css')
    <style>
        .media-detail-image {
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
        }

        .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .tag-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #f3f4f6;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-right: 6px;
            margin-bottom: 6px;
        }
    </style>
@endsection

@section('admin_content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-primary mr-2"></i>
                    Media Details
                </h5>
                <div>
                    <a href="{{ route('admin.media.edit', $media->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if ($media->type === 'image')
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->alt_text }}"
                            class="img-fluid media-detail-image w-100">
                    @elseif($media->type === 'video')
                        <video controls class="w-100 rounded">
                            <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($media->type === 'document')
                        <div class="text-center py-5 bg-light rounded">
                            <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
                            <h5>{{ $media->name }}</h5>
                            <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank"
                                class="btn btn-primary mt-3">
                                <i class="fas fa-download mr-2"></i> Download File
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <small class="text-muted">File Name</small>
                        <p class="mb-0 font-weight-bold">{{ $media->file_name }}</p>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">Title</small>
                        <p class="mb-0">{{ $media->title ?? 'Not set' }}</p>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">Alt Text</small>
                        <p class="mb-0">{{ $media->alt_text ?? 'Not set' }}</p>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">Description</small>
                        <p class="mb-0">{{ $media->description ?? 'No description' }}</p>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">File Info</small>
                        <p class="mb-0">
                            <span class="badge badge-info">{{ strtoupper($media->extension) }}</span>
                            <span class="ml-2">{{ $media->formatted_size }}</span>
                            @if ($media->width)
                                <span class="ml-2">{{ $media->width }} × {{ $media->height }} pixels</span>
                            @endif
                        </p>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">Tags</small>
                        <div class="mt-2">
                            @if ($media->tags)
                                @foreach ($media->tags as $tag)
                                    <span class="tag-badge">#{{ $tag }}</span>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No tags</p>
                            @endif
                        </div>
                    </div>

                    <div class="info-row">
                        <small class="text-muted">Uploaded</small>
                        <p class="mb-0">{{ $media->created_at->format('F d, Y h:i A') }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank"
                            class="btn btn-info btn-block">
                            <i class="fas fa-external-link-alt mr-2"></i> View Full Size
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
