@include('web::layouts.header')

<div class="hero" id="stage">
    <!-- Fullscreen looping background video with dark overlay -->
    <div class="hero-media">
        @if (isset($bannerItem) && !empty($bannerItem->youtube_url))
            @php
                preg_match(
                    '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                    $bannerItem->youtube_url,
                    $ytMatch,
                );
                $ytId = $ytMatch[1] ?? null;
            @endphp

            @if ($ytId)
                <div class="hero-yt-wrap">
                    <iframe class="hero-video hero-video-yt" id="heroVideo"
                        src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3&disablekb=1&fs=0&playsinline=1&enablejsapi=1"
                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <div class="hero-yt-poster" id="heroYtPoster"
                        style="background-image:url('https://img.youtube.com/vi/{{ $ytId }}/maxresdefault.jpg');">
                    </div>
                </div>
            @endif
        @elseif (isset($bannerItem) && !empty($bannerItem->path) && Str::endsWith($bannerItem->path, ['.mp4', '.webm', '.ogg']))
            <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
                <source src="{{ Storage::url($bannerItem->path) }}" type="video/mp4" />
            </video>
        @elseif (isset($bannerItem) && !empty($bannerItem->path))
            <img class="hero-video" id="heroVideo" src="{{ Storage::url($bannerItem->path) }}" alt="" />
        @else
            <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
                <source src="{{ asset('image/video.mp4') }}" type="video/mp4" />
            </video>
        @endif
        <div class="hero-overlay" id="heroOverlay"></div>
    </div>
    <!-- Center content -->
    <div class="hero-content" id="heroContent">
        <p class="hero-eyebrow" id="heroEyebrow">
            {{ isset($bannerItem) ? $bannerItem->title : 'Premium Grills • Signature Cocktails • Unforgettable Nights' }}
        </p>

        <h1 class="hero-heading" id="heroHeading">
            {{ isset($bannerItem) ? $bannerItem->subtitle : "Kathmandu's Largest" }}
            <em>{{ isset($bannerItem) ? $bannerItem->description : 'Rooftop Bar & Grill' }}</em>
        </h1>

        <a href="{{ isset($bannerItem) && !empty($bannerItem->youtube_link) ? $bannerItem->youtube_link : '#reveal' }}"
            class="btn-gold" id="heroBtn">
            <span>Explore Now</span>
        </a>
    </div>

    <!-- Half circle + bottles emerging from it -->
    <div class="circle-stage" id="circleStage">
        <div class="half-circle" id="halfCircle"></div>
        <div class="half-circlebg" id="halfCirclebg"></div>
        <div class="bottle-wrap bottle-marker" id="bottleWrapHero">
            <img src="{{ isset($heroBottleBack) && !empty($heroBottleBack->path) ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                alt="" class="bottle bottle-back" id="bottleBackHero" />
            <img src="{{ isset($heroBottleFront) && !empty($heroBottleFront->path) ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                alt="" class="bottle bottle-front" id="bottleFrontHero" />
        </div>
        <div class="bottle-glow" id="bottleGlow"></div>
    </div>
</div>

