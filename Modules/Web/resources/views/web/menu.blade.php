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
        @php
            // Find the "Main Course" category robustly — handles "Main Course",
            // "Main Courses", extra spaces, different casing, etc.
            $mainCourseId =
                $categories->first(function ($cat) {
                    return str_contains(strtolower(trim($cat->name)), 'main course');
                })->id ?? optional($categories->first())->id; // fallback: first category in the list
        @endphp

        <nav class="course-tabs" role="tablist">
            @foreach ($categories as $category)
                <button class="tab-btn {{ $category->id === $mainCourseId ? 'active' : '' }} reveal delay-4"
                    data-tab="{{ $category->id }}" role="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54" fill="none">
                        <path class="btn-shape"
                            d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                    </svg>
                    <span>{{ $category->name }}</span>
                </button>
            @endforeach
        </nav>

        {{-- ── Tab Content ── --}}
        @foreach ($categories as $category)
            <div id="tab-{{ $category->id }}"
                class="course-tab-wrapper {{ $category->id === $mainCourseId ? 'active' : '' }} container-fluid"
                style="margin:auto;">
                <div class="course-banner">
                    <img src="{{ $category->image ? Storage::url($category->image) : asset('images/13.webp') }}"
                        alt="{{ $category->name }}" />
                    <div class="course-overlay">
                        <div class="text-center">
                            <h2 class="title reveal delay-2">{{ $category->name }}</h2>
                            <div class="divider-gold reveal delay-3"><i></i></div>
                        </div>
                    </div>
                </div>
                <div class="course-grid p-0">
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

<style>
    .course-tab-wrapper {
        display: none;
    }

    .course-tab-wrapper.active {
        display: block;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.course-tabs .tab-btn');
        const tabContents = document.querySelectorAll('.course-tab-wrapper');

        tabButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = 'tab-' + this.dataset.tab;

                tabButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                tabContents.forEach(function(c) {
                    c.classList.remove('active');
                });

                this.classList.add('active');

                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    });
</script>
