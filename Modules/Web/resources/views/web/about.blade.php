@php
    $pageTitle = 'About Us | Sultan Arabic Restaurant';
    $pageDescription =
        "Learn more about Sultan Arabic Restaurant, Kathmandu's premier luxury Arabic dining destination.";
    $pageKeywords = 'arabic restaurant kathmandu, about sultan restaurant, middle eastern food';
@endphp
@include('web::layouts.header')

<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">About Us</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">About</li>
            </ol>
        </nav>
    </div>
</section>
<!-- ── Page About SECTION ── -->
<section class="page-about-section about-section section-padding">
    <div class="container">

        <div class="text-center mb-5">
            <p class="label-script reveal delay-1">Luxury Halal Arabic Restaurant</p>
            <h2 class="title reveal delay-2">
                {{ $aboutBrandPost->title ?? 'Where Dubai Meets Kathmandu' }}
            </h2>
            <div class="divider-gold reveal delay-3"><i></i></div>
        </div>

        <div class="row align-items-center justify-content-center g-4 g-md-5">

            <!-- Left: Lantern image -->
            <div class="col-12 col-md-3 col-lg-4 d-flex justify-content-center">
                <div class="lantern-wrap reveal-left delay-4">
                    <img src="{{ $aboutBrandPost && $aboutBrandPost->image ? $aboutBrandPost->image_url : asset('images/1.webp') }}"
                        alt="{{ $aboutBrandPost->title ?? "Sultan's Arabic Grill" }}">
                </div>
            </div>

            <!-- Center: Brand description -->
            <div class="col-12 col-md col-lg-4">
                <div class="page-about-wrapper">
                    <h3 class="brand-name reveal delay-4">{{ $aboutBrandPost->subtitle ?? "Sultan's Arabic Grill" }}
                    </h3>
                    <p class="reveal delay-5">
                        {{ $aboutBrandPost ? strip_tags($aboutBrandPost->content) : 'is a premium destination for authentic Halal Arabic cuisine in Kathmandu. Inspired by traditional Middle Eastern flavors and royal hospitality, we specialize in expertly grilled meats, rich spices, and time-honored recipes prepared under the guidance of Chef Mustafa. From signature kebabs and lamb chops to flavorful mezze and charcoal-grilled specialties, every dish is crafted to deliver an authentic Arabic dining experience with a modern luxury touch.' }}
                    </p>
                </div>
            </div>

            <!-- Right: Halal badge (kept static) -->
            <div class="col-12 col-md-3 col-lg-4 d-flex justify-content-center">
                <div class="halal-badge reveal-right delay-4">
                    <div class="halal-inner">
                        <span class="percent reveal delay-5">100%</span>
                        <span class="percent-title reveal delay-6">HALAL CUISINE</span>
                    </div>
                </div>
            </div>

        </div><!-- /row -->
    </div><!-- /container -->
