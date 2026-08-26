@include('web::layouts.header')
<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Events</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="index.html">Home</a>
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

                <div class="swiper-slide">
                    <div class="featured-card"
                        style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&q=80')">
                        <div class="featured-card-body">
                            <h4>Science Exhibition</h4>
                            <div class="featured-meta">
                                <span><i class="fa-solid fa-calendar-days"></i> 10 June 2025</span>
                                <span><i class="fa-solid fa-location-dot"></i> Science Block</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="featured-card"
                        style="background-image:url('https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=700&q=80')">
                        <div class="featured-card-body">
                            <h4>International Yoga Day</h4>
                            <div class="featured-meta">
                                <span><i class="fa-solid fa-calendar-days"></i> 21 June 2025</span>
                                <span><i class="fa-solid fa-location-dot"></i> School Ground</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="featured-card"
                        style="background-image:url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=700&q=80')">
                        <div class="featured-card-body">
                            <h4>Investiture Ceremony</h4>
                            <div class="featured-meta">
                                <span><i class="fa-solid fa-calendar-days"></i> 30 June 2025</span>
                                <span><i class="fa-solid fa-location-dot"></i> School Auditorium</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- ============ UPCOMING EVENTS + CALENDAR ============ -->
<section class="events-main-section">
    <div class="container events-main-grid">

        <div class="events-upcoming" data-aos="fade-right">
            <div class="col-heading">
                <h3><span class="bar bar-maroon"></span> Upcoming Events</h3>
                <a href="#" class="view-all">View All Events <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <ul class="event-row-list">
                <li>
                    <div class="event-date-chip"><span class="em">MAY</span><span class="ed">25</span><span
                            class="ey">2025</span></div>
                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200&q=80"
                        alt="Annual Sports Day">
                    <div>
                        <h5>Annual Sports Day 2025</h5>
                        <p class="ev-desc">A day full of energy, teamwork and celebration of sportsmanship.</p>
                        <div class="ev-meta"><span><i class="fa-regular fa-clock"></i>8:00 AM – 2:00
                                PM</span><span><i class="fa-solid fa-location-dot"></i>School Ground</span></div>
                    </div>
                    <span class="event-tag tag-sports">Sports</span>
                </li>
                <li>
                    <div class="event-date-chip"><span class="em">JUN</span><span class="ed">10</span><span
                            class="ey">2025</span></div>
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=200&q=80"
                        alt="Science Exhibition">
                    <div>
                        <h5>Science Exhibition</h5>
                        <p class="ev-desc">Innovative projects by our young scientists.</p>
                        <div class="ev-meta"><span><i class="fa-regular fa-clock"></i>9:00 AM – 1:00
                                PM</span><span><i class="fa-solid fa-location-dot"></i>Science Block</span></div>
                    </div>
                    <span class="event-tag tag-academic">Academic</span>
                </li>
                <li>
                    <div class="event-date-chip"><span class="em">JUN</span><span class="ed">18</span><span
                            class="ey">2025</span></div>
                    <img src="https://images.unsplash.com/photo-1583211472083-1e529b6b8f6d?w=200&q=80"
                        alt="Cultural Fest">
                    <div>
                        <h5>Cultural Fest 2025</h5>
                        <p class="ev-desc">Celebrating talent, diversity and tradition.</p>
                        <div class="ev-meta"><span><i class="fa-regular fa-clock"></i>5:00 PM – 8:00
                                PM</span><span><i class="fa-solid fa-location-dot"></i>School Auditorium</span>
                        </div>
                    </div>
                    <span class="event-tag tag-cultural">Cultural</span>
                </li>
                <li>
                    <div class="event-date-chip"><span class="em">JUN</span><span class="ed">21</span><span
                            class="ey">2025</span></div>
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=200&q=80"
                        alt="International Yoga Day">
                    <div>
                        <h5>International Yoga Day</h5>
                        <p class="ev-desc">Let's come together for a healthier and happier future.</p>
                        <div class="ev-meta"><span><i class="fa-regular fa-clock"></i>7:00 AM – 9:00
                                AM</span><span><i class="fa-solid fa-location-dot"></i>School Ground</span></div>
                    </div>
                    <span class="event-tag tag-wellness">Wellness</span>
                </li>
                <li>
                    <div class="event-date-chip"><span class="em">JUN</span><span class="ed">30</span><span
                            class="ey">2025</span></div>
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=200&q=80"
                        alt="Investiture Ceremony">
                    <div>
                        <h5>Investiture Ceremony</h5>
                        <p class="ev-desc">Empowering student leaders for the journey ahead.</p>
                        <div class="ev-meta"><span><i class="fa-regular fa-clock"></i>11:00 AM – 1:00
                                PM</span><span><i class="fa-solid fa-location-dot"></i>School Auditorium</span>
                        </div>
                    </div>
                    <span class="event-tag tag-ceremony">Ceremony</span>
                </li>
            </ul>
        </div>

        <div class="events-sidebar" data-aos="fade-left">
            <div class="events-calendar-card">
                <div class="calendar-title">
                    <div class="cal-nav"><button id="calPrev" aria-label="Previous month"><i
                                class="fa-solid fa-chevron-left"></i></button></div>
                    <h4 id="calMonthLabel">May 2025</h4>
                    <div class="cal-nav"><button id="calNext" aria-label="Next month"><i
                                class="fa-solid fa-chevron-right"></i></button></div>
                </div>
                <div class="cal-grid" id="calGrid">
                    <!-- populated by js/script.js -->
                </div>
            </div>

            <div class="calendar-cta">
                <div class="calendar-cta-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <h5>Never Miss an Event!</h5>
                    <p>Subscribe to our calendar and get notified about all school events.</p>
                    <a href="#" class="btn btn-dark-green">Subscribe Now</a>
                </div>
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
@include('web::layouts.footer')
