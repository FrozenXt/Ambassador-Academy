@php
    $pageTitle = 'Service | Sultan Arabic Restaurant';
    $pageDescription =
        'From luxury dining and family gatherings to corporate events and special celebrations, Sultan & Papas delivers authentic Arabic cuisine and exceptional hospitality in Kathmandu.';
    $pageKeywords =
        'restaurant events Kathmandu, private party venue Lalitpur, corporate dining Kathmandu, Arabic catering Nepal, celebration venue Kathmandu, fine dining services';
@endphp
@include('web::layouts.header')

<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">Service</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Service</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ── Page Service SECTION ── -->
<section class="page-service section-padding">
    <div class="container position-relative z-1">
        <div class="row g-4 justify-content-center">

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-1">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="material-symbols-light:menu-book-2"></iconify-icon>
                        </div>
                        <h3 class="service-title">Menu: For Every Taste</h3>
                        <p class="service-desc">
                            Authentic cuisine, elegant surroundings, premium beverages, and
                            world-class hospitality tailored to every palate.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-2">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="material-symbols-light:menu-book-2"></iconify-icon>
                        </div>
                        <h3 class="service-title">Always Fresh Ingredients</h3>
                        <p class="service-desc">
                            We source the finest imported spices, herbs, and premium-grade produce
                            daily to guarantee every plate is extraordinary.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-3">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="material-symbols:chef-hat"></iconify-icon>
                        </div>
                        <h3 class="service-title">Experienced Arabic Chefs</h3>
                        <p class="service-desc">
                            Our kitchen is led by seasoned masters of Levantine and Gulf cuisine,
                            framed in Dubai's finest establishments.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-4">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="fluent:building-townhouse-32-filled"></iconify-icon>
                        </div>
                        <h3 class="service-title">Luxury Ambiance</h3>
                        <p class="service-desc">
                            Inspired by the grandeur of Arabian palaces - from the lighting to
                            the tableware, every detail is curated for elegance.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-5">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="iconamoon:music-2-fill"></iconify-icon>
                        </div>
                        <h3 class="service-title">Live Entertainment</h3>
                        <p class="service-desc">
                            Experience vibrant DJ nights, oud performances, and exclusive
                            cultural events.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                <div class="service-page-card reveal delay-6">
                    <div class="service-card-inner">
                        <div class="service-icon-wrap">
                            <iconify-icon icon="ph:cheers-fill"></iconify-icon>
                        </div>
                        <h3 class="service-title">Private Events &amp; Banquets</h3>
                        <p class="service-desc">
                            Corporate dinners, private functions, and memorable events -
                            perfectly managed.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('web::layouts.footer')
