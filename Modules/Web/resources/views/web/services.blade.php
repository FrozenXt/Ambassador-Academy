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

            @foreach ($services as $service)
                <div class="col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                    <div class="service-page-card reveal delay-{{ min($loop->iteration, 6) }}">
                        <div class="service-card-inner">
                            <div class="service-icon-wrap">
                                @if (!empty($service->image))
                                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}"
                                        style="width:100%;height:100%;object-fit:contain;">
                                @elseif (!empty($service->icon))
                                    <iconify-icon icon="{{ $service->icon }}"></iconify-icon>
                                @endif
                            </div>
                            <h3 class="service-title">{{ $service->title }}</h3>
                            <p class="service-desc">
                                {{ $service->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

@include('web::layouts.footer')
