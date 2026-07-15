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
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
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
                <li><a href="{{ route('services') }}">Services</a></li>
                <li><a href="{{ route('contact') }}">Contacts</a></li>
            </ul>
            <p class="sidebar-footer">Luxury Arabic Dining · Kathmandu</p>
        </aside>

        <div class="hero" id="stage">
            <!-- Fullscreen looping background video with dark overlay -->
            <div class="hero-media">
                @if ($bannerItem && Str::endsWith($bannerItem->path, ['.mp4', '.webm', '.ogg']))
                    <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
                        <source src="{{ Storage::url($bannerItem->path) }}" type="video/mp4" />
                    </video>
                @elseif ($bannerItem)
                    <img class="hero-video" id="heroVideo" src="{{ Storage::url($bannerItem->path) }}"
                        alt="" />
                @else
                    <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
                        <source src="{{ asset('image/video.mp4') }}" type="video/mp4" />
                    </video>
                @endif
                <div class="hero-overlay" id="heroOverlay"></div>
            </div>

            <!-- Center content -->
            <div class="hero-content" id="heroContent">
                <p class="hero-eyebrow" id="heroEyebrow">
                    {{ $bannerItem->title ?? 'Premium Grills • Signature Cocktails • Unforgettable Nights' }}
                </p>

                <h1 class="hero-heading" id="heroHeading">
                    {{ $bannerItem->subtitle ?? "Kathmandu's Largest" }}<br />
                    <em>{{ $bannerItem->description ?? 'Rooftop Bar & Grill' }}</em>
                </h1>

                <a href="{{ $bannerItem->youtube_link ?? '#reveal' }}" class="btn-gold" id="heroBtn">
                    <span>Explore Now</span>
                </a>
            </div>

            <!-- Half circle + bottles emerging from it -->
            <div class="circle-stage" id="circleStage">
                <div class="half-circle" id="halfCircle"></div>
                <div class="half-circlebg" id="halfCirclebg"></div>
                <div class="bottle-wrap bottle-marker" id="bottleWrapHero">
                    <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                        alt="" class="bottle bottle-back" id="bottleBackHero" />
                    <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                        alt="" class="bottle bottle-front" id="bottleFrontHero" />
                </div>
                <div class="bottle-glow" id="bottleGlow"></div>
            </div>
        </div>

        <div class="reveal" id="stage1">
            <div class="container">
                <div class="row reveal-inner">
                    <div class="col-md-6">
                        <div class="reveal-copy">
                            <ul class="reveal-list">
                                @foreach ($revealFeatures as $feature)
                                    <li class="reveal-item">
                                        <span class="reveal-bullet"></span>
                                        <div>
                                            <h3 class="reveal-heading split-target">
                                                {!! $feature->title !!}
                                            </h3>
                                            <p class="reveal-sub">{{ $feature->description }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="reveal-visual" aria-hidden="true">
                            <div class="bottle-wrap bottle-marker" id="bottleWrapReveal">
                                <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                                    alt="" class="bottle bottle-back" id="bottleBackReveal" />
                                <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                                    alt="" class="bottle bottle-front" id="bottleFrontReveal" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="curve" id="stage2">
            <div class="container">
                <div class="curve-stage" id="curveStage">
                    <div class="half-curve" id="halfCurve"></div>
                    <div class="bottle-wrap bottle-marker" id="bottleWrapCurve">
                        <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                            alt="" class="bottle bottle-back" id="bottleBackCurve" />
                        <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                            alt="" class="bottle bottle-front" id="bottleFrontCurve" />
                    </div>
                </div>
                <div class="features">
                    <div class="feature-row" id="featureRow">
                        @foreach ($papasFeatures as $feature)
                            <div class="feature {{ $loop->last ? 'spaced' : '' }}">
                                <div class="ring">
                                    <iconify-icon icon="{{ $feature->icon }}"></iconify-icon>
                                </div>
                                <span>{{ $feature->title }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Burger marker for this stage — the traveling #burgerFly duplicate
               is what's visible; this defines where it starts. -->
            </div>
            <div class="burger-wrap burger-marker" id="burgerWrapCurve">
                <img src="{{ asset('image/b3.png') }}" alt="" class="burger" id="burgerCurve" />
            </div>
        </div>

        <div class="story" id="stage3">
            <div class="container">
                <div class="row row-story align-items-center g-4">
                    <div class="col-lg-6">
                        <h2 class="story-title">{{ $storyPost->title ?? 'The Story' }}</h2>

                        @if ($storyPost && $storyPost->content)
                            {!! nl2br(e(strip_tags($storyPost->content))) !!}
                        @else
                            <p class="story-text">
                                <strong>PAPA&rsquo;s Bar &amp; Grill</strong> was born at the
                                crossroads of Middle Eastern tradition and Nepali warmth.
                                Complementing the experience is Papas Bar &amp; Grill, home to
                                Kathmandu&rsquo;s largest rooftop bar, offering handcrafted
                                cocktails, grilled specialties, live entertainment, breathtaking
                                city views, and unforgettable nights.
                            </p>
                            <p class="story-text">
                                From authentic Arabic flavors and live belly dancing
                                performances to luxury dining and vibrant rooftop nightlife,
                                Sultan Arabic Fine Dine and Papas Bar &amp; Grill are set to
                                redefine dining and entertainment in Kathmandu.
                            </p>
                        @endif
                    </div>

                    <div class="col-lg-6 visual-col">
                        <!-- Burger marker: where the traveling burger lands at this stage -->
                        <div class="burger-wrap burger-marker" id="burgerWrapStory">
                            <img src="{{ $storyPost && $storyPost->image ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
                                alt="" class="burger" id="burgerStory" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="gallery-pin" id="galleryPin">
            <div class="gallery-intro" id="galleryIntro">
                <h2 class="gallery-title">Gallery</h2>
                <div class="thumb-row" id="thumbRow">
                    @foreach ($galleryImages->take(3) as $i => $img)
                        <div class="thumb" id="thumb{{ $i + 1 }}">
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                        </div>
                    @endforeach
                </div>

                <div class="filmstrip" id="filmstrip">
                    <div class="film-track" id="filmTrack">
                        @foreach ($galleryImages as $img)
                            <div class="film-slide" data-title="{{ $img->title }}">
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                            </div>
                        @endforeach
                    </div>

                    <div class="film-overlay" id="filmOverlay">
                        <h3 class="film-title" id="filmTitle">{{ $galleryImages->first()->title ?? '' }}</h3>
                    </div>

                    <div class="film-nav" id="filmNav">
                        <button class="nav-btn" id="prevBtn" aria-label="Previous">
                            &larr;
                        </button>
                        <button class="nav-btn" id="nextBtn" aria-label="Next">
                            &rarr;
                        </button>
                    </div>

                    <div class="film-progress" id="filmProgress"></div>
                </div>
            </div>
        </section>

        <section class="menu-section section-padding">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h2 class="menu-title">Our Special Menu</h2>

                        @forelse ($menuCategory->products ?? [] as $item)
                            <div class="menu-item">
                                <div>
                                    <h3>{{ $item->name }}</h3>
                                    <p>{{ $item->description }}</p>
                                </div>
                                <div class="price">Rs. {{ number_format($item->price, 0) }}</div>
                            </div>
                        @empty
                            <p class="text-muted">Menu coming soon.</p>
                        @endforelse
                    </div>

                    <div class="col-lg-6">
                        <div class="menu-gallery">
                            @foreach ($menuGalleryImages as $i => $img)
                                <div class="g-img {{ $i === 0 ? 'wide' : 'narrow' }}">
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer section-padding pb-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-0 text-center footer-top">
                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="fluent:call-20-regular"></iconify-icon>
                            <h3>Contact Us</h3>
                            <p>{{ $siteSettings->getByKey('site_phone', '014507444, 014509444') }}</p>
                            <a href="tel:{{ $siteSettings->getByKey('site_phone', '014507444') }}"
                                class="btn-outline-gold">Call Us Here</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="weui:location-outlined"></iconify-icon>
                            <h3>Address</h3>
                            <p>{{ $siteSettings->getByKey('site_address', 'Lazimpat, Kathmandu, Nepal') }}</p>
                            <a href="{{ $siteSettings->getByKey('google_map_link', '#') }}"
                                class="btn-outline-gold">Get Direction</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="streamline-cyber:email-2"></iconify-icon>
                            <h3>Email Address</h3>
                            <p>{{ $siteSettings->getByKey('site_email', 'info@papabargrill.com') }}</p>
                            <a href="mailto:{{ $siteSettings->getByKey('site_email', 'info@papabargrill.com') }}"
                                class="btn-outline-gold">Message Us</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="fe:clock"></iconify-icon>
                            <h3>Opening Hours</h3>
                            <p>
                                {{ $siteSettings->getByKey('opening_hours_weekday', 'Mon &ndash; Sun:') }}<br>
                                {{ $siteSettings->getByKey('opening_hours_weekend', '9:30 am &ndash; 12:30 am') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center middle-row">
                    <div class="col-12 col-md-4">
                        <p class="footer-copy">
                            &copy; {{ date('Y') }} <span
                                class="accent">{{ $siteSettings->getByKey('site_name', "Papa's bar and grill") }}</span>.
                            All
                            rights reserved.
                        </p>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="brand-badge">
                            <img src="{{ $logoUrl }}" alt="">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="social-row">
                            <a href="{{ $siteSettings->getByKey('social_instagram', '#') }}"
                                aria-label="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
                            <a href="{{ $siteSettings->getByKey('social_facebook', '#') }}"
                                aria-label="Facebook"><iconify-icon icon="ic:outline-facebook"></iconify-icon></a>
                            <a href="{{ $siteSettings->getByKey('social_whatsapp', '#') }}"
                                aria-label="WhatsApp"><iconify-icon icon="mingcute:whatsapp-line"></iconify-icon></a>
                        </div>
                    </div>
                </div>

                <p class="footer-credit">Developed By: <a href="https://bentraytech.com/">Bent Ray Technologies</a>
                </p>
            </div>
        </footer>

        <!-- Traveling bottle: hero -> reveal -> curve, driven purely by scroll
         progress (see initBottleJourney in custom.js). Its start/mid/end
         geometry is measured live from the hidden .bottle-marker instances
         above, so it always lands exactly where each section already
         places it in the markup/CSS. -->
        <div class="bottle-fly" id="bottleFly" aria-hidden="true">
            <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                alt="" class="bottle-fly-img" id="bottleFlyBack" />
            <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                alt="" class="bottle-fly-img" id="bottleFlyFront" />
        </div>

        <!-- Traveling burger: curve -> story, same approach as the bottle above. -->
        <div class="burger-fly" id="burgerFly" aria-hidden="true">
            <img src="{{ $storyPost && $storyPost->image ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
                alt="" class="burger-fly-img" id="burgerFlyImg" />
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('js/gsap.min.js') }}"></script>
    <script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('js/text-split.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>

</html>
