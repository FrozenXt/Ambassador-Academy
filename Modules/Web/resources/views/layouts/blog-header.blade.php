<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $blog->meta_title ?? ($blog->title ?? 'MG Motor Nepal') }}</title>

    <meta name="description" content="{{ $blog->meta_description ?? ($blog->excerpt ?? '') }}">
    <meta name="keywords" content="{{ is_array($blog->tags ?? null) ? implode(', ', $blog->tags) : $blog->tags ?? '' }}">
    <meta name="author" content="{{ $blog->author->name ?? 'MG Motor Nepal' }}">
    <meta name="robots" content="index, follow">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                @if (!empty($settings['site_logo']->value))
                    <img src="{{ asset('storage/' . $settings['site_logo']->value) }}"
                        alt="{{ $settings['site_name']->value ?? 'MGS6' }}"
                        title="{{ $settings['site_name']->value ?? 'MGS6' }}">
                @else
                    <img src="images/logo.webp" alt="MGS6" title="MGS6">
                @endif
            </a>

            {{-- Brochure download from settings or static --}}
            <a href="{{ $settings['brochure_url']->value ?? 'MGS6-Brochure-20260119.pdf' }}" target="_blank"
                class="btn-premium-glass">Download Brochure</a>
        </div>
    </nav>
