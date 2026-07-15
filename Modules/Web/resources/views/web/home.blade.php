@include('web::layouts.header')

<div class="hero" id="stage">
    <!-- Fullscreen looping background video with dark overlay -->
    <div class="hero-media">
        @if ($bannerItem && Str::endsWith($bannerItem->path, ['.mp4', '.webm', '.ogg']))
            <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
                <source src="{{ Storage::url($bannerItem->path) }}" type="video/mp4" />
            </video>
        @elseif ($bannerItem)
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
            {{ $bannerItem->title ?? 'Premium Grills • Signature Cocktails • Unforgettable Nights' }}
        </p>

        <h1 class="hero-heading" id="heroHeading">
            {{ $bannerItem->subtitle ?? "Kathmandu's Largest" }}<br />
            <em>{{ $bannerItem->description ?? 'Rooftop Bar & Grill' }}</em>
        </h1>

        <a href="{{ $bannerItem->youtube_link ?? '#reveal' }}" class="btn-gold" id="heroBtn">
            <span>Explore Now</span>
        </a>
    </div>

    <!-- Half circle + bottles emerging from it -->
    <div class="circle-stage" id="circleStage">
        <div class="half-circle" id="halfCircle"></div>
        <div class="half-circlebg" id="halfCirclebg"></div>
        <div class="bottle-wrap bottle-marker" id="bottleWrapHero">
            <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                alt="" class="bottle bottle-back" id="bottleBackHero" />
            <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
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
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="reveal-visual" aria-hidden="true">
                    <div class="bottle-wrap bottle-marker" id="bottleWrapReveal">
                        <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                            alt="" class="bottle bottle-back" id="bottleBackReveal" />
                        <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
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
                <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}"
                    alt="" class="bottle bottle-back" id="bottleBackCurve" />
                <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}"
                    alt="" class="bottle bottle-front" id="bottleFrontCurve" />
            </div>
        </div>
        <div class="features">
            <div class="feature-row" id="featureRow">
                @foreach ($papasFeatures as $feature)
                    <div class="feature {{ $loop->last ? 'spaced' : '' }}">
                        <div class="ring">
                            <iconify-icon icon="{{ $feature->icon }}"></iconify-icon>
                        </div>
                        <span>{{ $feature->title }}</span>
                    </div>
                @endforeach
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
                <h2 class="story-title">{{ $storyPost->title ?? 'The Story' }}</h2>

                @if ($storyPost && $storyPost->content)
                    {!! nl2br(e(strip_tags($storyPost->content))) !!}
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
                    <img src="{{ $storyPost && $storyPost->image ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
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
            @foreach ($galleryImages->take(3) as $i => $img)
                <div class="thumb" id="thumb{{ $i + 1 }}">
                    <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                </div>
            @endforeach
        </div>

        <div class="filmstrip" id="filmstrip">
            <div class="film-track" id="filmTrack">
                @foreach ($galleryImages as $img)
                    <div class="film-slide" data-title="{{ $img->title }}">
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                    </div>
                @endforeach
            </div>

            <div class="film-overlay" id="filmOverlay">
                <h3 class="film-title" id="filmTitle">{{ $galleryImages->first()->title ?? '' }}</h3>
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

                @forelse ($menuCategory->products ?? [] as $item)
                    <div class="menu-item">
                        <div>
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->description }}</p>
                        </div>
                        <div class="price">Rs. {{ number_format($item->price, 0) }}</div>
                    </div>
                @empty
                    <p class="text-muted">Menu coming soon.</p>
                @endforelse
            </div>

            <div class="col-lg-6">
                <div class="menu-gallery">
                    @foreach ($menuGalleryImages as $i => $img)
                        <div class="g-img {{ $i === 0 ? 'wide' : 'narrow' }}">
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Traveling bottle: hero -> reveal -> curve, driven purely by scroll progress -->
<div class="bottle-fly" id="bottleFly" aria-hidden="true">
    <img src="{{ $heroBottleBack ? Storage::url($heroBottleBack->path) : asset('image/b1.png') }}" alt=""
        class="bottle-fly-img" id="bottleFlyBack" />
    <img src="{{ $heroBottleFront ? Storage::url($heroBottleFront->path) : asset('image/b2.png') }}" alt=""
        class="bottle-fly-img" id="bottleFlyFront" />
</div>

<!-- Traveling burger: curve -> story -->
<div class="burger-fly" id="burgerFly" aria-hidden="true">
    <img src="{{ $storyPost && $storyPost->image ? Storage::url($storyPost->image) : asset('image/b3.png') }}"
        alt="" class="burger-fly-img" id="burgerFlyImg" />
</div>

@include('web::layouts.footer')
