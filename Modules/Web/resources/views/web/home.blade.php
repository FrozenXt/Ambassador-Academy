@include('web::layouts.header')
<!-- ============ HERO ============ -->
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-text" data-aos="fade-right" data-aos-duration="900">
            <h1>{!! $bannerContent->title ??
                'Nurturing <span class="text-accent">Young Minds,</span><br>Building Bright Futures' !!}</h1>
            <p>{{ $bannerContent->subtitle ?? 'A place where academic excellence meets character development and every student is empowered to shine.' }}
            </p>
            <div class="hero-btns">
                <a href="#academics" class="btn btn-primary">Discover More <i class="fa-solid fa-arrow-right"></i></a>
                <a href="{{ route('apply') }}" class="btn btn-outline">Admissions Open</a>
            </div>
        </div>

        <div class="hero-media" data-aos="fade-left" data-aos-duration="900">
            <div class="hero-shape"></div>
            <div class="swiper heroSlider">
                <div class="swiper-wrapper">
                    @forelse ($bannerImages as $banner)
                        <div class="swiper-slide">
                            <img src="{{ $banner->path ? Storage::url($banner->path) : asset('image/default-banner.jpg') }}"
                                alt="{{ $banner->title ?? 'Ambassador School banner image' }}">
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=900&q=80"
                                alt="Ambassador School students in uniform">
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination hero-pagination"></div>
            </div>
            <button class="hero-arrow hero-prev" id="heroPrev" aria-label="Previous banner"><i
                    class="fa-solid fa-chevron-left"></i></button>
            <button class="hero-arrow hero-next" id="heroNext" aria-label="Next banner"><i
                    class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Feature strip (static, unchanged) -->
    <div class="container">
        <div class="feature-strip" data-aos="fade-up" data-aos-duration="800">
            <div class="feature-item">
                <div class="feature-icon bg-maroon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <h4>Academic Excellence</h4>
                    <p>World-class curriculum and innovative teaching methods.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon bg-gold"><i class="fa-solid fa-people-group"></i></div>
                <div>
                    <h4>Holistic Development</h4>
                    <p>Nurturing the mind, body and character.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon bg-green"><i class="fa-solid fa-heart"></i></div>
                <div>
                    <h4>Caring Environment</h4>
                    <p>A safe, inclusive and supportive community.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon bg-maroon2"><i class="fa-solid fa-earth-asia"></i></div>
                <div>
                    <h4>Global Perspective</h4>
                    <p>Preparing students to thrive in a global world.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ WELCOME ============ -->
<section class="welcome-section">
    <div class="container welcome-inner">
        <div class="welcome-text" data-aos="fade-right">
            <span class="eyebrow eyebrow-green">{{ $storyPost->subtitle ?? 'Welcome to' }}</span>
            <h2>{{ $storyPost->title ?? 'Ambassador School' }}</h2>
            <p>{{ $storyPost->description ?? 'At Ambassador School, we believe every child is unique and has the potential to achieve greatness. Our mission is to provide quality education that inspires curiosity, creativity and compassion.' }}
            </p>

            <ul class="check-list">
                @forelse ($checkListItems ?? [] as $item)
                    <li><i class="fa-solid fa-circle-check"></i> {!! $item !!}</li>
                @empty
                    <li><i class="fa-solid fa-circle-check"></i> Student-Centered Learning</li>
                    <li><i class="fa-solid fa-circle-check"></i> Values-Based Education</li>
                    <li><i class="fa-solid fa-circle-check"></i> Modern Infrastructure</li>
                    <li><i class="fa-solid fa-circle-check"></i> Strong Community Partnerships</li>
                @endforelse
            </ul>

            <a href="{{ url('about') }}" class="btn btn-primary">About Us <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="welcome-media" data-aos="fade-left">
            <img src="{{ $storyPost && $storyPost->image ? Storage::url($storyPost->image) : 'https://images.unsplash.com/photo-1580582932707-8f0e5a9c1c2a?w=900&q=80' }}"
                alt="{{ $storyPost->title ?? 'Ambassador School campus' }}"
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=900&q=80'">
            <div class="badge-card">
                <i class="fa-solid fa-school"></i>
                <h3>25+</h3>
                <p>Years of<br>Excellence</p>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- ============ ACADEMICS ============ -->
