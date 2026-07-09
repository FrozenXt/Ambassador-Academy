@php
    $pageTitle = 'Gallery | Sultan Arabic Restaurant';
    $pageDescription =
        'Explore stunning images of Sultan & Papas featuring elegant interiors, signature Arabic dishes, premium hospitality, and memorable dining experiences in Lalitpur, Kathmandu.';
    $pageKeywords =
        'restaurant gallery Kathmandu, Arabic dining photos, luxury restaurant Nepal, Sultan and Papas photos, Middle Eastern restaurant Kathmandu, fine dining gallery';
@endphp
@include('web::layouts.header')

<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">gallery</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gallery</li>
            </ol>
        </nav>
    </div>
</section>
<!-- ── Page Gallery SECTION ── -->
<section class="page-gallery section-padding">
    <div class="container position-relative z-1">
        <!-- Header -->
        <div class="text-center mb-5">
            <!-- Label -->
            <p class="label-script reveal delay-1">Visual Experience</p>
            <!-- Title -->
            <h2 class="title reveal delay-2">
                {{ $album->title ?? 'Moments of Arabian Excellence' }}
            </h2>
            <!-- Divider -->
            <div class="divider-gold reveal delay-3"><i></i></div>
        </div>

        <!-- Gallery -->
        <div class="gallery-grid pt-4" id="galleryGrid">

            @if ($album && $album->galleries->count())
                @foreach ($album->galleries as $i => $img)
                    {{-- Drop the quote block in as the 6th cell, same spot as before --}}
                    @if ($i == 5)
                        <div class="gallery-item quote-cell large reveal delay-6">
                            <div class="quote-cell-inner">
                                <blockquote>{{ $album->description }}</blockquote>
                            </div>
                        </div>
                    @endif

                    <div class="gallery-item reveal delay-{{ ($i % 9) + 1 }}" data-name="{{ $img->title }}"
                        data-index="{{ $i }}">
                        <img src="{{ $img->image_url }}" alt="{{ $img->title }}" />
                        <div class="gallery-overlay">
                            <div class="overlay-name">{{ $img->title }}</div>
                        </div>
                    </div>
                @endforeach

                {{-- If there were fewer than 6 images, still show the quote at the end --}}
                @if ($album->galleries->count() < 6)
                    <div class="gallery-item quote-cell large reveal delay-6">
                        <div class="quote-cell-inner">
                            <blockquote>{{ $album->description }}</blockquote>
                        </div>
                    </div>
                @endif
            @else
                <div class="gallery-item quote-cell large reveal delay-1">
                    <div class="quote-cell-inner">
                        <blockquote>No gallery images yet.</blockquote>
                    </div>
                </div>
            @endif

        </div><!-- /gallery-grid -->
    </div>
    <div class="lightbox-overlay" id="lightbox" role="dialog" aria-modal="true">
        <div class="lightbox-inner">
            <button class="lightbox-close" id="lbClose" aria-label="Close">&times;</button>
            <button class="lightbox-nav lightbox-prev" id="lbPrev" aria-label="Previous">&#8592;</button>
            <button class="lightbox-nav lightbox-next" id="lbNext" aria-label="Next">&#8594;</button>
            <img id="lbImg" src="" alt="Sultan Image Gallery" />
            <div class="lightbox-caption" id="lbCaption"></div>
            <div class="lightbox-tag" id="lbTag"></div>
        </div>
    </div>
</section>
@include('web::layouts.footer')
