@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>About Us</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="index.html">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">About Us</span>
        </div>
        <p class="banner-lead">At Ambassador School, we go beyond academics to offer a holistic environment that
            nurtures every child's potential.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ WHO WE ARE ============ -->
<section class="who-we-are">
    <div class="container who-we-are-grid">
        <div class="who-we-are-text" data-aos="fade-right">
            <span class="eyebrow eyebrow-green">{{ $aboutPost->subtitle }}</span>
            <h2>{!! $aboutPost->title !!}</h2>
            <div class="tri-divider"><span></span><span></span><span></span></div>
            <p>{!! $aboutPost->content !!}</p>
            <a href="#" class="btn btn-dark-green">Our Vision & Mission <i
                    class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="who-we-are-media" data-aos="fade-left">
            <img src="{{ $aboutPost->image ? Storage::url($aboutPost->image) : asset('images/placeholder.jpg') }}"
                alt="{{ $aboutPost->title }}">
            <div class="badge-card">
                <i class="fa-solid fa-school"></i>
                <h3>20+</h3>
                <p>Years of<br>Excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ CHAIRPERSON MESSAGE ============ -->

<section class="chairperson-section">
    <div class="container chairperson-grid">
        <div class="chairperson-media" data-aos="fade-right">
            <img src="{{ $chairpersonPost->image ? Storage::url($chairpersonPost->image) : asset('images/placeholder.jpg') }}"
                alt="{{ $chairpersonPost->name ?? 'Mr. Mohan Malla' }}">
            <div class="chairperson-tag">
                <i class="fa-solid fa-quote-left"></i>
                <strong>{{ $chairpersonPost->name ?? 'Mr. Mohan Malla' }}</strong>
                <span>{{ $chairpersonPost->position }}</span>
            </div>
        </div>

        <div class="chairperson-content" data-aos="fade-left">
            <span class="eyebrow eyebrow-green">{{ $chairpersonPost->subtitle }}</span>
            <h2>{!! $chairpersonPost->title !!}</h2>
            <div class="heading-divider"><span></span><i class="fa-solid fa-shield-halved"></i><span></span></div>
            <div class="chairperson-quote">
                <span class="quote-mark"><i class="fa-solid fa-quote-left"></i></span>
                {!! $chairpersonPost->content !!}
                <span class="quote-mark end"><i class="fa-solid fa-quote-left"></i></span>
            </div>
        </div>
    </div>
</section>

<!-- ============ OUR JOURNEY ============ -->
<section class="journey-section">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow eyebrow-green">Our Journey</span>
            <h2>A Legacy of Growth and <span class="text-accent">Excellence</span></h2>
        </div>

        <div class="journey-track">
            <div class="journey-node" data-aos="fade-up" data-aos-delay="0">
                <div class="journey-icon bg-gold"><i class="fa-solid fa-landmark"></i></div>
                <div class="journey-year" style="color:var(--gold-dark)">1998+</div>
                <h4>The Beginning</h4>
                <p>Ambassador School was established with a vision to provide quality education and value-based
                    learning.</p>
            </div>
            <div class="journey-node" data-aos="fade-up" data-aos-delay="100">
                <div class="journey-icon bg-maroon"><i class="fa-solid fa-people-group"></i></div>
                <div class="journey-year" style="color:var(--maroon)">2005</div>
                <h4>Growing Together</h4>
                <p>Our community grew stronger with more students, dedicated educators, and modern facilities.</p>
            </div>
            <div class="journey-node" data-aos="fade-up" data-aos-delay="200">
                <div class="journey-icon bg-green"><i class="fa-solid fa-medal"></i></div>
                <div class="journey-year" style="color:var(--green)">2015</div>
                <h4>Milestones Achieved</h4>
                <p>Introduced innovative programs, expanded infrastructure, and celebrated academic success.</p>
            </div>
            <div class="journey-node" data-aos="fade-up" data-aos-delay="300">
                <div class="journey-icon bg-orange"><i class="fa-solid fa-rocket"></i></div>
                <div class="journey-year" style="color:#e08a2b">Today & Beyond</div>
                <h4>Continuing our journey</h4>
                <p>Continuing our journey towards excellence, innovation, and holistic development.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ STATS BAR ============ -->

<section class="stats-bar">
    <div class="stat-block sb-maroon" data-aos="fade-up">
        <i class="{{ optional($counters[0] ?? null)->icon ?? 'fa-solid fa-graduation-cap' }} stat-icon"></i>
        <div>
            <h3 data-count="{{ optional($counters[0] ?? null)->number ?? 1200 }}"
                data-suffix="{{ optional($counters[0] ?? null)->suffix ?? '+' }}">0+</h3>
            <p>{{ optional($counters[0] ?? null)->title ?? 'Students' }}</p>
        </div>
    </div>
    <div class="stat-block sb-gold" data-aos="fade-up" data-aos-delay="100">
        <i class="{{ optional($counters[1] ?? null)->icon ?? 'fa-solid fa-chalkboard-user' }} stat-icon"></i>
        <div>
            <h3 data-count="{{ optional($counters[1] ?? null)->number ?? 80 }}"
                data-suffix="{{ optional($counters[1] ?? null)->suffix ?? '+' }}">0+</h3>
            <p>{{ optional($counters[1] ?? null)->title ?? 'Experienced Teachers' }}</p>
        </div>
    </div>
    <div class="stat-block sb-green" data-aos="fade-up" data-aos-delay="200">
        <i class="{{ optional($counters[2] ?? null)->icon ?? 'fa-solid fa-book-open-reader' }} stat-icon"></i>
        <div>
            <h3 data-count="{{ optional($counters[2] ?? null)->number ?? 25 }}"
                data-suffix="{{ optional($counters[2] ?? null)->suffix ?? '+' }}">0+</h3>
            <p>{{ optional($counters[2] ?? null)->title ?? 'Years of Excellence' }}</p>
        </div>
    </div>
    <div class="stat-block sb-maroon2" data-aos="fade-up" data-aos-delay="300">
        <i class="{{ optional($counters[3] ?? null)->icon ?? 'fa-solid fa-trophy' }} stat-icon"></i>
        <div>
            <h3 data-count="{{ optional($counters[3] ?? null)->number ?? 100 }}"
                data-suffix="{{ optional($counters[3] ?? null)->suffix ?? '+' }}">0+</h3>
            <p>{{ optional($counters[3] ?? null)->title ?? 'Awards & Recognitions' }}</p>
        </div>
    </div>
</section>

<!-- ============ NEWSLETTER ============ -->
{{-- <section class="newsletter-section variant-green" data-aos="fade-up">
    <div class="container newsletter-inner">
        <div class="newsletter-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
        <div class="newsletter-text">
            <h4>Stay Connected</h4>
            <p>Subscribe to our newsletter for the latest updates, events and inspiring stories.</p>
        </div>
        <form class="newsletter-form" id="newsletterForm">
            <input type="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-dark-green">Subscribe</button>
        </form>
    </div>
</section> --}}

<!-- ============ FOOTER (identical to home page) ============ -->

@include('web::layouts.footer')
