@php
    $pageTitle = 'Home | Sultan Arabic Restaurant';
    $pageDescription =
        "Experience authentic Arabic cuisine, premium beverages, and luxury dining at Sultan & Papas in Lalitpur, Kathmandu, inspired by Dubai's world-class hospitality.";
    $pageKeywords = 'Arabic restaurant Kathmandu, luxury dining Lalitpur, Middle Eastern food Nepal, Sultan and Papas';
@endphp
@include('web::layouts.header')

{{-- ── HERO BANNER ── --}}
@if ($bannerSlides->isNotEmpty())
    <div id="heroSwiper" class="swiper">
        <div class="swiper-wrapper">
            @foreach ($bannerSlides as $slide)
                <div class="swiper-slide hero-slide">
                    <div class="slide-bg" style="background-image: url('{{ Storage::url($slide->path) }}');"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <h2 class="slide-title">{{ $slide->title }}</h2>
                        <p class="slide-subtitle">"{{ $slide->description }}"</p>
                    </div>
                </div>
            @endforeach
        </div><!-- /swiper-wrapper -->

        <div class="hero-btn-prev" id="heroPrev"><iconify-icon icon="mingcute:left-line"></iconify-icon></div>
        <div class="hero-btn-next" id="heroNext"><iconify-icon icon="mingcute:right-line"></iconify-icon></div>
    </div><!-- /heroSwiper -->
@endif

{{-- ── ABOUT SECTION ── --}}
@if ($heroAlbum)
    <section class="about-section section-padding">
        <div class="container">
            <!-- Label -->
            <div class="text-center mb-5">
                <p class="label-script reveal delay-1">Sultan's Arabic Grill</p>
                <!-- Title -->
                <h2 class="title reveal delay-2">
                    {{ $heroAlbum->title }}
                </h2>
                <!-- Divider -->
                <div class="divider-gold reveal delay-3"><i></i></div>

                <!-- Description -->
                <p class="reveal delay-3">
                    {{ $heroAlbum->description }}
                </p>
            </div>

            @if ($heroImages->isNotEmpty())
                <div class="swiper aboutSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($heroImages as $img)
                            <div class="swiper-slide">
                                <div class="custom-shape-about">
                                    @if (str_ends_with(strtolower($img->path), '.mp4'))
                                        <video autoplay muted loop playsinline>
                                            <source src="{{ Storage::url($img->path) }}" type="video/mp4" />
                                        </video>
                                    @else
                                        <img src="{{ Storage::url($img->path) }}"
                                            alt="{{ $img->title ?? $heroAlbum->title }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            @endif

        </div><!-- /container -->
    </section>
@endif

{{-- ── COURSE SECTION ── --}}
@if ($categories->isNotEmpty())
    <section class="course-section section-padding">
        <div class="container">
            <!-- Label -->
            <div class="text-center mb-5">
                <p class="label-script reveal delay-1">Taste the Best </p>
                <!-- Title -->
                <h2 class="title reveal delay-2">
                    sultan's special menu
                </h2>
                <!-- Divider -->
                <div class="divider-gold reveal delay-3"><i></i></div>

                <!-- Description -->
                <p class="reveal delay-3">
                    Experience the rich flavors of authentic Arabic cuisine, crafted with premium ingredients and served
                    in an atmosphere of elegance, comfort, and exceptional hospitality.
                </p>
            </div>

            {{-- ── Tabs ── --}}
            <nav class="course-tabs" role="tablist">
                @foreach ($categories as $i => $category)
                    <button class="tab-btn {{ $i === 0 ? 'active' : '' }} reveal delay-4" data-tab="{{ $category->id }}"
                        role="tab"><svg xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54"
                            fill="none">
                            <path class="btn-shape"
                                d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                        </svg>
                        <span>{{ $category->name }}</span></button>
                @endforeach
            </nav>

            {{-- ── Tab Content ── --}}
            @foreach ($categories as $i => $category)
                <div id="tab-{{ $category->id }}"
                    class="course-tab-wrapper {{ $i === 0 ? 'active' : '' }} container-fluid px-0"
                    style="max-width:900px;margin:auto;">
                    <div class="course-grid">

                        @forelse ($category->products as $item)
                            <div class="course-card reveal delay-5" data-name="{{ $item->name }}"
                                data-desc="{{ $item->description }}" data-price="NPR {{ $item->price }}">
                                <div class="card-img-wrap">
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                                </div>
                                <div class="card-body-text">
                                    <div class="card-name">{{ $item->name }}</div>
                                    <div class="card-desc">{{ $item->description }}</div>
                                    <div class="card-price">NPR {{ number_format($item->price, 2) }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-box" style="padding:24px;text-align:center;color:#9ca3af;">
                                No items in this category yet.
                            </div>
                        @endforelse

                    </div>
                </div>
            @endforeach

            <!-- ── View All ── -->
            <div class="view-all-wrap">
                <a href="{{ route('menu') }}" class="view-all-btn reveal delay-6">View All Courses</a>
            </div>
            <!-- ── Item Modal ── -->
            <div class="item-modal-overlay reveal delay-7" id="itemModal" role="dialog" aria-modal="true">
                <div class="item-modal">
                    <button class="modal-close" id="modalClose" aria-label="Close">✕</button>
                    <img class="modal-img" id="modalImg" src="" alt="modalName">
                    <div class="modal-name" id="modalName"></div>
                    <div class="modal-desc" id="modalDesc"></div>
                    <div class="modal-price" id="modalPrice"></div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ── SERVICES SECTION ── --}}
