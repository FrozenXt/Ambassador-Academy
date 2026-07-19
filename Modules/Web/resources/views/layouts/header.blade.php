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
                            <svg class="logo-shape" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="317" height="179"
                                viewBox="0 0 317 179" fill="none">
                                <rect y="-4" width="178" height="183" fill="url(#pattern0_1_30)" />
                                <rect width="178" height="183" transform="matrix(-1 0 0 1 317 -4)"
                                    fill="url(#pattern1_1_30)" />
                                <defs>
                                    <pattern id="pattern0_1_30" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_1_30" transform="scale(0.00689655 0.01)" />
                                    </pattern>
                                    <pattern id="pattern1_1_30" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_1_30" transform="scale(0.00689655 0.01)" />
                                    </pattern>
                                    <image id="image0_1_30" width="146" height="80" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJIAAABQCAYAAADyWywxAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAANxSURBVHgB7d3vVRNBFAXwGyuwA5cKwAqMFQgViBWIFRgrUCtQK5AOpAPoQDqQDuK77htcYxISsjM7f+7vnD0LBz7ec99kMpvMYJbL5VO7dXbxfmzXqV0n/rvIg2bb/mgBO7fba7vmENlitss/WaA6uy3Qh0rkPzsFKbBAcdx9Rz8GRe492eefZ7PZjV1H9uMHiAzs1UhD1k4XdnsPLcgFBwSJfNT9gMLUvIOCRAqT0MFBIoVJ9lpsb8JFOLQAb9oojRRYM32y21tIc8YOEkfbNbTP1JxRRltgI+7Obm8gzRk1SGRhurLbN0hTRh1tgb839xPSjNEbiayVbqFWakqURiLfW7qGNCFKI5HvLV1BmhAtSE6blI2INtoCG3G/oLdOqhe7kegzpHopGoltxK0AtVLFojeS73arlSoXvZFIrVS/FGsktVIDkjQSqZXqlqSRSK1Ut2SNRGqleiVrJFIr1StpI5FOUdYpaSORt9I7SFWSN1JgzcTHl+aQKkwZpA79iNPCuwLJR1vgpyi18K7EZI0UWDOxlU4gRZuskQb0+FIFJg+SHveuw+SjLdCIK1sOoy04s+sOUqRsguSv4jTiCpXNaAv0iSZlyjFI3KDkrrfWSwXJLkikXe/y5LTYvufrpTNIMbIMEvnH4+iUQCGyDRJZmLjw1iu5AmS5Rlpla6av0PegZK2IIJHOL+Ut69G2govvG0iWimkk0h5TvooKEunhgTyVNNr+8IcHnkNjLivFNVKgMZeX4hop8GZ6adclZHLFBokYJrv4ak4PEUys6CAFFqYLaAd8UsWukdbxr0f9CEmuqiCRvgl8GtUFifw8E8OkV3SJVLFGWsXzTHZxr0nrpkSqbKQha6dz9F8r30GiqT5I5KPuC3R6IJoqR9sqH3XcvNSoi6SJRhryduJbKx1kNE000pC30xH68+B6snckzTXSkLfTAjrGe7CmgxRYoE7R74h3kEdpbrStY6Pu0scdP6vpFrI3NdIa1lAL9OOug+xEQdrA10/nUKB2oiA9wAM1h3bHt1KQ9qC3WzZTkB7BAjXH37EnUJAOMhh7/GCwpo+sKEgj8VDxhOYrNDj6FKQI/JTmC/Tjr4mmUpAiG4w/XgxXhwopSIkNghVaq4rGUpAy4K8C+eQw78f+c1EBU5Ay5u3FaxisZ/h3PHbIwG9l9/F38bO06AAAAABJRU5ErkJggg==" />
                                </defs>
                            </svg>
                            <a href="{{ route('home') }}" class="logo-badge">
                                <img src="{{ $logoUrl }}"
                                    alt="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}"
                                    title="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}" />
                            </a>
                        </div>
                    </div>
                    <!-- ░░░ HAMBURGER (mobile) ░░░ -->
                    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- ░░░ SIDEBAR ░░░ -->
        <aside class="sidebar" id="sidebar" aria-hidden="true">
            <div class="sidebar-head">
                <div class="sidebar-logo">
                    <img src="{{ $logoUrl }}"
                        alt="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}"
                        title="{{ $siteSettings->getByKey('site_name', "Papa's Bar and Grill") }}" />
                </div>
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
