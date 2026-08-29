@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner"
    style="background:linear-gradient(rgba(107,20,40,.88), rgba(20,67,47,.55)), url('https://images.unsplash.com/photo-1544717305-2782549b5136?w=1600&q=80') center 30%/cover;">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>ECA</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">ECA</span>
        </div>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ EMPOWERING TALENTS INTRO + ACTIVITY CARDS ============ -->
<section class="eca-intro">
    <div class="container eca-intro-grid">

        <div class="eca-intro-text" data-aos="fade-right">
            <h2>{{ $ecaPost->title ?? 'Empowering Talents. Building Leaders.' }}</h2>
            <p>{{ $ecaPost->description ?? 'At Ambassador School, our co-curricular activities (ECA) are designed to nurture creativity, develop skills, and encourage students to explore their passions beyond the classroom.' }}
            </p>

            <div class="why-eca-card">
                <h4>Why ECA Matters?</h4>
                <div class="check-list-content">
                    {!! $ecaPost->content ?? '' !!}
                </div>
                <a href="{{ route('contact') ?? '#' }}" class="btn btn-dark-green">Join an Activity</a>
            </div>
        </div>

        <div class="eca-cards-grid">
            @php
                $ecaColors = ['bg-maroon', 'bg-gold', 'bg-green', 'bg-purple', 'bg-blue', 'bg-maroon2'];
            @endphp

            @forelse ($ecaCards ?? [] as $i => $card)
                <div class="eca-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="eca-card-img">
                        <img src="{{ $card->image ? Storage::url($card->image) : 'https://images.unsplash.com/photo-1555597673-b21d5c935865?w=500&q=80' }}"
                            alt="{{ $card->title }}">
                    </div>
                    <div class="eca-card-icon {{ $ecaColors[$i % count($ecaColors)] }}">
                        @if ($card->icon)
                            <i class="{{ $card->icon }}"></i>
                        @else
                            <i class="fa-solid fa-star"></i>
                        @endif
                    </div>
                    <div class="eca-card-body">
                        <h4>{{ $card->title }}</h4>
                        <p>{{ Str::limit($card->description, 120) }}</p>
                        <a href="{{ route('eca.show', $card->slug) }}" class="eca-view-link">View Activities <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            @empty
                <div class="eca-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="eca-card-img">
                        <img src="https://images.unsplash.com/photo-1555597673-b21d5c935865?w=500&q=80"
                            alt="Sports & Fitness">
                    </div>
                    <div class="eca-card-icon bg-gold">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                    <div class="eca-card-body">
                        <h4>Sports &amp; Fitness</h4>
                        <p>Promoting physical well-being, discipline, and teamwork through various sports and fitness
                            programs.</p>
                        <a href="{{ route('eca.show', $card->slug) }}" class="eca-view-link">View Activities <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>

<!-- rest of the page (testimonial callout, info strip, footer) stays exactly as-is, unchanged -->

<!-- ============ TESTIMONIAL CALLOUT ============ -->
<section class="eca-quote-section">
    <div class="container">
        <div class="eca-quote-card" data-aos="fade-up">
            <div class="quote-icon"><i class="fa-solid fa-quote-left"></i></div>
            <div class="eca-quote-text">
                <p>Co-curricular activities at Ambassador School have helped me discover my passion and build skills
                    that go beyond academics. It's where I grow, learn, and have fun!</p>
                <span>— Ananya Sharma, Grade 10</span>
            </div>
            <div class="eca-quote-avatar">
                <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=200&q=80" alt="Ananya Sharma">
            </div>
        </div>
    </div>
</section>

<!-- ============ THIN INFO STRIP ============ -->
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
@include('web::layouts.footer')
