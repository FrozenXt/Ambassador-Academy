@extends('admin::layouts.app')
@section('title', $post->title)

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-8">
                <h1 class="m-0">{{ $post->title }}</h1>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($post->image)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                        style="max-width:100%;max-height:300px;object-fit:cover;border-radius:6px;" class="mb-3">
                @endif

                <dl class="row">
                    <dt class="col-sm-3">Subtitle</dt>
                    <dd class="col-sm-9">{{ $post->subtitle ?: '—' }}</dd>

                    <dt class="col-sm-3">Slug</dt>
                    <dd class="col-sm-9"><code>{{ $post->slug }}</code></dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        <span class="badge badge-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">Featured</dt>
                    <dd class="col-sm-9">{{ $post->is_featured ? 'Yes' : 'No' }}</dd>

                    <dt class="col-sm-3">Published At</dt>
                    <dd class="col-sm-9">{{ $post->published_at?->format('Y-m-d H:i') ?? '—' }}</dd>

                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $post->description ?: '—' }}</dd>

                    <dt class="col-sm-3">Content</dt>
                    <dd class="col-sm-9">{!! nl2br(e($post->content)) !!}</dd>

                    <dt class="col-sm-3">Meta Title</dt>
                    <dd class="col-sm-9">{{ $post->meta_title ?: '—' }}</dd>

                    <dt class="col-sm-3">Meta Description</dt>
                    <dd class="col-sm-9">{{ $post->meta_description ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
