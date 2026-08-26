<!doctype html>
<html lang="en">

<head>
    @php
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? $siteSettings->getByKey('meta_title', 'Ambassador School | Inspire · Innovate · Achieve') }}
    </title>
    <meta name="description"
        content="{{ $pageDescription ?? $siteSettings->getByKey('meta_description', 'Ambassador School - Nurturing young minds, building bright futures.') }}">
    <meta name="keywords" content="{{ $pageKeywords ?? $siteSettings->getByKey('meta_keywords', '') }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title"
        content="{{ $pageTitle ?? $siteSettings->getByKey('meta_title', 'Ambassador School | Inspire · Innovate · Achieve') }}">
    <meta property="og:description" content="{{ $pageDescription ?? $siteSettings->getByKey('meta_description', '') }}">
    <meta property="og:type" content="website">

    <!-- Favicon (dynamic) -->
    @php
        $faviconUrl = $siteSettings->getByKey('site_favicon')
            ? Storage::url($siteSettings->getByKey('site_favicon'))
            : asset('image/favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css">

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Site CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Google Analytics (gtag.js) -->
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

    <!-- Custom Header Scripts (raw HTML/JS from admin) -->
    {!! $siteSettings->getByKey('header_scripts', '') !!}
</head>

<body>
    @php
        $logoUrl = $siteSettings->getByKey('site_logo') ? Storage::url($siteSettings->getByKey('site_logo')) : null;
        $siteName = $siteSettings->getByKey('site_name', 'Ambassador School');
        $siteTagline = $siteSettings->getByKey('site_tagline', 'INSPIRE · INNOVATE · ACHIEVE');
        $topbarMessage = $siteSettings->getByKey('topbar_message', 'Welcome to Ambassador School');
        $phone = $siteSettings->getByKey('phone');
        $email = $siteSettings->getByKey('email');
        $facebook = $siteSettings->getByKey('facebook_url');
        $twitter = $siteSettings->getByKey('twitter_url');
        $instagram = $siteSettings->getByKey('instagram_url');
        $youtube = $siteSettings->getByKey('youtube_url');
    @endphp

    <!-- ============ PRELOADER ============ -->
    @php
        $logoUrl = $siteSettings->getByKey('site_logo') ? Storage::url($siteSettings->getByKey('site_logo')) : null;
    @endphp

    <div class="preloader" id="preloader">
        <div class="preloader-shield">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Site Logo">
            @else
                <i class="fa-solid fa-shield-halved"></i>
            @endif
        </div>
    </div>

    <!-- ============ TOP BAR ============ -->
    <div class="topbar">
        <div class="container topbar-inner">
            <p class="topbar-msg"><i class="fa-solid fa-bullhorn"></i> {{ $topbarMessage }}</p>
            <div class="topbar-right">
                <ul class="topbar-links">
                    @if ($phone)
                        <li><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><i class="fa-solid fa-phone"></i>
                                {{ $phone }}</a></li>
                    @endif
                    @if ($email)
                        <li><a href="mailto:{{ $email }}"><i class="fa-solid fa-envelope"></i>
                                {{ $email }}</a></li>
                    @endif
                </ul>
                <div class="topbar-socials">
                    @if ($facebook)
                        <a href="{{ $facebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i
                                class="fa-brands fa-facebook-f"></i></a>
                    @endif
                    @if ($twitter)
                        <a href="{{ $twitter }}" target="_blank" rel="noopener" aria-label="Twitter"><i
                                class="fa-brands fa-twitter"></i></a>
                    @endif
                    @if ($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><i
                                class="fa-brands fa-instagram"></i></a>
                    @endif
                    @if ($youtube)
                        <a href="{{ $youtube }}" target="_blank" rel="noopener" aria-label="YouTube"><i
                                class="fa-brands fa-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============ HEADER (reused across pages) ============ -->
    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="logo">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" title="{{ $siteName }}"
                        class="logo-img">
                @else
                    <div class="logo-badge"><i class="fa-solid fa-shield-halved"></i></div>
                @endif
                <div class="logo-text">
                    <span class="logo-title">{{ $siteName }}</span>
                    <span class="logo-tagline">{{ $siteTagline }}</span>
                </div>
            </a>

            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="{{ route('home') }}"
                            class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About
                            Us</a></li>
                    <li><a href="{{ route('services') }}"
                            class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
                    <li><a href="{{ route('events') }}"
                            class="{{ request()->routeIs('events') ? 'active' : '' }}">Events</a></li>
                    <li><a href="{{ route('blog') }}"
                            class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <a href="{{ route('apply') }}" class="btn btn-primary btn-sm">Apply Now</a>
                <button class="icon-btn" id="searchToggle" aria-label="Search"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <div class="search-box" id="searchBox">
            <div class="container">
                <input type="text" placeholder="Search the site...">
                <button aria-label="Close search" id="searchClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    </header>