<section class="academics-section" id="academics">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow eyebrow-maroon">Academics</span>
            <h2>Learning Without Limits</h2>
            <div class="heading-divider"><span></span><i class="fa-solid fa-shield-halved"></i><span></span></div>
            <p>Our comprehensive academic programs are designed to challenge, inspire and prepare students for the
                future.</p>
        </div>

        <div class="academics-grid">
            @php
                $iconColors = ['bg-maroon', 'bg-gold', 'bg-green', 'bg-maroon', 'bg-gold'];
                $icons = [
                    'fa-solid fa-shapes',
                    'fa-solid fa-pencil',
                    'fa-solid fa-book-open',
                    'fa-solid fa-graduation-cap',
                    'fa-solid fa-star',
                ];
            @endphp

            @forelse ($academics as $index => $academic)
                <div class="academic-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="academic-img">
                        <img src="{{ $academic->image ? Storage::url($academic->image) : 'https://images.unsplash.com/photo-1587616211892-b26c8e6dcd3f?w=500&q=80' }}"
                            alt="{{ $academic->title }}">
                    </div>
                    <h4>{{ $academic->title }}</h4>
                    <span class="grade">{{ $academic->description }}</span>
                    <div class="academic-content">
                        {!! $academic->content !!}
                    </div>
                    <div class="academic-icon {{ $iconColors[$index % count($iconColors)] }}">
                        <i class="{{ $icons[$index % count($icons)] }}"></i>
                    </div>
                </div>
            @empty
                <div class="academic-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="academic-img"><img
                            src="https://images.unsplash.com/photo-1587616211892-b26c8e6dcd3f?w=500&q=80"
                            alt="Early Years"></div>
                    <h4>Early Years</h4>
                    <span class="grade">Pre-KG - KG</span>
                    <p>Learn, play and grow together.</p>
                    <div class="academic-icon bg-maroon"><i class="fa-solid fa-shapes"></i></div>
                </div>
            @endforelse
        </div>

        <div class="center-btn" data-aos="fade-up">
            <a href="#" class="btn btn-dark-green">Explore Curriculum <i
                    class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- ============ STUDENT LIFE ============ -->
<section class="student-life-section">
    <div class="student-life-text" data-aos="fade-right">
        <span class="eyebrow eyebrow-light">{{ $ecaPost->subtitle ?? 'Student Life' }}</span>
        <h2>{{ $ecaPost->title ?? 'Beyond Classrooms, Beyond Boundaries' }}</h2>
        <p>{{ $ecaPost->description ?? 'From sports to arts, clubs to community service – our students explore their passions and create unforgettable memories.' }}
        </p>
        <a href="#" class="btn btn-light-outline">Explore ECA <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="student-life-gallery" data-aos="fade-left">
        @forelse ($ecaGalleryImages as $index => $item)
            <div class="gallery-item {{ $index === 0 ? 'large' : '' }}">
                <img src="{{ Storage::url($item->path) }}" alt="{{ $item->title }}">
                @if ($index !== 0)
                    <div class="gallery-caption">{{ $item->title }}</div>
                @endif
            </div>
        @empty
            <div class="gallery-item large">
                <img src="https://images.unsplash.com/photo-1526676037777-05a232554f77?w=700&q=80" alt="Football">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=500&q=80"
                    alt="Music & Performing Arts">
                <div class="gallery-caption">Music & Performing Arts</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1529390079861-591de354faf5?w=500&q=80"
                    alt="Clubs & Societies">
                <div class="gallery-caption">Clubs & Societies</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=500&q=80"
                    alt="Community Service">
                <div class="gallery-caption">Community Service</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=500&q=80"
                    alt="Leadership Programs">
                <div class="gallery-caption">Leadership Programs</div>
            </div>
        @endforelse
    </div>