</section>
<<!-- ── Chairman SECTION ── -->
    <section class="chairman-card section-padding">

        <div class="container">
            <div class="chairman-wrapper">
                <div class="chairman-wrap">
                    <div class="row g-4 align-items-stretch">

                        <!-- Portrait -->
                        <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                            <div class="arch-frame-wrppaer reveal-left delay-1">
                                <div class="arch-frame">
                                    <img src="{{ $chairmanPost && $chairmanPost->image ? $chairmanPost->image_url : asset('images/p4.jpg') }}"
                                        alt="{{ $chairmanPost->title ?? "Welcome to Sultan's Arabic Grill" }}">
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-12 col-md-8 content-col">

                            <div class="mb-4">
                                <h2 class="title d-flex reveal delay-1"><span class="quote-mark">"</span>
                                    {{ $chairmanPost->title ?? 'Where tradition, hospitality, and excellence come together.' }}
                                </h2>
                                <div class="divider-gold reveal delay-2"><i></i></div>
                            </div>

                            <div class="content-title reveal delay-3">
                                {{ $chairmanPost->subtitle ?? "Welcome to Sultan's Arabic Grill," }}
                            </div>

                            <p class="reveal delay-4" style="white-space: pre-line;">
                                {{ $chairmanPost ? strip_tags($chairmanPost->content) : "Our vision is to create a dining destination that brings the rich flavors, traditions, and hospitality of the Arab world to Nepal. Every detail of Sultan has been thoughtfully designed-from our authentic cuisine and luxurious ambience to our commitment to exceptional service.\n\nThank you for being a part of our journey. We look forward to welcoming you and sharing the true essence of Arabic hospitality in Nepal." }}
                            </p>

                            <span class="signature reveal delay-7">
                                - {{ $chairmanPost->position ?? 'Chairman & Founder' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ── Gallery SECTION ── -->
    <section class="gallery-section section-padding">
        <div class="container position-relative z-1">
            <!-- Header -->
            <div class="text-center mb-5">
                <p class="label-script reveal delay-1">Visual Journey</p>
                <h2 class="title reveal delay-2">
                    {{ $aboutAlbum->title ?? 'The Sultan Experience' }}
                </h2>
                <div class="divider-gold reveal delay-3"><i></i></div>
                <p class="reveal delay-3">
                    {{ $aboutAlbum->description ?? 'Prepared with carefully sourced Halal ingredients, ensuring authenticity, quality, and peace of mind.' }}
                </p>
            </div>

            <!-- Gallery -->
            <div class="gallery-grid pt-4" id="galleryGrid">

                @foreach ($aboutImages as $img)
                    <div class="gallery-item reveal delay-{{ min($loop->iteration, 9) }}"
                        data-name="{{ $img->name }}" data-index="{{ $loop->index }}">
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $img->name }}" />
                        <div class="gallery-overlay">
                            <div class="overlay-name">{{ $img->name }}</div>
                        </div>
                    </div>

                    {{-- Quote cell fixed in the middle of the grid, after the 4th image --}}
                    @if ($loop->iteration === 4)
                        <div class="gallery-item quote-cell reveal delay-5">
                            <div class="quote-cell-inner">
                                <blockquote>
                                    {{ $aboutAlbum->description ?? 'Bring the rich flavors, traditions, and hospitality of the Arab world to Nepal.' }}
                                </blockquote>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div><!-- /gallery-grid -->
        </div>
        <div class="lightbox-overlay" id="lightbox" role="dialog" aria-modal="true">
            <div class="lightbox-inner">
                <button class="lightbox-close" id="lbClose" aria-label="Close">&times;</button>
                <button class="lightbox-nav lightbox-prev" id="lbPrev" aria-label="Previous">&#8592;</button>
                <button class="lightbox-nav lightbox-next" id="lbNext" aria-label="Next">&#8594;</button>
                <img id="lbImg" src="" alt=" Sultan Image" />
                <div class="lightbox-caption" id="lbCaption"></div>
                <div class="lightbox-tag" id="lbTag"></div>
            </div>
        </div>
    </section>
    <!-- ── Chef SECTION ── -->
    <section class="chef-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <!-- Label -->
                <p class="label-script reveal delay-1">Dubai Culinary Expertise</p>
                <!-- Title -->
                <h2 class="title reveal delay-2">
                    {{ $chefAlbum->title ?? 'Chef de Cuisine – Dubai Heritage' }}
                </h2>
                <!-- Divider -->
                <div class="divider-gold reveal delay-3"><i></i></div>

                <!-- Description -->
                <p class="reveal delay-3">
                    {{ $chefAlbum->description ?? 'An Arabic chef from Dubai leads the kitchen at Sultan Arabic Grill, bringing authentic Middle Eastern flavors and royal hospitality to Kathmandu. Every dish is crafted with traditional techniques and modern fine-dining presentation-a true Dubai-to-Kathmandu culinary experience.' }}
                </p>
            </div>

            <div class="chef-grid">
                @php
                    // Matches your original 3-card layout: left / center (no left/right class) / right
                    $chefAnimClasses = ['reveal-left', 'reveal', 'reveal-right'];
                @endphp

                @foreach ($chefImages as $img)
                    <div class="chef-item {{ $chefAnimClasses[$loop->index % 3] }} delay-4">
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                        <div class="chef-overlay">
                            <span class="hover-title">"{{ $img->title }}"</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- ── Contact SECTION ── -->
    @include('web::layouts.location')
    @include('web::layouts.footer')
