@include('web::layouts.header')
<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Events</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Events</span>
        </div>
        <p class="banner-lead">Discover, participate and celebrate! Stay updated with all the upcoming events and
            activities.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ FEATURED EVENTS ============ -->
<section class="featured-events-section">
    <div class="container">
        <div class="featured-head" data-aos="fade-up">
            <div>
                <h2>Featured Events</h2>
                <div class="tri-divider"><span></span><span></span><span></span></div>
            </div>
            <div class="featured-nav">
                <button id="featPrev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
                <button id="featNext" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="swiper featuredSwiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">

                @forelse ($featuredEvents ?? [] as $event)
                    <div class="swiper-slide">
                        <div class="featured-card"
                            style="background-image:url('{{ $event->image ? Storage::url($event->image) : 'https://images.unsplash.com/photo-1583211472083-1e529b6b8f6d?w=700&q=80' }}')">
                            <div class="featured-card-body">
                                @if (\Carbon\Carbon::parse($event->start_date)->isFuture())
                                    <span class="featured-badge">Upcoming</span>
                                @endif
                                <h4>{{ $event->title }}</h4>
                                <div class="featured-meta">
                                    <span><i class="fa-solid fa-calendar-days"></i>
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                        @if ($event->end_date && \Carbon\Carbon::parse($event->end_date)->format('H:i') !== '00:00')
                                            · {{ \Carbon\Carbon::parse($event->start_date)->format('h:i A') }} –
                                            {{ \Carbon\Carbon::parse($event->end_date)->format('h:i A') }}
                                        @endif
                                    </span>
                                    <span><i class="fa-solid fa-location-dot"></i>
                                        {{ $event->venue ?? $event->location }}</span>
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="featured-card"
                            style="background-image:url('https://images.unsplash.com/photo-1583211472083-1e529b6b8f6d?w=700&q=80')">
                            <div class="featured-card-body">
                                <h4>Cultural Fest 2025</h4>
                                <div class="featured-meta">
                                    <span><i class="fa-solid fa-calendar-days"></i> 18 May 2025</span>
                                    <span><i class="fa-solid fa-location-dot"></i> School Auditorium</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="featured-card"
                            style="background-image:url('https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=700&q=80')">
                            <div class="featured-card-body">
                                <span class="featured-badge">Upcoming</span>
                                <h4>Annual Sports Day 2025</h4>
                                <div class="featured-meta">
                                    <span><i class="fa-solid fa-calendar-days"></i> 25 May · 8:00 AM – 2:00 PM</span>
                                    <span><i class="fa-solid fa-location-dot"></i> School Ground</span>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- ============ UPCOMING EVENTS + CALENDAR ============ -->
