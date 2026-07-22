@php
    $pageTitle = "Menu | Papa's Bar and Grill";
    $pageDescription =
        "Explore Papa's Bar and Grill's full menu — craft beers, wine and champagne, mocktails and soft drinks.";
@endphp
@include('web::layouts.header')

<div class="breadcrump-wrapper">
    <div class="container">
        <div class="breadcrump-header">
            <h1>Menu</h1>
        </div>
    </div>
</div>

<div class="menu-wrapper">
    @foreach ($categories as $i => $category)
        <section class="section-padding">
            <div class="container">

                @if ($category->image && $category->image_2)
                    {{-- ── Dual circle-shot layout (category has two images) ── --}}
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-6">
                            <div class="menuPage-wrapper">
                                <div class="section-title">{{ $category->name }}</div>

                                @forelse ($category->products as $item)
                                    <a href="{{ route('menu.detail', $item->id) }}" class="menuPage-item">
                                        <div class="row-top">
                                            <span class="name">{{ $item->name }}</span>
                                            <span class="price">Rs. {{ number_format($item->price, 0) }}</span>
                                        </div>
                                        <p class="desc">{{ $item->description }}</p>
                                    </a>
                                @empty
                                    <p class="text-muted">No items in this category yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="circle-shot">
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="circle-shot">
                                        <img src="{{ Storage::url($category->image_2) }}" alt="{{ $category->name }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- ── Single alternating img-frame layout ── --}}
                    @php $imageOnRight = $i % 2 === 0; @endphp

                    <div class="row g-4 align-items-center">

                        @if (!$imageOnRight)
                            <div class="col-lg-6 order-lg-1">
                                <div class="img-frame img-menu2">
                                    <img loading="lazy" decoding="async"
                                        src="{{ $category->image ? Storage::url($category->image) : asset('image/2.jpg') }}"
                                        alt="{{ $category->name }}" />
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-6 {{ !$imageOnRight ? 'order-lg-2' : '' }}">
                            <div class="menuPage-wrapper">
                                <div class="section-title">{{ $category->name }}</div>

                                @forelse ($category->products as $item)
                                    <a href="{{ route('menu.detail', $item->id) }}" class="menuPage-item">
                                        <div class="row-top">
                                            <span class="name">{{ $item->name }}</span>
                                            <span class="price">Rs. {{ number_format($item->price, 0) }}</span>
                                        </div>
                                        <p class="desc">{{ $item->description }}</p>
                                    </a>
                                @empty
                                    <p class="text-muted">No items in this category yet.</p>
                                @endforelse
                            </div>
                        </div>

                        @if ($imageOnRight)
                            <div class="col-lg-6">
                                <div class="img-frame">
                                    <img src="{{ $category->image ? Storage::url($category->image) : asset('image/2.jpg') }}"
                                        alt="{{ $category->name }}" />
                                </div>
                            </div>
                        @endif

                    </div>
                @endif

            </div>
        </section>
    @endforeach
</div>

@include('web::layouts.footer')
