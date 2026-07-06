<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title><?= $pageTitle ?? 'Sultan Arabic Restaurant' ?></title>

    <meta name="description" content="<?= $pageDescription ?? 'Default website description.' ?>">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index,follow">
    <meta name="rating" content="general">
    <meta name="abstract" content="Sultan Arabic Restaurant">
    <meta name="keywords" content="<?= $pageKeywords ?? 'keyword1, keyword2' ?>">
    <meta name="author" content="Sultan Arabic Restaurant">
    <meta name="geo.region" content="NP">
    <meta name="geo.placename" content="Kathmandu">
    <meta name="ICBM" content="27.7172,85.3240">
    <link rel="canonical" href="<?= $canonicalUrl ?? 'https://dev.sultansarabicrestro.com' ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= $pageTitle ?? 'Sultan Arabic Restaurant' ?>">
    <meta property="og:description" content="<?= $pageDescription ?? '' ?>">
    <meta property="og:type" content="website">
    <!-- Bootstrap 5 -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- ── Google reCAPTCHA v2 ── -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <nav class="site-nav" aria-label="Main navigation">
        <div class="nav-inner">
            <ul class="nav-links">
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                        About
                    </a>
                </li>

                <li>
                    <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                        Menu
                    </a>
                </li>
                <div class="nav-logo">
                    <a href="{{ route('home') }}" class="logo-badge">
                        <img src="{{ asset('images/logo.png') }}" alt="Sultan Arabic" title="Sultan Arabic">
                    </a>
                </div>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">
                            Gallery
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('services') }}"
                            class="{{ request()->routeIs('services') ? 'active' : '' }}">
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                            Contacts
                        </a>
                    </li>
                </ul>
        </div>
        <!-- ░░░ HAMBURGER (mobile) ░░░ -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>
    <!-- ░░░ SIDEBAR ░░░ -->
    <aside class="sidebar" id="sidebar" aria-hidden="true">
        <div class="sidebar-head">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Sultan Arabic" title="Sultan Arabic">
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
            <li><a href="{{ route('services') }}">Services</a></li>
            <li><a href="{{ route('contact') }}">Contacts</a></li>
        </ul>
        <p class="sidebar-footer">Luxury Arabic Dining · Kathmandu</p>
    </aside>
