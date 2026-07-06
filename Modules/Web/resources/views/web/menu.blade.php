@php
    $pageTitle = 'Menu | Sultan Arabic Restaurant';
    $pageDescription =
        "Discover a curated selection of authentic Arabic dishes, signature grills, premium beverages, and exquisite desserts at Sultan & Papas, Kathmandu's luxury dining destination.";
    $pageKeywords =
        'luxury dining Kathmandu, Arabic restaurant Kathmandu, Middle Eastern food Lalitpur, fine dining Nepal, Arabic grills, authentic Arabic cuisine, Sultan and Papas';
@endphp
@include('web::layouts.header')

<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">our menu</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Menu</li>
            </ol>
        </nav>
    </div>
</section>
<!-- ── Page Menu SECTION ── -->
<section class="page-menu section-padding">
    <div class="container position-relative z-1">
        <!-- ── Tabs ── -->
        <nav class="course-tabs" role="tablist">
            <button class="tab-btn active reveal delay-4" data-tab="main" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>
                <span>Main Courses</span></button>
            <button class="tab-btn reveal delay-4" data-tab="appetizers" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54" fill="none">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>

                <span>Appetizers</span></button>
            <button class="tab-btn reveal delay-4" data-tab="desserts" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54" fill="none">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>

                <span>Desserts</span></button>
        </nav>
        <!-- ── Main Courses ── -->
        <div id="tab-main" class="course-tab-wrapper active container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="{{ asset('images/13.webp') }}" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            sultan's special menu
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">

                @foreach ($mainCourses as $item)
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
                @endforeach

            </div>
        </div>
        <!-- ── Appetizers ── -->
        <div id="tab-appetizers" class="course-tab-wrapper container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="{{ asset('images/12.webp') }}" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            Appetizers
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">

                @foreach ($appetizers as $item)
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
                @endforeach

            </div>
        </div>
        <!-- ── Desserts ── -->
        <div id="tab-desserts" class="course-tab-wrapper container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="{{ asset('images/10.jpg') }}" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            Desserts
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">

                @foreach ($desserts as $item)
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
                @endforeach

            </div>
        </div>
    </div>
    <!-- ── Item Modal ── -->
    <div class="item-modal-overlay reveal delay-7" id="itemModal" role="dialog" aria-modal="true">
        <div class="item-modal">
            <button class="modal-close" id="modalClose" aria-label="Close">✕</button>
            <img class="modal-img" id="modalImg" src="" alt="Menu Image">
            <div class="modal-name" id="modalName"></div>
            <div class="modal-desc" id="modalDesc"></div>
            <div class="modal-price" id="modalPrice"></div>
        </div>
    </div>
</section>
@include('web::layouts.footer')
