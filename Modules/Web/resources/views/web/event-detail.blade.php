@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Event Detail</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('events') }}">Events</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">{{ $event->title }}</span>
        </div>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ EVENT DETAIL MAIN ============ -->
<section class="event-detail-section">
    <div class="container event-detail-grid">

        <!-- ===== MAIN CONTENT ===== -->
        <div class="event-detail-main" data-aos="fade-up">

            <div class="event-detail-img">
                <img src="{{ $event->image ? Storage::url($event->image) : 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1000&q=80' }}"
                    alt="{{ $event->title }}">
            </div>

            <div class="event-detail-header">
                <div class="event-date-box">
                    <span class="mon">{{ $event->start_date->format('M') }}</span>
                    <span class="day">{{ $event->start_date->format('d') }}</span>
                    <span class="yr">{{ $event->start_date->format('Y') }}</span>
                </div>
                <div class="event-header-text">
                    <h2>{{ $event->title }}</h2>
                    <div class="event-header-meta">
                        <span><i class="fa-regular fa-calendar"></i>
                            {{ $event->start_date->format('l, F d, Y') }}</span>
                        <span><i class="fa-regular fa-clock"></i>
                            {{ $event->start_date->format('g:i A') }}
                            @if ($event->end_date)
                                - {{ $event->end_date->format('g:i A') }}
                            @endif
                        </span>
                        <span><i class="fa-solid fa-location-dot"></i> {{ $event->venue ?? $event->location }}</span>
                    </div>
                </div>
            </div>

            @if ($event->short_description)
                <blockquote class="event-quote">
                    <i class="fa-solid fa-quote-left"></i> {{ $event->short_description }}
                </blockquote>
            @endif

            <div class="event-detail-content">
                {!! $event->content !!}
            </div>

            <div class="event-share">
                <span>Share This Event</span>
                <div class="sidebar-share-icons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($event->title) }}"
                        target="_blank" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($event->title . ' ' . request()->fullUrl()) }}"
                        target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- ===== RELATED EVENTS ===== -->
            @if ($relatedEvents->isNotEmpty())
                <div class="related-events-section">
                    <div class="related-posts-head">
                        <h4>Related Events</h4>
                        <a href="{{ route('events') }}" class="view-all">View All Events <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>

                    <div class="related-posts-grid">
                        @foreach ($relatedEvents as $related)
                            <a href="{{ route('events.show', $related->slug) }}" class="related-post-card">
                                <div class="related-post-img-wrap">
                                    <img src="{{ $related->image ? Storage::url($related->image) : 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&q=80' }}"
                                        alt="{{ $related->title }}">
                                    <div class="related-date-badge">
                                        <span class="mon">{{ $related->start_date->format('M') }}</span>
                                        <span class="day">{{ $related->start_date->format('d') }}</span>
                                        <span class="yr">{{ $related->start_date->format('Y') }}</span>
                                    </div>
                                </div>
                                <h5>{{ $related->title }}</h5>
                                <div class="related-meta">
                                    <span><i class="fa-regular fa-calendar"></i>
                                        {{ $related->start_date->format('M d, Y') }}</span>
                                    <span><i class="fa-solid fa-location-dot"></i>
                                        {{ $related->venue ?? $related->location }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- ===== SIDEBAR ===== -->
        <aside class="event-detail-sidebar" data-aos="fade-up" data-aos-delay="100">

            <div class="sidebar-card">
                <h5>Event Details</h5>
                <div class="event-detail-rows">
                    <div class="detail-row">
                        <span class="label">Event Name</span>
                        <span class="value">{{ $event->title }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Date</span>
                        <span class="value">{{ $event->start_date->format('l, F d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Time</span>
                        <span class="value">
                            {{ $event->start_date->format('g:i A') }}
                            @if ($event->end_date)
                                - {{ $event->end_date->format('g:i A') }}
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Venue</span>
                        <span class="value">{{ $event->venue ?? $event->location }}</span>
                    </div>
                    @if ($event->organizer)
                        <div class="detail-row">
                            <span class="label">Organized By</span>
                            <span class="value">{{ $event->organizer }}</span>
                        </div>
                    @endif
                </div>

                @if ($event->registration_url)
                    <a href="{{ $event->registration_url }}" target="_blank" class="btn btn-outline-green btn-block">
                        <i class="fa-solid fa-calendar-plus"></i> Register Now
                    </a>
                @else
                    <a href="{{ route('events.calendar.ics', $event->slug) ?? '#' }}"
                        class="btn btn-outline-green btn-block">
                        <i class="fa-solid fa-calendar-plus"></i> Add to Calendar
                    </a>
                @endif
            </div>

            <div class="sidebar-card sidebar-cta">
                <h5>Stay Updated!</h5>
                <p>Subscribe to our newsletter and get updates about our upcoming events and activities.</p>
                <a href="#" class="btn btn-dark-green btn-block">Subscribe Now</a>
            </div>

            <div class="sidebar-card sidebar-contact-card">
                <h5>Have Questions?</h5>
                <p>Contact us for more information about this event.</p>
                @if ($event->contact_email || $event->contact_phone)
                    <ul class="event-contact-list">
                        @if ($event->contact_phone)
                            <li><i class="fa-solid fa-phone"></i> {{ $event->contact_phone }}</li>
                        @endif
                        @if ($event->contact_email)
                            <li><i class="fa-solid fa-envelope"></i> {{ $event->contact_email }}</li>
                        @endif
                    </ul>
                @endif
                <a href="{{ route('contact') ?? '#' }}" class="btn btn-dark-green btn-sm">Contact Us</a>
            </div>

        </aside>

    </div>
</section>

<!-- ============ INFO STRIP ============ -->
<section class="info-strip-section">
    <div class="container">
        <div class="info-strip">
            <div class="info-strip-item" data-aos="fade-up">
                <div class="info-strip-icon"><i class="fa-solid fa-people-group" style="color:var(--green)"></i>
                </div>
                <div>
                    <h4>Holistic Development</h4>
                    <p>Building academic, physical and emotional well-being.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="100">
                <div class="info-strip-icon"><i class="fa-solid fa-heart" style="color:var(--maroon)"></i></div>
                <div>
                    <h4>Community &amp; Values</h4>
                    <p>Encouraging empathy, respect and leadership.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="200">
                <div class="info-strip-icon"><i class="fa-solid fa-trophy" style="color:var(--gold-dark)"></i></div>
                <div>
                    <h4>Excellence in Education</h4>
                    <p>Inspiring students to achieve their best every day.</p>
                </div>
            </div>
            <div class="info-strip-item" data-aos="fade-up" data-aos-delay="300">
                <div class="info-strip-icon"><i class="fa-solid fa-shield" style="color:var(--green)"></i></div>
                <div>
                    <h4>Safe &amp; Supportive Campus</h4>
                    <p>A secure environment where every child thrives.</p>
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
        <form class="newsletter-form" id="newsletterForm" action="{{ route('newsletter.subscribe') }}"
            method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-dark-green">Subscribe</button>
        </form>
    </div>
    <div class="newsletter-leaf"></div>
</section>

@include('web::layouts.footer')
