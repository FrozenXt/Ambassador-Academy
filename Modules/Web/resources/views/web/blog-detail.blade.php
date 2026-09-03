@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Blog Detail</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('blog') }}">Blog</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">{{ $blog->title }}</span>
        </div>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ BLOG DETAIL MAIN ============ -->
<section class="blog-detail-section">
    <div class="container blog-detail-grid">

        <!-- ===== MAIN CONTENT ===== -->
        <div class="blog-detail-main" data-aos="fade-up">

            <div class="blog-detail-img">
                <img src="{{ $blog->featured_image ? Storage::url($blog->featured_image) : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1000&q=80' }}"
                    alt="{{ $blog->title }}">
            </div>

            <div class="blog-detail-meta">
                @if ($blog->category)
                    <span class="blog-cat-badge">{{ $blog->category->name }}</span>
                @endif
                <span><i class="fa-regular fa-calendar"></i> {{ $blog->published_at?->format('M d, Y') }}</span>
                <span><i class="fa-regular fa-user"></i> By {{ $blog->author->name ?? 'Admin' }}</span>
                <span><i class="fa-regular fa-clock"></i> {{ $blog->reading_time }}</span>
                @if ($blog->allow_comments)
                    <span><i class="fa-regular fa-comment"></i> 0 Comments</span>
                @endif
            </div>

            <h2 class="blog-detail-title">{{ $blog->title }}</h2>

            <div class="blog-detail-content">
                {!! $blog->content !!}
            </div>

            <!-- ===== RELATED POSTS ===== -->
            @if ($relatedPosts->isNotEmpty())
                <div class="related-posts-section">
                    <div class="related-posts-head">
                        <h4>Related Posts</h4>
                        <a href="{{ route('blog') }}" class="view-all">View All Blogs <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>

                    <div class="related-posts-grid">
                        @foreach ($relatedPosts as $related)
                            <article class="related-post-card">
                                <img src="{{ $related->featured_image ? Storage::url($related->featured_image) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&q=80' }}"
                                    alt="{{ $related->title }}">
                                <span class="related-date">{{ $related->published_at?->format('M d, Y') }}</span>
                                <h5>{{ $related->title }}</h5>
                                <a href="{{ route('blog.show', $related->slug) }}" class="read-more-link">Read More <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- ===== SIDEBAR ===== -->
        <aside class="blog-detail-sidebar" data-aos="fade-up" data-aos-delay="100">

            <form action="{{ route('blog') }}" method="GET" class="sidebar-search">
                <input type="text" name="q" placeholder="Search blogs...">
                <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            @if ($categories->isNotEmpty())
                <div class="sidebar-card">
                    <h5>Categories</h5>
                    <ul class="sidebar-cat-list">
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('blog', ['category' => $category->slug]) }}"
                                    class="{{ $blog->category_id === $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($recentPosts->isNotEmpty())
                <div class="sidebar-card">
                    <h5>Recent Posts</h5>
                    <div class="sidebar-recent-list">
                        @foreach ($recentPosts as $recent)
                            <a href="{{ route('blog.show', $recent->slug) }}" class="sidebar-recent-item">
                                <img src="{{ $recent->featured_image ? Storage::url($recent->featured_image) : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=150&q=80' }}"
                                    alt="{{ $recent->title }}">
                                <div>
                                    <h6>{{ Str::limit($recent->title, 45) }}</h6>
                                    <span>{{ $recent->published_at?->format('M d, Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="sidebar-card sidebar-cta">
                <h5>Stay Updated!</h5>
                <p>Subscribe to our newsletter and get our latest blogs directly in your inbox.</p>
                <a href="#" class="btn btn-dark-green btn-block">Subscribe Now</a>
            </div>

            <div class="sidebar-card">
                <h5>Share This Post</h5>
                <p>Inspire others by sharing this blog.</p>
                <div class="sidebar-share-icons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($blog->title) }}"
                        target="_blank" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . request()->fullUrl()) }}"
                        target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="sidebar-card sidebar-contact-card">
                <h5>Have Questions?</h5>
                <p>Contact us for more information about the blog.</p>
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
                <div class="info-strip-icon"><i class="fa-solid fa-calendar-days" style="color:var(--green)"></i></div>
                <div>
                    <h4>Diverse Events</h4>
                    <p>From academic to arts, sports to wellness.</p>
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
                <div class="info-strip-icon"><i class="fa-solid fa-trophy" style="color:var(--gold-dark)"></i></div>
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
