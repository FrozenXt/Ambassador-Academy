<!doctype html>
<html lang="en">

<head>
    @php
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
    @endphp

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? $siteSettings->getByKey('meta_title', "Papa's Bar and Grill") }}</title>

    <meta name="description" content="{{ $pageDescription ?? $siteSettings->getByKey('meta_description', '') }}">
    <meta name="keywords" content="{{ $pageKeywords ?? $siteSettings->getByKey('meta_keywords', '') }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title"
        content="{{ $pageTitle ?? $siteSettings->getByKey('meta_title', "Papa's Bar and Grill") }}">
    <meta property="og:description" content="{{ $pageDescription ?? $siteSettings->getByKey('meta_description', '') }}">
    <meta property="og:type" content="website">

    <!-- ── Favicon (dynamic) ── -->
    @php
        $faviconUrl = $siteSettings->getByKey('site_favicon')
            ? Storage::url($siteSettings->getByKey('site_favicon'))
            : asset('image/favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
    <!-- AOS - Animate on Scroll -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,500&family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Jost:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400&display=swap" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />

    <!-- ── Google Analytics (gtag.js) ── -->
    @if ($siteSettings->getByKey('google_analytics'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->getByKey('google_analytics') }}">
        </script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $siteSettings->getByKey('google_analytics') }}');
        </script>
    @endif

    <!-- ── Custom Header Scripts (raw HTML/JS from admin) ── -->
    {!! $siteSettings->getByKey('header_scripts', '') !!}
</head>

<body>
    @php
        $logoUrl = $siteSettings->getByKey('site_logo')
            ? Storage::url($siteSettings->getByKey('site_logo'))
            : asset('image/logo.png');
    @endphp

    <div id="main">
        <nav class="site-nav" aria-label="Main navigation">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="nav-inner">
                        <div class="nav-logo">
                            <a href="{{ route('home') }}" class="logo-badge">
                                <img src="{{ $logoUrl }}"
                                    alt="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}"
                                    title="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}" />
                            </a>
                        </div>
                    </div>
                    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar" aria-hidden="true">
            <div class="sidebar-head">
                <a href="{{ route('home') }}" class="sidebar-logo">
                    <img src="{{ $logoUrl }}"
                        alt="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}"
                        title="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}" />
                </a>
                <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">
                    <iconify-icon icon="mingcute:close-fill"></iconify-icon>
                </button>
            </div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('menu') }}">Menu</a></li>
                <li><a href="{{ route('gallery') }}">Gallery</a></li>
                <li><a href="{{ route('contact') }}">Contacts</a></li>
            </ul>
            <p class="sidebar-footer">Luxury Arabic Dining · Kathmandu</p>
        </aside>