</section>
<!-- ============ EVENTS & BLOG ============ -->
<section class="events-blog-section">
    <div class="container events-blog-grid">
        <div class="events-col" data-aos="fade-up">
            <div class="col-heading">
                <h3><span class="bar bar-maroon"></span> Upcoming Events</h3>
                <a href="{{ route('events') ?? '#' }}" class="view-all">View all events <i
                        class="fa-solid fa-arrow-right"></i></a>
            </div>
            <ul class="event-list">
                @php
                    $badgeColors = ['bg-maroon', 'bg-gold', 'bg-green'];
                @endphp

                @forelse ($upcomingEvents as $index => $event)
                    <li>
                        <a href="{{ route('events.show', $event->slug) }}" class="event-blog-link">
                            <div class="date-badge {{ $badgeColors[$index % count($badgeColors)] }}">
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}
                            </div>
                            <div>
                                <h5>{{ $event->title }}</h5>
                                <span
                                    class="event-date">{{ \Carbon\Carbon::parse($event->start_date)->format('l, d F Y') }}</span>
                                <p>{{ $event->short_description }}</p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li>
                        <div class="date-badge bg-maroon"><span>25</span>MAY</div>
                        <div>
                            <h5>Annual Sports Day 2025</h5>
                            <span class="event-date">Sunday, 25 May 2025</span>
                            <p>A day full of energy, teamwork and celebration.</p>
                        </div>
                    </li>
                @endforelse
            </ul>
            <a href="#" class="btn btn-outline-maroon">View All Events</a>
        </div>

        <div class="blog-col" data-aos="fade-up" data-aos-delay="150">
            <div class="col-heading">
                <h3><span class="bar bar-maroon"></span> Latest From Our Blog</h3>
                <a href="{{ route('blog') ?? '#' }}" class="view-all">View all blogs <i
                        class="fa-solid fa-arrow-right"></i></a>
            </div>
            <ul class="blog-list">
                @forelse ($latestBlogs as $blog)
                    <li>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="event-blog-link">
                            <img src="{{ $blog->featured_image ? Storage::url($blog->featured_image) : asset('image/blog-placeholder.jpg') }}"
                                alt="{{ $blog->title }}">
                            <div>
                                <h5>{{ $blog->title }}</h5>
                                <span
                                    class="event-date">{{ \Carbon\Carbon::parse($blog->published_at)->format('F d, Y') }}</span>
                            </div>
                        </a>
                    </li>
                @empty
                    <li>
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=200&q=80"
                            alt="Reading habits">
                        <div>
                            <h5>How to Encourage Reading Habits in Children</h5>
                            <span class="event-date">May 12, 2025</span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</section>

<!-- ============ TESTIMONIAL ============ -->
{{-- <section class="testimonial-section">
    <div class="testimonial-inner">
        <div class="testimonial-content">
            <div class="testimonial-badge">
                <i class="fa-solid fa-quote-left"></i>
            </div>
            <div class="testimonial-text">
                <p>{{ $testimonials->first()->content ?? 'Ambassador School has been a second home for our child. The teachers truly care and inspire.' }}
                </p>
                <span class="quote-author">—
                    {{ $testimonials->first()->name ?? 'Parent of Grade 6 Student' }},
                    {{ $testimonials->first()->position ?? 'Parent' }}</span>
            </div>
        </div>

        <div class="testimonial-media">
            <div class="media-curve"></div>
            <img src="{{ $testimonials->first()->avatar ? Storage::url($testimonials->first()->avatar) : asset('image/testimonial-students.jpg') }}"
                alt="Ambassador School students">
        </div>

        <div class="testimonial-pagination"></div>
        <button class="scroll-top" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
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
