@include('web::layouts.header')

<div class="breadcrump-wrapper">
    <div class="container">
        <div class="breadcrump-header">
            <h1>Gallery</h1>
        </div>
    </div>
</div>

<section class="gallery-section section-padding">
    <div class="container">
        <ul class="filter-nav" id="filterNav">
            <li>
                <button class="filter-btn active" data-filter="all">All</button>
            </li>
            @foreach ($filters as $filter)
                <li>
                    <button class="filter-btn" data-filter="{{ $filter['slug'] }}">{{ $filter['label'] }}</button>
                </li>
            @endforeach
        </ul>

        <div class="gallery-grid mode-all" id="galleryGrid">
            @php
                $areaLetters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
            @endphp

            @forelse ($galleryItems as $i => $item)
                <div class="gallery-item" data-area="{{ $areaLetters[$i % count($areaLetters)] }}"
                    data-category="{{ $item['category'] }}">
                    <img src="{{ Storage::url($item['image']->path) }}" alt="{{ $item['image']->title }}" />
                    <div class="gallery-overlay">
                        <span class="cap-label">{{ $item['image']->title }}</span>
                        <span class="cap-tag">{{ $item['label'] }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center w-100">No gallery images yet.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lightboxClose">&times;</button>
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev">&#8249;</button>
    <img src="" alt="" id="lightboxImg" />
    <button class="lightbox-nav lightbox-next" id="lightboxNext">&#8250;</button>
</div>

@include('web::layouts.footer')
