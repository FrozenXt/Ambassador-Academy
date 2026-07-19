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
                <div class="row g-4 align-items-center">

                    @php $imageOnRight = $i % 2 === 0; @endphp

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
            </div>
        </section>
    @endforeach
</div>

@include('web::layouts.footer')
