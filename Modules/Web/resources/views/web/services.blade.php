@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Services</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="index.html">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Services</span>
        </div>
        <p class="banner-lead">At Ambassador School, we go beyond academics to offer a holistic environment that
            nurtures every child's potential.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ QUICK SERVICES STRIP ============ -->
@php
    $stripColors = ['bg-maroon', 'bg-gold', 'bg-green'];
@endphp

<div class="container">
    <div class="quick-services-strip" data-aos="fade-up">
        @foreach ($quickServices as $service)
            <div class="quick-service-item">
                <div class="quick-service-icon {{ $stripColors[$loop->index % count($stripColors)] }}">
                    <i class="{{ $service->icon ?? 'fa-solid fa-star' }}"></i>
                </div>
                <h4>{{ $service->title }}</h4>
                <p>{{ $service->description }}</p>
            </div>
        @endforeach
    </div>
</div>

<!-- ============ WHAT WE OFFER ============ -->
@php
    $cardColors = ['bg-maroon', 'bg-gold', 'bg-green'];
@endphp

<!-- ============ WHAT WE OFFER ============ -->
<section class="services-section">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow eyebrow-green">What We Offer</span>
            <h2>Services That Support<br>Every Step of <span class="text-accent">Growth</span></h2>
            <div class="heading-divider"><span></span><i class="fa-solid fa-shield-halved"></i><span></span></div>
        </div>

        <div class="services-grid">

            @foreach ($serviceFeatures as $service)
                @php
                    $checklist = json_decode($service->content, true) ?? [];
                @endphp
                <div class="service-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                    <div class="service-card-img">
                        <img src="{{ $service->image ? Storage::url($service->image) : asset('images/placeholder.jpg') }}"
                            alt="{{ $service->title }}">
                    </div>
                    <div class="service-card-icon {{ $cardColors[$loop->index % count($cardColors)] }}">
                        <i class="{{ $service->icon ?? 'fa-solid fa-star' }}"></i>
                    </div>
                    <div class="service-card-body">
                        <h4><a href="{{ route('service.detail', $service->slug) }}">{{ $service->title }}</a></h4>
                        <p>{{ $service->description }}</p>

                        @php
                            $checklist = [];
                            if ($service->content) {
                                // Extract text from each <p> tag
                                preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $service->content, $matches);
                                $checklist = array_filter(array_map('trim', $matches[1] ?? []), function ($item) {
                                    return $item !== '' && $item !== '<br>' && strip_tags($item) !== '';
                                });
                                $checklist = array_map('strip_tags', $checklist); // remove any inline tags like <strong>, <em>
                            }
                        @endphp

                        @if (count($checklist))
                            <ul class="service-checklist {{ count($checklist) > 4 ? 'two-col' : '' }}">
                                @foreach ($checklist as $point)
                                    <li><i class="fa-solid fa-circle-check"></i> {{ $point }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
<!-- ============ HIGHLIGHT STRIP ============ -->
<section class="eca-info-strip-section">
    <div class="container">
        <div class="eca-info-strip">
            <div class="eca-info-item" data-aos="fade-up">
                <div class="eca-info-icon"><i class="fa-solid fa-seedling"></i></div>
                <div>
                    <h5>Holistic Development</h5>
                    <p>Nurturing mind, body and character through diverse activities.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="100">
                <div class="eca-info-icon"><i class="fa-solid fa-compass"></i></div>
                <div>
                    <h5>Discover &amp; Explore</h5>
                    <p>Helping students find their passions and unlock their potential.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="200">
                <div class="eca-info-icon"><i class="fa-solid fa-people-group"></i></div>
                <div>
                    <h5>Leadership &amp; Teamwork</h5>
                    <p>Building confidence, communication, and leadership for a brighter future.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="300">
                <div class="eca-info-icon"><i class="fa-solid fa-face-smile"></i></div>
                <div>
                    <h5>Fun &amp; Engagement</h5>
                    <p>Creating joyful experiences that make learning meaningful.</p>
                </div>
            </div>
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
        <form class="newsletter-form" id="newsletterForm" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-dark-green">Subscribe</button>
        </form>
    </div>
    <div class="newsletter-leaf"></div>
</section>

<!-- ============ FOOTER (identical to home & about pages) ============ -->
@include('web::layouts.footer')
