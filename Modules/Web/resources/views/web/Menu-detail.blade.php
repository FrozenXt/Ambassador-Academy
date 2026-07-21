@include('web::layouts.header')

<div class="breadcrump-wrapper">
    <div class="container">
        <div class="breadcrump-header">
            <h1>{{ $product->name }}</h1>
        </div>
    </div>
</div>

<section class="menuStage section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Image -->
            <div class="col-md-5 col-lg-4 offset-lg-1">
                <div class="photo-arch">
                    <img src="{{ $product->image ? Storage::url($product->image) : asset('image/8.png') }}"
                        alt="{{ $product->name }}" />
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-7 col-lg-6 content-col">
                <div class="eyebrow">{{ $product->categories->pluck('name')->implode(', ') }}</div>
                <h1 class="section-title">{{ $product->name }}</h1>
                <p class="subtitle">{{ $product->subtitle }}</p>
                <p class="description">{{ $product->description }}</p>

                <div class="row attr-row">
                    <div class="col-4">
                        <span class="attr-label">Base</span>
                        <span class="attr-value">{{ $product->base }}</span>
                    </div>
                    <div class="col-4">
                        <span class="attr-label">Style</span>
                        <span class="attr-value">{{ $product->style }}</span>
                    </div>
                    <div class="col-4">
                        <span class="attr-label">Served</span>
                        <span class="attr-value">{{ $product->served }}</span>
                    </div>
                </div>

                <div class="price-box">
                    <span class="price-label">Price</span>
                    <span class="price-value">Rs. {{ number_format($product->price, 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

@include('web::layouts.footer')