@if ($whyChooseUsServices->isNotEmpty())
    <section class="services-section section-padding">
        <!-- Content -->
        <div class="container">
            <div class="text-center mb-5">
                <!-- Label -->
                <p class="label-script reveal delay-1">services</p>
                <!-- Title -->
                <h2 class="title reveal delay-2">
                    Why people choose us?
                </h2>
                <!-- Divider -->
                <div class="divider-gold reveal delay-3"><i></i></div>

                <!-- Description -->
                <p class="reveal delay-3">
                    From authentic Arabic flavors and luxury dining to live entertainment and Kathmandu's largest
                    rooftop bar, we deliver an experience unlike any other in the city.
                </p>
            </div>

            {{-- Service cards --}}
            <div class="row justify-content-center">
                @foreach ($whyChooseUsServices as $service)
                    <div class="col-12 col-md-4">
                        <div class="service-card reveal delay-4">
                            <div class="icon-wrap">
                                @if (!empty($service->image))
                                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}">
                                @elseif (!empty($service->icon))
                                    <iconify-icon icon="{{ $service->icon }}"></iconify-icon>
                                @endif
                            </div>
                            <div class="card-title-text">{{ $service->title }}</div>
                            <div class="card-body-text">{{ $service->description }}</div>
                        </div>
                    </div>
                @endforeach
            </div><!-- /row -->
        </div><!-- /container -->
    </section>
@endif

{{-- ── VIDEO SECTION ── --}}
@if ($heroVideoItem)
    <section class="video-section section-padding">
        <video class="video-wrapper" autoplay muted loop playsinline>
            <source src="{{ Storage::url($heroVideoItem->path) }}" type="video/mp4" />
        </video>

        <!-- Overlay -->
        <div class="video-overlay"></div>

        <!-- Text content -->
        <div class="video-content">
            <h2 class="title reveal delay-1">
                {{ $heroVideoItem->title }}
            </h2>

            <!-- Description -->
            <p class="reveal delay-2">
                {{ $heroVideoItem->description }}
            </p>
        </div>
    </section>
@endif

{{-- ── TESTIMONIAL SECTION ── --}}
@if ($testimonials->isNotEmpty())
    <section class="testimonial-section section-padding">
        <div class="container">
            <!-- Header -->
            <div class="text-center mb-5 pb-3">
                <!-- Label -->
                <p class="label-script reveal delay-1">What Our Guests Say</p>
                <!-- Title -->
                <h2 class="title reveal delay-2">
                    Guest Experiences
                </h2>
                <!-- Divider -->
                <div class="divider-gold reveal delay-3"><i></i></div>

                <!-- Description -->
                <p class="reveal delay-3">
                    Thousands of guests have made Sultan & Papas their premier social dining destination in
                    Kathmandu.
                </p>
            </div>

            <!-- Swiper -->
            <div class="swiper-outer reveal delay-4">
                <div class="swiper testimonial-swiper">
                    <div class="swiper-wrapper mb-3">
                        @foreach ($testimonials as $t)
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <span class="quote-mark">"</span>
                                    <p class="review-text">
                                        {{ $t->content }}
                                    </p>
                                    <div class="reviewer-row">
                                        <div class="reviewer-avatar">
                                            <img src="{{ asset('storage/' . $t->avatar) }}"
                                                alt="{{ $t->name }}">
                                        </div>
                                        <div>
                                            <div class="reviewer-name">{{ $t->name }}</div>
                                            <div class="reviewer-loc">{{ $t->address }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!-- /.swiper-wrapper -->
                    <div class="swiper-pagination"></div>
                </div><!-- /.swiper-outer -->
            </div>
        </div><!-- /.container -->
    </section>
@endif

{{-- ── CONTACT / LOCATION SECTION ── --}}
@include('web::layouts.location')
@include('web::layouts.footer')
