@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner"
    style="background:linear-gradient(rgba(107,20,40,.88), rgba(20,67,47,.55)), url('{{ $ecaItem->image ? Storage::url($ecaItem->image) : 'https://images.unsplash.com/photo-1516307365426-bea591f05011?w=1600&q=80' }}') center 30%/cover;">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>ECA Detail</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('eca') }}">ECA</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">{{ $ecaItem->title }}</span>
        </div>
        <p class="banner-lead">Discover. Develop. Excel.<br>Nurturing passions beyond the classroom.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ ECA DETAIL MAIN ============ -->
<section class="eca-detail-section">
    <div class="container eca-detail-grid">

        <!-- ===== MAIN CONTENT ===== -->
        <div class="eca-detail-main" data-aos="fade-up">

            <div class="eca-detail-img">
                <img src="{{ $ecaItem->image ? Storage::url($ecaItem->image) : 'https://images.unsplash.com/photo-1516307365426-bea591f05011?w=1000&q=80' }}"
                    alt="{{ $ecaItem->title }}">
            </div>

            <div class="eca-detail-header">
                <div class="eca-detail-icon">
                    @if ($ecaItem->icon)
                        <i class="{{ $ecaItem->icon }}"></i>
                    @else
                        <i class="fa-solid fa-star"></i>
                    @endif
                </div>
                <div class="eca-detail-header-text">
                    <h2>{{ $ecaItem->title }}</h2>
                    <span class="eca-cat-badge">{{ $ecaItem->category ?? 'Activity' }}</span>
                </div>
            </div>

            <!-- Static meta row: category / open-to / meeting days / venue are placeholders -->
            <div class="eca-meta-row">
                <div class="eca-meta-item">
                    <i class="fa-solid fa-flag"></i>
                    <div>
                        <span class="label">Category</span>
                        <span class="value">{{ $ecaItem->category ?? 'Performing Arts' }}</span>
                    </div>
                </div>
                <div class="eca-meta-item">
                    <i class="fa-solid fa-user-group"></i>
                    <div>
                        <span class="label">Open To</span>
                        <span class="value">Grades 6 – 12</span>
                    </div>
                </div>
                <div class="eca-meta-item">
                    <i class="fa-regular fa-calendar"></i>
                    <div>
                        <span class="label">Meeting Days</span>
                        <span class="value">Saturday</span>
                    </div>
                </div>
                <div class="eca-meta-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <span class="label">Venue</span>
                        <span class="value">Activity Room</span>
                    </div>
                </div>
            </div>

            <div class="eca-detail-section-block">
                <h3>About the {{ $ecaItem->title }}</h3>
                <p>{{ $ecaItem->description }}</p>
            </div>

            <div class="eca-detail-section-block">
                <h3>Objectives</h3>
                <div class="eca-objectives-content">
                    {!! $ecaItem->content !!}
                </div>
            </div>

            <!-- ===== ACTIVITIES — STATIC ===== -->
            <div class="eca-detail-section-block">
                <h3>Activities</h3>
                <div class="eca-activities-grid">
                    <div class="eca-activity-card">
                        <i class="fa-solid fa-microphone"></i>
                        <div>
                            <h5>Vocal Training</h5>
                            <p>Voice modulation, singing techniques and group harmonies.</p>
                        </div>
                    </div>
                    <div class="eca-activity-card">
                        <i class="fa-solid fa-guitar"></i>
                        <div>
                            <h5>Instrumental Sessions</h5>
                            <p>Guitar, keyboard, drums and other instruments.</p>
                        </div>
                    </div>
                    <div class="eca-activity-card">
                        <i class="fa-solid fa-music"></i>
                        <div>
                            <h5>Band Performances</h5>
                            <p>Practice and perform as a band in school events.</p>
                        </div>
                    </div>
                    <div class="eca-activity-card">
                        <i class="fa-solid fa-star"></i>
                        <div>
                            <h5>Music Events</h5>
                            <p>Participate in inter-school competitions and annual concerts.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== GALLERY ===== -->
            @if ($galleryImages->isNotEmpty())
                <div class="eca-detail-section-block">
                    <div class="eca-gallery-head">
                        <h3>Gallery</h3>
                        <a href="{{ route('gallery') }}" class="view-all">View All Photos <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="eca-gallery-grid">
                        @foreach ($galleryImages as $img)
                            <div class="eca-gallery-item">
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $ecaItem->title }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- ===== SIDEBAR ===== -->
        <aside class="eca-detail-sidebar" data-aos="fade-up" data-aos-delay="100">

            <div class="sidebar-card">
                <h5>ECA Categories</h5>
                <ul class="sidebar-cat-list eca-cat-list">
                    @foreach ($categories as $cat)
                        <li>
                            <a href="{{ route('eca') }}" class="{{ $ecaItem->id === $cat->id ? 'active' : '' }}">
                                @if ($cat->icon)
                                    <i class="{{ $cat->icon }}"></i>
                                @endif
                                {{ $cat->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-card sidebar-join-card">
                <h5>Interested in Joining?</h5>
                <p>Join the {{ $ecaItem->title }} and let your talent shine!</p>
                <a href="{{ route('apply') }}" class="btn btn-light-outline btn-block">Apply Now</a>
            </div>

            <!-- ===== CLUB INCHARGE — STATIC ===== -->
            <div class="sidebar-card">
                <h5>Club Incharge</h5>
                <div class="incharge-info">
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&q=80"
                        alt="Club Incharge">
                    <div>
                        <h6>Ms. Anjana Rai</h6>
                        <span>Music Teacher</span>
                    </div>
                </div>
                <ul class="incharge-contact">
                    <li><i class="fa-regular fa-envelope"></i> anjana.rai@ambassadorschool.edu.np</li>
                    <li><i class="fa-solid fa-phone"></i> +977-1-5435678 (Ext. 204)</li>
                </ul>
            </div>

            <!-- ===== UPCOMING PERFORMANCES — STATIC ===== -->
            <div class="sidebar-card">
                <h5>Upcoming Performances</h5>
                <div class="sidebar-recent-list">
                    <div class="sidebar-recent-item upcoming-perf-item">
                        <div class="perf-date-box">
                            <span class="mon">May</span>
                            <span class="day">24</span>
                        </div>
                        <div>
                            <h6>Summer Concert 2025</h6>
                            <span>May 24, 2025 · School Auditorium</span>
                        </div>
                    </div>
                    <div class="sidebar-recent-item upcoming-perf-item">
                        <div class="perf-date-box">
                            <span class="mon">Jun</span>
                            <span class="day">15</span>
                        </div>
                        <div>
                            <h6>Inter-School Music Fest</h6>
                            <span>June 15, 2025 · Bright Future School</span>
                        </div>
                    </div>
                    <div class="sidebar-recent-item upcoming-perf-item">
                        <div class="perf-date-box">
                            <span class="mon">Aug</span>
                            <span class="day">10</span>
                        </div>
                        <div>
                            <h6>Independence Day Celebration</h6>
                            <span>August 10, 2025 · School Events</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('events') }}" class="view-all" style="margin-top:14px; display:inline-flex;">View
                    All Events <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="sidebar-card">
                <h5>Share This Page</h5>
                <p>Encourage others to join and explore their passion.</p>
                <div class="sidebar-share-icons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($ecaItem->title) }}"
                        target="_blank" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($ecaItem->title . ' ' . request()->fullUrl()) }}"
                        target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

        </aside>

    </div>
</section>

<!-- ============ INFO STRIP ============ -->
<section class="eca-info-strip-section">
    <div class="container">
        <div class="eca-info-strip">
            <div class="eca-info-item" data-aos="fade-up">
                <div class="eca-info-icon"><i class="fa-solid fa-seedling"></i></div>
                <div>
                    <h5>Holistic Development</h5>
                    <p>Nurturing mind, body and character.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="100">
                <div class="eca-info-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div>
                    <h5>Expert Guidance</h5>
                    <p>Learn from experienced mentors and coaches.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="200">
                <div class="eca-info-icon"><i class="fa-solid fa-display"></i></div>
                <div>
                    <h5>Modern Facilities</h5>
                    <p>Well-equipped spaces for learning and practice.</p>
                </div>
            </div>
            <div class="eca-info-item" data-aos="fade-up" data-aos-delay="300">
                <div class="eca-info-icon"><i class="fa-solid fa-people-group"></i></div>
                <div>
                    <h5>Inclusive Environment</h5>
                    <p>All students are welcome, all talents are valued.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('web::layouts.footer')
