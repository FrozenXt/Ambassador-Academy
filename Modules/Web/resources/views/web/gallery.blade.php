@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner"
    style="background:linear-gradient(rgba(107,20,40,.88), rgba(20,67,47,.55)), url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600&q=80') center 30%/cover;">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Gallery</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Gallery</span>
        </div>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ GALLERY FILTER + GRID ============ -->
<section class="gallery-section">
    <div class="container">

        <div class="gallery-filters" data-aos="fade-up">
            <button class="gallery-filter-btn active" data-filter="all">All</button>
            @foreach ($filters ?? [] as $filter)
                <button class="gallery-filter-btn" data-filter="{{ $filter['slug'] }}">{{ $filter['label'] }}</button>
            @endforeach
        </div>

        <div class="gallery-grid" id="galleryGrid">
            @forelse ($galleryItems ?? [] as $i => $item)
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="{{ ($i % 6) * 80 }}"
                    data-category="{{ $item['category'] }}">
                    <a href="{{ $item['image']->file_type === 'image' ? Storage::url($item['image']->path) : '#' }}"
                        class="gallery-lightbox" data-title="{{ $item['label'] }}">
                        <img src="{{ Storage::url($item['image']->path) }}" alt="{{ $item['label'] }}" loading="lazy">
                        <div class="gallery-overlay">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </div>
                    </a>
                </div>
            @empty
                <div class="gallery-item" data-category="campus-life">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&q=80"
                        alt="Campus Life">
                </div>
                <div class="gallery-item" data-category="academics">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80" alt="Academics">
                </div>
                <div class="gallery-item" data-category="sports">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600&q=80" alt="Sports">
                </div>
            @endforelse
        </div>

        <div class="gallery-tagline" data-aos="fade-up">
            <h3>Every Moment, A Memory</h3>
            <p>Capturing the joy, achievements, and unforgettable moments of our school life.</p>
        </div>

    </div>
</section>

<!-- ============ NEWSLETTER ============ -->
<section class="newsletter-section" data-aos="fade-up">
    <div class="container newsletter-inner">
        <div class="newsletter-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
        <div class="newsletter-text">
            <h4>Stay Connected</h4>
            <p>Subscribe to our newsletter for the latest updates, events and news.</p>
        </div>
        <form class="newsletter-form" id="newsletterForm" action="{{ route('newsletter.subscribe') }}"
            method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-dark-green">Subscribe</button>
        </form>
    </div>
    <div class="newsletter-leaf"></div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.gallery-filter-btn');
        const items = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                items.forEach(item => {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

@include('web::layouts.footer')