<div class="reveal" id="stage1">
    <div class="container">
        <div class="row reveal-inner">
            <div class="col-md-6">
                <div class="reveal-copy">
                    <ul class="reveal-list">
                        @if (isset($revealFeatures) && count($revealFeatures) > 0)
                            @foreach ($revealFeatures as $feature)
                                <li class="reveal-item">
                                    <span class="reveal-bullet"></span>
                                    <div>
                                        <h3 class="reveal-heading split-target">
                                            {!! $feature->title !!}
                                        </h3>
                                        <p class="reveal-sub">{{ $feature->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <!-- Default fallback content -->
                            <li class="reveal-item">
                                <span class="reveal-bullet"></span>
                                <div>
                                    <h3 class="reveal-heading split-target">Rooftop Bar & Grill</h3>
                                    <p class="reveal-sub">Kathmandu's Ultimate Social Destination</p>
                                </div>
                            </li>
                            <li class="reveal-item">
                                <span class="reveal-bullet"></span>
                                <div>
                                    <h3 class="reveal-heading split-target">Premium <em class="gold">Whisky</em></h3>
                                    <p class="reveal-sub">Raise a Glass Above the City</p>
                                </div>
                            </li>
                            <li class="reveal-item">
                                <span class="reveal-bullet"></span>
                                <div>
                                    <h3 class="reveal-heading split-target">Fine <em class="gold">Wine</em> Signature
                                        <em class="gold">Cocktails</em>
                                    </h3>
                                    <p class="reveal-sub">From Exceptional Whiskies to Fine Wines</p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="reveal-visual" aria-hidden="true">
                    <div class="bottle-wrap bottle-marker" id="bottleWrapReveal">
                        <img src="{{ isset($heroBottleBack) && !empty($heroBottleBack->path) ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                            alt="" class="bottle bottle-back" id="bottleBackReveal" />
                        <img src="{{ isset($heroBottleFront) && !empty($heroBottleFront->path) ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                            alt="" class="bottle bottle-front" id="bottleFrontReveal" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="curve" id="stage2">
    <div class="container">
        <div class="curve-stage" id="curveStage">
            <div class="half-curve" id="halfCurve"></div>
            <div class="bottle-wrap bottle-marker" id="bottleWrapCurve">
                <img src="{{ isset($heroBottleBack) && !empty($heroBottleBack->path) ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                    alt="" class="bottle bottle-back" id="bottleBackCurve" />
                <img src="{{ isset($heroBottleFront) && !empty($heroBottleFront->path) ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                    alt="" class="bottle bottle-front" id="bottleFrontCurve" />
            </div>
        </div>
        <div class="features">
            <div class="feature-row" id="featureRow">
                @if (isset($papasFeatures) && count($papasFeatures) > 0)
                    @foreach ($papasFeatures as $feature)
                        <div class="feature {{ $loop->last ? 'spaced' : '' }}">
                            <div class="ring">
                                <iconify-icon icon="{{ $feature->icon }}"></iconify-icon>
                            </div>
                            <span>{{ $feature->title }}</span>
                        </div>
                    @endforeach
                @else
                    <!-- Default fallback features -->
                    <div class="feature">
                        <div class="ring">
                            <iconify-icon icon="roentgen:apartments-2-story-skillion-roof"></iconify-icon>
                        </div>
                        <span>RoofTop Experience</span>
                    </div>
                    <div class="feature">
                        <div class="ring">
                            <iconify-icon icon="boxicons:wine-alt-filled"></iconify-icon>
                        </div>
                        <span>Premium Bar</span>
                    </div>
                    <div class="feature">
                        <div class="ring">
                            <iconify-icon icon="material-symbols:outdoor-grill-rounded"></iconify-icon>
                        </div>
                        <span>Signature Grill</span>
                    </div>
                    <div class="feature">
                        <div class="ring">
                            <iconify-icon icon="mdi:wine"></iconify-icon>
                        </div>
                        <span>Fine Wines</span>
                    </div>
                    <div class="feature">
                        <div class="ring">
                            <iconify-icon icon="mdi:dance-ballroom"></iconify-icon>
                        </div>
                        <span>Private Events</span>
                    </div>
                    <div class="feature spaced">
                        <div class="ring">
                            <iconify-icon icon="majesticons:music"></iconify-icon>
                        </div>
                        <span>Live Entertainment</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="burger-wrap burger-marker" id="burgerWrapCurve">
        <img src="{{ asset('image/b3.png') }}" alt="" class="burger" id="burgerCurve" />
    </div>
</div>

<div class="story" id="stage3">
    <div class="container">
        <div class="row row-story align-items-center g-4">
            <div class="col-lg-6">
                <h2 class="story-title">{{ isset($storyPost) ? $storyPost->title : 'The Story' }}</h2>

                @if (isset($storyPost) && !empty($storyPost->content))
                    {!! $storyPost->content !!}
                @else
                    <p class="story-text">
                        <strong>PAPA&rsquo;s Bar &amp; Grill</strong> was born at the
                        crossroads of Middle Eastern tradition and Nepali warmth.
                        Complementing the experience is Papas Bar &amp; Grill, home to
                        Kathmandu&rsquo;s largest rooftop bar, offering handcrafted
                        cocktails, grilled specialties, live entertainment, breathtaking
                        city views, and unforgettable nights.
                    </p>
                    <p class="story-text">
                        From authentic Arabic flavors and live belly dancing
                        performances to luxury dining and vibrant rooftop nightlife,
                        Sultan Arabic Fine Dine and Papas Bar &amp; Grill are set to
                        redefine dining and entertainment in Kathmandu.
                    </p>
                @endif
            </div>

            <div class="col-lg-6 visual-col">
                <div class="burger-wrap burger-marker" id="burgerWrapStory">
                    <img src="{{ isset($storyPost) && !empty($storyPost->image) ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
                        alt="" class="burger" id="burgerStory" />
                </div>
            </div>
        </div>
    </div>
</div>

<section class="gallery-pin" id="galleryPin">
    <div class="gallery-intro" id="galleryIntro">
        <h2 class="gallery-title">Gallery</h2>
        <div class="thumb-row" id="thumbRow">
            @if (isset($galleryImages) && count($galleryImages) > 0)
                @foreach ($galleryImages->take(3) as $i => $img)
                    <div class="thumb" id="thumb{{ $i + 1 }}">
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title ?? '' }}" />
                    </div>
                @endforeach
            @else
                <div class="thumb" id="thumb1">
                    <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=1200&q=80"
                        alt="" />
                </div>
                <div class="thumb" id="thumb2">
                    <img src="https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1200&q=80"
                        alt="" />
                </div>
                <div class="thumb" id="thumb3">
                    <img src="https://images.unsplash.com/photo-1543007630-9710e4a00a20?auto=format&fit=crop&w=1600&q=80"
                        alt="" />
                </div>
            @endif
        </div>

        <div class="filmstrip" id="filmstrip">
            <div class="film-track" id="filmTrack">
                @if (isset($galleryImages) && count($galleryImages) > 0)
                    @foreach ($galleryImages as $img)
                        <div class="film-slide" data-title="{{ $img->title ?? '' }}">
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title ?? '' }}" />
                        </div>
                    @endforeach
                @else
                    <!-- Default fallback slides -->
                    <div class="film-slide" data-title="Rooftop Bar &amp; Grill">
                        <img src="https://images.unsplash.com/photo-1543007630-9710e4a00a20?auto=format&fit=crop&w=1600&q=80"
                            alt="" />
                    </div>
                    <div class="film-slide" data-title="Crafted Cocktails">
                        <img src="https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1600&q=80"
                            alt="" />
                    </div>
                    <div class="film-slide" data-title="Late Night Crowd">
                        <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=1600&q=80"
                            alt="" />
                    </div>
                    <div class="film-slide" data-title="City Skyline Views">
                        <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=1600&q=80"
                            alt="" />
                    </div>
                @endif
            </div>

            <div class="film-overlay" id="filmOverlay">
                <h3 class="film-title" id="filmTitle">
                    {{ isset($galleryImages) && count($galleryImages) > 0 ? $galleryImages->first()->title ?? '' : 'Rooftop Bar &amp; Grill' }}
                </h3>
            </div>

            <div class="film-nav" id="filmNav">
                <button class="nav-btn" id="prevBtn" aria-label="Previous">&larr;</button>
                <button class="nav-btn" id="nextBtn" aria-label="Next">&rarr;</button>
            </div>

            <div class="film-progress" id="filmProgress"></div>
        </div>
    </div>
