@extends('web::layouts.app')

@section('page_title', $service->title ?? 'Service')

@section('content')
    <div class="container py-5">
        <h1>{{ $service->title }}</h1>

        @if ($service->image)
            <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid">
        @endif

        <p>{{ $service->description }}</p>

        <div>
            {!! $service->content !!}
        </div>
    </div>
@endsection
