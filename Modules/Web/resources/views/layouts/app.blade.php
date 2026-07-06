<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>{{ $settings['meta_title']->value ?? ($settings['site_name']->value ?? 'My Shop') }} | @yield('page_title', 'Home')
    </title>
    <meta name="description" content="{{ $settings['meta_description']->value ?? '' }}">
    <meta name="keywords" content="{{ $settings['meta_keywords']->value ?? '' }}">
    <meta name="author" content="{{ $settings['site_name']->value ?? 'My Shop' }}">

    {{-- Favicon --}}
    @if (!empty($settings['site_favicon']->value))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['site_favicon']->value) }}">
    @endif

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Swiper Slider --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Header Scripts from Settings --}}
    {!! $settings['header_scripts']->value ?? '' !!}

    {{-- Google Analytics --}}
    {!! $settings['google_analytics']->value ?? '' !!}

    <style>
        :root {
            --primary: #F02F34;
            --primary-dark: #d41f24;
            --primary-light: #ff5a5f;
            --secondary: #E7D3BB;
            --secondary-dark: #d4bea0;
            --dark: #1a1a1a;
            --gray: #6c757d;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --black: #000000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            overflow-x: hidden;
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Navbar Styles */
        .navbar {
            background: var(--white);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            max-height: 45px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }

        /* Dropdown Styles - Hover based with clickable parent */
        .dropdown {
            position: relative;
        }

        .dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            min-width: 220px;
            padding: 0.5rem;
            margin: 0.5rem 0 0;
            font-size: 0.9rem;
            color: var(--dark);
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }

        /* Show dropdown on hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Keep dropdown visible when hovering over the menu itself */
        .dropdown .dropdown-menu:hover {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Add a bridge between nav link and dropdown */
        .dropdown::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 10px;
            background: transparent;
        }

        .dropdown-menu {
            margin-top: 0 !important;
        }

        .dropdown-item {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: var(--dark);
            cursor: pointer;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: var(--secondary);
            color: var(--primary);
        }

        .dropdown-item.active {
            background: var(--primary);
            color: white;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
        }

        /* Search Form */
        .search-form {
            position: relative;
        }

        .search-input {
            border: 2px solid #e5e7eb;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            width: 220px;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: none;
            outline: none;
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            border: none;
            border-radius: 50px;
            padding: 0.3rem 1rem;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .search-btn:hover {
            background: var(--primary-dark);
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 47, 52, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Section Styles */
        .section-padding {
            padding: 80px 0;
        }

        .section-tag {
            display: inline-block;
            background: var(--secondary);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Product Card */
        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .product-img-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: var(--light-gray);
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.08);
        }

        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
        }

        .product-badge.new {
            background: var(--primary);
        }

        .product-badge.sale {
            background: #f59e0b;
        }

        .product-info {
            padding: 1.2rem;
        }

        .product-category {
            font-size: 0.7rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .product-name a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-name a:hover {
            color: var(--primary);
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.8rem;
        }

        .product-price del {
            font-size: 0.85rem;
            color: var(--gray);
            margin-left: 0.5rem;
        }

        /* Category Card */
        .category-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            display: block;
            text-decoration: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .category-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover .category-img {
            transform: scale(1.08);
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 1.5rem;
            color: white;
        }

        .category-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .category-count {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* Service Card */
        .service-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            color: var(--primary);
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background: var(--primary);
            color: white;
            transform: rotateY(180deg);
        }

        /* Testimonial Card */
        .testimonial-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: var(--gray);
            padding: 60px 0 20px;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .footer a {
            color: var(--gray);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary);
        }

        .footer-logo {
            max-height: 35px;
            width: auto;
            object-fit: contain;

            /* Optional improvements */
            display: inline-block;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        /* Newsletter Form */
        .newsletter-form {
            position: relative;
        }

        .newsletter-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            color: white;
            outline: none;
            transition: all 0.3s ease;
        }

        .newsletter-input:focus {
            border-color: var(--primary);
        }

        .newsletter-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            color: white;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            background: var(--primary-dark);
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .dropdown .dropdown-menu {
                position: static;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
                opacity: 1;
                transform: none;
                padding-left: 1rem;
                pointer-events: auto;
                display: none;
            }

            .dropdown.show .dropdown-menu {
                display: block;
            }

            .dropdown-item {
                color: var(--dark);
                padding: 0.5rem 1rem;
            }

            .dropdown-item:hover {
                background: var(--secondary);
            }

            .search-input {
                width: 100%;
            }

            .nav-link.dropdown-toggle::after {
                display: inline-block;
                margin-left: 0.255em;
                vertical-align: 0.255em;
                content: "";
                border-top: 0.3em solid;
                border-right: 0.3em solid transparent;
                border-bottom: 0;
                border-left: 0.3em solid transparent;
            }

            .section-padding {
                padding: 50px 0;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .product-img-wrapper {
                height: 180px;
            }

            .category-img {
                height: 160px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>

    @yield('extra_css')
</head>

<body>
    {{-- ── NAVBAR ── --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNav">
        <div class="container">
            {{-- Logo / Brand --}}
            <a class="navbar-brand" href="{{ route('web.home') }}">
                @if (!empty($settings['site_logo']->value))
                    <img src="{{ asset('storage/' . $settings['site_logo']->value) }}"
                        alt="{{ $settings['site_name']->value }}" style="max-height: 45px;">
                @else
                    <span class="fw-bold" style="color: var(--primary); font-size: 1.5rem;">
                        {{ $settings['site_name']->value ?? 'MyShop' }}
                    </span>
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                {{-- Dynamic Header Menu --}}
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    @php
                        $headerMenu = \Modules\Common\Entities\Menu::where('location', 'header')
                            ->where('status', 'active')
                            ->with([
                                'items' => function ($q) {
                                    $q->where('status', 'active')
                                        ->orderBy('order')
                                        ->with([
                                            'children' => function ($q) {
                                                $q->where('status', 'active')->orderBy('order');
                                            },
                                        ]);
                                },
                            ])
                            ->first();
                    @endphp

                    @if ($headerMenu && $headerMenu->items->count())
                        @foreach ($headerMenu->items as $item)
                            @if ($item->children->count())
                                {{-- Dropdown item with clickable parent link --}}
                                <li class="nav-item dropdown position-relative">
                                    <a class="nav-link dropdown-toggle {{ request()->url() == url($item->url ?? '') ? 'active' : '' }}"
                                        href="{{ $item->url ?? '#' }}" id="dropdown-{{ $item->id }}"
                                        role="button" aria-expanded="false">
                                        @if ($item->icon)
                                            <i class="{{ $item->icon }} me-1"></i>
                                        @endif
                                        {{ $item->label }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdown-{{ $item->id }}">
                                        @foreach ($item->children as $child)
                                            <li>
                                                <a class="dropdown-item {{ request()->url() == url($child->url ?? '') ? 'active' : '' }}"
                                                    href="{{ $child->url ?? '#' }}" target="{{ $child->target }}">
                                                    @if ($child->icon)
                                                        <i class="{{ $child->icon }} me-2"></i>
                                                    @endif
                                                    {{ $child->label }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                {{-- Single menu item --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->url() == url($item->url ?? '') ? 'active' : '' }}"
                                        href="{{ $item->url ?? '#' }}" target="{{ $item->target }}">
                                        @if ($item->icon)
                                            <i class="{{ $item->icon }} me-1"></i>
                                        @endif
                                        {{ $item->label }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback static links --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('web.home') ? 'active' : '' }}"
                                href="{{ route('web.home') }}">Home</a>
                        </li>



                    @endif
                </ul>

                {{-- Search Form --}}
                <form class="search-form d-flex" action="{{ route('web.home') }}" method="GET">
                    <input class="search-input form-control" type="search" name="search"
                        placeholder="Search products..." value="{{ request('search') }}">
                    <button class="search-btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ── MAIN CONTENT ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                {{-- Brand & About --}}
                <div class="col-md-4">
                    <a href="{{ route('web.home') }}" class="footer-brand">
                        @if (!empty($settings['site_logo']->value ?? null))
                            <img src="{{ asset('storage/' . data_get($settings, 'site_logo.value')) }}"
                                class="footer-logo" />
                        @else
                            {{ $settings['site_name']->value ?? 'MyShop' }}
                        @endif
                    </a>

                    <p class="small mt-3">
                        {{ $settings['footer_about']->value ?? 'We are a CMS website dedicated to providing the best products and services to our customers.' }}
                    </p>

                    {{-- Social Icons --}}
                    <div class="d-flex gap-2 mt-3">
                        @if (!empty($settings['facebook_url']->value) && $settings['facebook_url']->value != '#')
                            <a href="{{ $settings['facebook_url']->value }}" class="social-icon" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if (!empty($settings['twitter_url']->value) && $settings['twitter_url']->value != '#')
                            <a href="{{ $settings['twitter_url']->value }}" class="social-icon" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if (!empty($settings['instagram_url']->value) && $settings['instagram_url']->value != '#')
                            <a href="{{ $settings['instagram_url']->value }}" class="social-icon" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if (!empty($settings['youtube_url']->value) && $settings['youtube_url']->value != '#')
                            <a href="{{ $settings['youtube_url']->value }}" class="social-icon" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        @if (!empty($settings['linkedin_url']->value) && $settings['linkedin_url']->value != '#')
                            <a href="{{ $settings['linkedin_url']->value }}" class="social-icon" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-md-2">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>

                    <ul class="list-unstyled small footer-links">
                        @if ($headerMenu && $headerMenu->items->count())

                            @foreach ($headerMenu->items as $item)
                                @if ($item->children->count())
                                    {{-- Parent with dropdown --}}
                                    <li class="mb-2">
                                        <a href="javascript:void(0);"
                                            class="footer-toggle d-flex justify-content-between align-items-center"
                                            data-target="footer-menu-{{ $item->id }}">

                                            <span>
                                                @if ($item->icon)
                                                    <i class="{{ $item->icon }} me-1"></i>
                                                @endif
                                                {{ $item->label }}
                                            </span>

                                            <span class="arrow">&#9662;</span>
                                        </a>

                                        <ul class="list-unstyled ps-3 mt-2 d-none footer-submenu"
                                            id="footer-menu-{{ $item->id }}">

                                            @foreach ($item->children as $child)
                                                <li class="mb-1">
                                                    <a href="{{ $child->url ?? '#' }}"
                                                        target="{{ $child->target }}">
                                                        @if ($child->icon)
                                                            <i class="{{ $child->icon }} me-1"></i>
                                                        @endif
                                                        {{ $child->label }}
                                                    </a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                @else
                                    {{-- Single link --}}
                                    <li class="mb-2">
                                        <a href="{{ $item->url ?? '#' }}" target="{{ $item->target }}">
                                            @if ($item->icon)
                                                <i class="{{ $item->icon }} me-1"></i>
                                            @endif
                                            {{ $item->label }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            {{-- fallback --}}
                            <li class="mb-2"><a href="{{ route('web.home') }}">Home</a></li>
                            <li class="mb-2"><a href="{{ route('web.home') }}">Products</a></li>
                            <li class="mb-2"><a href="{{ route('web.home') }}">Contact</a></li>
                        @endif
                    </ul>
                </div>
                {{-- Contact Info --}}
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Contact Info</h6>
                    <ul class="list-unstyled small">
                        @if (!empty($settings['site_name']->value))
                            <li class="mb-2">
                                <i class="fa-regular fa-building me-2"></i>
                                <a
                                    href="tel:{{ $settings['site_name']->value }}">{{ $settings['site_name']->value }}</a>
                            </li>
                        @endif
                        @if (!empty($settings['site_phone']->value))
                            <li class="mb-2">
                                <i class="fas fa-phone me-2"></i>
                                <a
                                    href="tel:{{ $settings['site_phone']->value }}">{{ $settings['site_phone']->value }}</a>
                            </li>
                        @endif
                        @if (!empty($settings['site_email']->value))
                            <li class="mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <a
                                    href="mailto:{{ $settings['site_email']->value }}">{{ $settings['site_email']->value }}</a>
                            </li>
                        @endif
                        @if (!empty($settings['site_address']->value))
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $settings['site_address']->value }}
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Newsletter</h6>
                    <p class="small">Subscribe to get latest updates and offers.</p>
                    <form class="newsletter-form" method="POST">
                        @csrf
                        <input type="email" name="email" class="newsletter-input"
                            placeholder="Your email address..." required>
                        <button type="submit" class="newsletter-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0 1rem;">

            <div class="text-center small">

                {{ $settings['footer_text']->value ?? '© ' . date('Y') . ' MyShop. All rights reserved.' }}
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('mainNav');
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });

            // Make parent dropdown links clickable
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                // Remove Bootstrap's default dropdown toggle behavior
                toggle.removeAttribute('data-bs-toggle');

                // Add click handler for parent link
                toggle.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                });

                // Hover functionality for desktop
                if (window.innerWidth > 991.98) {
                    const parent = this.closest('.dropdown');
                    let hoverTimer;

                    parent.addEventListener('mouseenter', function() {
                        clearTimeout(hoverTimer);
                        const menu = this.querySelector('.dropdown-menu');
                        if (menu) {
                            menu.style.display = 'block';
                            menu.style.opacity = '1';
                            menu.style.transform = 'translateY(0)';
                        }
                    });

                    parent.addEventListener('mouseleave', function() {
                        hoverTimer = setTimeout(() => {
                            const menu = this.querySelector('.dropdown-menu');
                            if (menu) {
                                menu.style.display = '';
                                menu.style.opacity = '';
                                menu.style.transform = '';
                            }
                        }, 200);
                    });

                    // Keep menu open when hovering over the menu itself
                    const menu = parent.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.addEventListener('mouseenter', function() {
                            clearTimeout(hoverTimer);
                            this.style.display = 'block';
                            this.style.opacity = '1';
                            this.style.transform = 'translateY(0)';
                        });

                        menu.addEventListener('mouseleave', function() {
                            hoverTimer = setTimeout(() => {
                                this.style.display = '';
                                this.style.opacity = '';
                                this.style.transform = '';
                            }, 200);
                        });
                    }
                }
            });

            // Handle dropdown items click
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                });
            });

            // Handle regular nav links
            const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                });
            });

            // Mobile dropdown toggle
            if (window.innerWidth <= 991.98) {
                dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const parent = this.closest('.dropdown');
                        parent.classList.toggle('show');
                    });
                });
            }

            // Active link detection
            const currentUrl = window.location.href;
            document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
                const linkUrl = link.getAttribute('href');
                if (linkUrl && linkUrl !== '#' && currentUrl === linkUrl) {
                    link.classList.add('active');

                    // If it's a dropdown item, also highlight parent
                    const parentDropdown = link.closest('.dropdown');
                    if (parentDropdown) {
                        const parentToggle = parentDropdown.querySelector('.dropdown-toggle');
                        if (parentToggle) {
                            parentToggle.classList.add('active');
                        }
                    }
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
        document.querySelectorAll('.footer-toggle').forEach(item => {
            item.addEventListener('click', function() {
                let target = document.getElementById(this.dataset.target);
                let arrow = this.querySelector('.arrow');

                target.classList.toggle('d-none');

                // rotate arrow
                arrow.classList.toggle('rotate');
            });
        });
    </script>

    {{-- Footer Scripts from Settings --}}
    {!! $settings['footer_scripts']->value ?? '' !!}

    @yield('extra_js')
</body>

</html>
