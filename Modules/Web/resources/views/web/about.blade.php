@include('web::layouts.header')

<div class="breadcrump-wrapper">
    <div class="container">
        <div class="breadcrump-header">
            <h1>About Us</h1>
        </div>
    </div>
</div>

<!-- ================= aboutPage ================= -->
<section class="aboutPage section-padding">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <h2 class="section-title">
                    {{ $aboutPost->title ?? 'Rooftop. Cocktails. Grill. Entertainment.' }}
                </h2>

                @if ($aboutPost && $aboutPost->content)
                    {!! nl2br(e(strip_tags($aboutPost->content))) !!}
                @else
                    <p>
                        Perched above the city's rooftops, Papa's is where the evening light lingers a little longer.
                        Come for hand-crafted cocktails and open-fire grilling, stay for a skyline that keeps the
                        conversation going. Every table, every pour, and every plate is set for the moments worth
                        savouring.
                    </p>
                    <p>
                        Whether you're planning an evening with friends, celebrating a special occasion, or unwinding
                        after a long Kathmandu afternoon, Papa's offers a distinctive rooftop experience — signature
                        grills and hand-poured drinks in equal measure.
                    </p>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="aboutPage-media row g-4">
                    <div class="col-md-6">
                        <img class="img-a"
                            src="{{ $aboutPost && $aboutPost->image ? $aboutPost->image_url : asset('image/2.jpg') }}"
                            alt="{{ $aboutPost->title ?? "Papa's Bar & Grill" }}" />
                    </div>
                    <div class="col-md-6">
                        <img class="img-b"
                            src="{{ $aboutPost && $aboutPost->image_2 ? $aboutPost->image_2_url : asset('image/3.jpg') }}"
                            alt="{{ $aboutPost->title ?? "Papa's Bar & Grill" }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================= aboutFeature STRIP ================= -->
<section class="aboutFeature-strip section-padding">
    <div class="container">
        <div class="aboutFeature-card">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="row g-4">
                        @forelse ($amenityImages->take(2) as $img)
                            <div class="col-md-6">
                                <div class="circle-shot">
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $img->title }}" />
                                </div>
                            </div>
                        @empty
                            <div class="col-md-6">
                                <div class="circle-shot">
                                    <img src="{{ asset('image/4.jpg') }}" alt="Crimson Bloom mocktail" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="circle-shot">
                                    <img src="{{ asset('image/5.png') }}" alt="Golden Hour Fizz mocktail" />
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3 g-md-4">
                        @foreach ($amenities as $amenity)
                            <div class="col-lg-4 col-6 amenity">
                                <div class="amenity-icon">
                                    <iconify-icon icon="{{ $amenity->icon }}"></iconify-icon>
                                </div>
                                <div class="amenity-label">{{ $amenity->title }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= EVENT SLIDER (Swiper) ================= -->
@if ($upcomingEvents->isNotEmpty())
    <section class="event-section">
        <div class="container">
            <div class="swiper event-swiper">
                <div class="swiper-wrapper">
                    @foreach ($upcomingEvents as $event)
                        <div class="swiper-slide">
                            <img class="event-slide"
                                src="{{ $event->image ? Storage::url($event->image) : asset('image/7.jpg') }}"
                                alt="{{ $event->title }}">
                            <div class="event-overlay"></div>
                            <div class="event-content">
                                <div class="event-eyebrow">
                                    {{ $event->type === 'announcement' ? 'Announcement' : 'Upcoming Event' }}
                                </div>
                                <h2 class="event-title">{{ $event->title }}</h2>
                                <div class="event-meta">
                                    {{ $event->start_date->format('F j, Y') }}
                                    &nbsp;|&nbsp;
                                    {{ $event->start_date->format('g:i A') }}
                                    @if ($event->end_date)
                                        – {{ $event->end_date->format('g:i A') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="event-pagination"></div>

                <button class="event-arrow prev" type="button" aria-label="Previous event">
                    <iconify-icon icon="mingcute:left-line"></iconify-icon>
                </button>
                <button class="event-arrow next" type="button" aria-label="Next event">
                    <iconify-icon icon="mingcute:right-line"></iconify-icon>
                </button>
            </div>
        </div>
    </section>
@endif

@include('web::layouts.footer')
