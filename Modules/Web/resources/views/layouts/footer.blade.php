@php
    $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
@endphp

<!-- ── Footer SECTION ── -->
<footer>
    <div class="container">
        <!-- Main content -->
        <div class="footer-main text-center">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="footer-logo">
                <div class="logo-circle">
                    <img src="{{ $siteSettings->getByKey('site_logo') ? Storage::url($siteSettings->getByKey('site_logo')) : asset('images/logo.png') }}"
                        alt="{{ $siteSettings->getByKey('site_name', "Sultan's Arabic Grill") }}">
                </div>
                <div class="text-center text-sm-start">
                    <div class="logo-title">{{ $siteSettings->getByKey('site_name', "Sultan's Arabic Grill") }}</div>
                    <div class="logo-sub">{{ $siteSettings->getByKey('site_sub', 'Halal Arabic Dining Destination') }}
                    </div>
                </div>
            </a>

            <!-- Tagline -->
            <p class="footer-tagline">
                {{ $siteSettings->getByKey('site_description', "Kathmandu's premier luxury Arabic dining and premium social destination, inspired by Dubai's world-class dining culture.") }}
            </p>

            <!-- Social icons -->
            <div class="social-icons">

                <a class="social-btn" target="_blank"
                    href="{{ $siteSettings->getByKey('social_facebook', 'https://www.facebook.com/profile.php?id=61590732414617') }}"
                    aria-label="Facebook">
                    <iconify-icon icon="mynaui:facebook-solid"></iconify-icon>
                </a>

                <a class="social-btn" target="_blank"
                    href="{{ $siteSettings->getByKey('social_instagram', 'https://www.instagram.com/sultansarabicgrill/') }}"
                    aria-label="Instagram">
                    <iconify-icon icon="lets-icons:insta-fill"></iconify-icon>
                </a>

                <a class="social-btn" target="_blank"
                    href="{{ $siteSettings->getByKey('social_tiktok', 'https://www.tiktok.com/@sultansarabicgrill') }}"
                    aria-label="TikTok">
                    <iconify-icon icon="prime:tiktok"></iconify-icon>
                </a>

            </div>
        </div>

        <!-- Bottom bar -->
        <div
            class="footer-bottom d-flex flex-wrap justify-content-center justify-content-md-between align-items-center">
            <span class="footer-copy">
                {!! $siteSettings->getByKey(
                    'footer_text',
                    '© ' .
                        date('Y') .
                        ' <strong>' .
                        $siteSettings->getByKey('site_name', "Sultan's Arabic Grill") .
                        '</strong>. All rights reserved.',
                ) !!}
            </span>
            <span class="footer-dev">Developed By: <a href="https://bentraytech.com/" target="_blank">Bent Ray
                    Technologies</a></span>
        </div>
    </div>

    <!-- ── Course/Menu Tab Toggle (used on Home + Menu pages) ── -->
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
                    if (targetContent) targetContent.classList.add('active');
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</footer>
</body>

</html>
