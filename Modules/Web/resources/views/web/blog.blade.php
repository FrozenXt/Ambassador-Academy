@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Latest News &amp; Blogs</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Blogs</span>
        </div>
        <p class="banner-lead">Insights from Ambassador School. Discover stories of student success, educational
            excellence, and community events.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>


<!-- ============ BLOG CARDS ============ -->
<section class="blog-section">
    <div class="container">

        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow eyebrow-green">What We Offer</span>
            <h2>Services That Support<br>Every Step of <span class="text-accent">Growth</span></h2>
            <div class="heading-divider"><span></span><i class="fa-solid fa-shield-halved"></i><span></span></div>
        </div>

        <div class="blog-grid">

            @forelse ($blogs ?? [] as $i => $blog)
                <article class="blog-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="blog-card-img">
                        <img src="{{ $blog->featured_image ? Storage::url($blog->featured_image) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80' }}"
                            alt="{{ $blog->title }}">
                    </div>
                    <div class="blog-card-body">
                        <h4>{{ $blog->title }}</h4>
                        <p>{{ Str::limit($blog->excerpt, 120) }}</p>
                        <a href="{{ route('blog.show', $blog->slug) ?? '#' }}" class="btn btn-dark-green btn-sm">Read
                            More</a>
                    </div>
                </article>
            @empty
                <article class="blog-card" data-aos="fade-up">
                    <div class="blog-card-img">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80"
                            alt="Innovations in Learning">
                    </div>
                    <div class="blog-card-body">
                        <h4>Innovations in Learning</h4>
                        <p>Insights from Ambassador School. Discover stories of student success, educational excellence,
                            and community events.</p>
                        <a href="#" class="btn btn-dark-green btn-sm">Read More</a>
                    </div>
                </article>
            @endforelse

        </div>
    </div>
</section>

<!-- ============ BLOG PHOTO GALLERY ============ -->
{{-- <section class="blog-gallery-section">
    <div class="container blog-gallery-grid">
        <a href="#" data-aos="zoom-in">
            <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=600&q=80" alt="Classroom learning">
        </a>
        <a href="#" data-aos="zoom-in" data-aos-delay="100">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80" alt="Group study">
        </a>
        <a href="#" data-aos="zoom-in" data-aos-delay="200">
            <img src="https://images.unsplash.com/photo-1526676037777-05a232554f77?w=600&q=80" alt="Sports team">
        </a>
    </div>
</section> --}}

<!-- ============ NEWSLETTER ============ -->
<section class="newsletter-section" data-aos="fade-up">
    <div class="container newsletter-inner">
        <div class="newsletter-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
        <div class="newsletter-text">
            <h4>Stay Connected</h4>
            <p>Subscribe to our newsletter for the latest updates, events and news.</p>
        </div>
        <form class="newsletter-form" id="newsletterForm" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-dark-green">Subscribe</button>
        </form>
    </div>
    <div class="newsletter-leaf"></div>
</section>

<!-- ============ FOOTER (identical across the site) ============ -->
@include('web::layouts.footer')