</section>

<section class="menu-section section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="menu-title">Our Special Menu</h2>

                @if (isset($menuCategory) && isset($menuCategory->products) && count($menuCategory->products) > 0)
                    @foreach ($menuCategory->products as $item)
                        <a href="{{ route('menu.detail', $item->slug) }}" class="menu-item">
                            <div>
                                <div class="row-top">
                                    <h3>{{ $item->name }}</h3>
                                    <div class="price">Rs. {{ number_format($item->price, 0) }}</div>
                                </div>
                                <p>{{ $item->description }}</p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Default fallback menu items -->
                    <div class="menu-item">
                        <div>
                            <div class="row-top">
                                <h3>Moscow Mule</h3>
                                <div class="price">Rs. 2000</div>
                            </div>
                            <p>Lemongrass infused Russian standard platinum vodka, lime, jasmine green tea &amp; matcha
                                soda</p>
                        </div>
                    </div>
                    <div class="menu-item">
                        <div>
                            <div class="row-top">
                                <h3>Mango Dusk</h3>
                                <div class="price">Rs. 1500</div>
                            </div>
                            <p>Lemongrass infused Russian standard platinum vodka, lime, jasmine green tea &amp; matcha
                                soda</p>
                        </div>
                    </div>
                    <div class="menu-item">
                        <div>
                            <div class="row-top">
                                <h3>Pineapple Sunrise</h3>
                                <div class="price">Rs. 1200</div>
                            </div>
                            <p>Lemongrass infused Russian standard platinum vodka, lime, jasmine green tea &amp; matcha
                                soda</p>
                        </div>
                    </div>
                    <div class="menu-item">
                        <div>
                            <div class="row-top">
                                <h3>Bloody Orange</h3>
                                <div class="price">Rs. 1000</div>
                            </div>
                            <p>Lemongrass infused Russian standard platinum vodka, lime, jasmine green tea &amp; matcha
                                soda</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="menu-gallery">
                    @if (isset($menuGalleryImages) && count($menuGalleryImages) > 0)
                        @foreach ($menuGalleryImages as $i => $img)
                            <div class="g-img {{ $i === 0 ? 'wide' : 'narrow' }}">
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title ?? '' }}" />
                            </div>
                        @endforeach
                    @else
                        <div class="g-img wide">
                            <img src="{{ asset('image/g4.jpg') }}" alt="Cocktail on a table" />
                        </div>
                        <div class="g-img narrow">
                            <img src="{{ asset('image/g5.jpg') }}" alt="Guest enjoying the night" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Traveling bottle: hero -> reveal -> curve, driven purely by scroll progress -->
<div class="bottle-fly" id="bottleFly" aria-hidden="true">
    <img src="{{ isset($heroBottleBack) && !empty($heroBottleBack->path) ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
        alt="" class="bottle-fly-img" id="bottleFlyBack" />
    <img src="{{ isset($heroBottleFront) && !empty($heroBottleFront->path) ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
        alt="" class="bottle-fly-img" id="bottleFlyFront" />
</div>

<!-- Traveling burger: curve -> story -->
<div class="burger-fly" id="burgerFly" aria-hidden="true">
    <img src="{{ isset($storyPost) && !empty($storyPost->image) ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
        alt="" class="burger-fly-img" id="burgerFlyImg" />
</div>

@include('web::layouts.footer')