<section class="events-main-section">
    <div class="container events-main-grid">

        <div class="events-upcoming-col" data-aos="fade-up">
            <div class="panel-head">
                <div>
                    <h3>Upcoming Events</h3>
                    <div class="heading-bar"></div>
                </div>
                <a href="#" class="view-all">View All Events <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="upcoming-list">
                @php
                    $tagColors = [
                        'sports' => 'bg-maroon',
                        'academic' => 'bg-gold',
                        'cultural' => 'bg-maroon2',
                        'wellness' => 'bg-green',
                        'ceremony' => 'bg-gold',
                    ];
                    $fallbackColors = ['bg-maroon', 'bg-gold', 'bg-maroon2', 'bg-green'];
                @endphp

                @forelse ($upcomingEvents ?? [] as $i => $event)
                    <div class="upcoming-row">
                        <div class="upcoming-date">
                            <span class="day">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                            <span class="mon">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</span>
                        </div>
                        <img src="{{ $event->image ? Storage::url($event->image) : 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200&q=80' }}"
                            alt="{{ $event->title }}"
                            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200&q=80'">
                        <div class="upcoming-body">
                            <h5>{{ $event->title }}</h5>
                            <p>{{ $event->short_description }}</p>
                            <div class="upcoming-meta">
                                <span><i class="fa-regular fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}
                                    @if ($event->end_date)
                                        - {{ \Carbon\Carbon::parse($event->end_date)->format('g:i A') }}
                                    @endif
                                </span>
                                <span><i class="fa-solid fa-location-dot"></i>
                                    {{ $event->venue ?? $event->location }}</span>
                            </div>
                        </div>
                        @if ($event->category ?? false)
                            <span
                                class="upcoming-tag {{ $tagColors[strtolower($event->category)] ?? $fallbackColors[$i % count($fallbackColors)] }}">
                                {{ ucfirst($event->category) }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="upcoming-row">
                        <div class="upcoming-date"><span class="day">25</span><span class="mon">MAY 2025</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200&q=80"
                            alt="Annual Sports Day">
                        <div class="upcoming-body">
                            <h5>Annual Sports Day 2025</h5>
                            <p>A day full of energy, teamwork and celebration of sportsmanship.</p>
                            <div class="upcoming-meta">
                                <span><i class="fa-regular fa-clock"></i> 8:00 AM - 2:00 PM</span>
                                <span><i class="fa-solid fa-location-dot"></i> School Ground</span>
                            </div>
                        </div>
                        <span class="upcoming-tag bg-maroon">Sports</span>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="events-sidebar" data-aos="fade-up" data-aos-delay="100">
            <div class="calendar-card" id="calendar-card">
                @include('web::partials.calendar')
            </div>

            <div class="calendar-promo">
                <i class="fa-solid fa-calendar-check"></i>
                <h5>Never Miss an Event!</h5>
                <p>Subscribe to our calendar and get notified about all school events.</p>
                <a href="#" class="btn btn-dark-green btn-sm">Subscribe Now</a>
            </div>
        </div>

    </div>
</section>

<!-- ============ INFO STRIP ============ -->
<section class="info-strip-section">
    <div class="container">
        <div class="info-strip">
            <div class="info-strip-item" data-aos="fade-up">
                <div class="info-strip-icon"><i class="fa-solid fa-calendar-days" style="color:var(--green)"></i>
                </div>
                <div>
                    <h4>Diverse Events</h4>
                    <p>From academics to arts, sports to wellness.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="100">
                <div class="info-strip-icon"><i class="fa-solid fa-people-group" style="color:var(--maroon)"></i>
                </div>
                <div>
                    <h4>Student Participation</h4>
                    <p>Encouraging leadership, teamwork and creativity.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="200">
                <div class="info-strip-icon"><i class="fa-solid fa-trophy" style="color:var(--gold-dark)"></i>
                </div>
                <div>
                    <h4>Memorable Moments</h4>
                    <p>Building memories that last a lifetime.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="300">
                <div class="info-strip-icon"><i class="fa-solid fa-bell" style="color:var(--green)"></i></div>
                <div>
                    <h4>Stay Updated</h4>
                    <p>Regular updates so you never miss a moment.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ NEWSLETTER ============ -->
<section class="newsletter-section" data-aos="fade-up">
    <div class="container">
        <div class="newsletter-card">
            <div class="newsletter-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
            <div class="newsletter-text">
                <h4>Stay Updated with Our Events</h4>
                <p>Subscribe to our newsletter and never miss an update.</p>
            </div>
            <form class="newsletter-form" id="newsletterForm">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </div>
    </div>
</section>
<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.cal-nav');
        if (!link) return;

        e.preventDefault();

        const month = link.dataset.month;
        const year = link.dataset.year;
        const url = `{{ route('events.calendar.partial') }}?month=${month}&year=${year}`;

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('calendar-card').innerHTML = html;
                const newUrl = `${window.location.pathname}?month=${month}&year=${year}`;
                window.history.pushState({
                    month,
                    year
                }, '', newUrl);
            })
            .catch(err => console.error('Calendar load failed:', err));
    });

    window.addEventListener('popstate', function() {
        const params = new URLSearchParams(window.location.search);
        const month = params.get('month') || {{ now()->month }};
        const year = params.get('year') || {{ now()->year }};

        fetch(`{{ route('events.calendar.partial') }}?month=${month}&year=${year}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('calendar-card').innerHTML = html;
            });
    });
</script>
@include('web::layouts.footer')
