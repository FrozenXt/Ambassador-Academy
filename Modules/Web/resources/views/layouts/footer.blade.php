@php
    $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
    $logoUrl = $siteSettings->getByKey('site_logo')
        ? Storage::url($siteSettings->getByKey('site_logo'))
        : asset('image/logo.png');
@endphp


<footer class="site-footer section-padding pb-0">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-0 text-center footer-top">
            <div class="col info-col">
                <div class="info-item">
                    <iconify-icon icon="fluent:call-20-regular"></iconify-icon>
                    <h3>Contact Us</h3>
                    <p>{{ $siteSettings->getByKey('site_phone', '014507444, 014509444') }}</p>
                    <a href="tel:{{ $siteSettings->getByKey('site_phone', '014507444') }}" class="btn-outline-gold">Call
                        Us Here</a>
                </div>
            </div>

            <div class="col info-col">
                <div class="info-item">
                    <iconify-icon icon="weui:location-outlined"></iconify-icon>
                    <h3>Address</h3>
                    <p>{{ $siteSettings->getByKey('site_address', 'Lazimpat, Kathmandu, Nepal') }}</p>
                    <a href="{{ $siteSettings->getByKey('google_map_link', '#') }}" class="btn-outline-gold">Get
                        Direction</a>
                </div>
            </div>

            <div class="col info-col">
                <div class="info-item">
                    <iconify-icon icon="streamline-cyber:email-2"></iconify-icon>
                    <h3>Email Address</h3>
                    <p>{{ $siteSettings->getByKey('site_email', 'info@papabargrill.com') }}</p>
                    <a href="mailto:{{ $siteSettings->getByKey('site_email', 'info@papabargrill.com') }}"
                        class="btn-outline-gold">Message Us</a>
                </div>
            </div>

            <div class="col info-col">
                <div class="info-item">
                    <iconify-icon icon="fe:clock"></iconify-icon>
                    <h3>Opening Hours</h3>
                    <p>
                        {{ $siteSettings->getByKey('opening_hours_weekday', 'Mon &ndash; Sun:') }}<br>
                        {{ $siteSettings->getByKey('opening_hours_weekend', '9:30 am &ndash; 12:30 am') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row align-items-center middle-row">
            <div class="col-12 col-md-4">
                <p class="footer-copy">
                    &copy; {{ date('Y') }} <span
                        class="accent">{{ $siteSettings->getByKey('site_name', "Papa's bar and grill") }}</span>.
                    All rights reserved.
                </p>
            </div>

            <div class="col-12 col-md-4">
                <div class="brand-badge">
                    <img src="{{ $logoUrl }}" alt="">
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="social-row">
                    @php
                        $activeSocialLinks = $siteSettings
                            ->getByGroup('social')
                            ->filter(fn($link) => $link->is_active && !empty($link->value));
                    @endphp

                    @foreach ($activeSocialLinks as $link)
                        @php
                            $label = ucwords(str_replace(['_url', '_'], ['', ' '], $link->key));
                            $iconName = $link->icon ?: 'mdi:link-variant';
                        @endphp
                        <a href="{{ $link->value }}" aria-label="{{ $label }}" target="_blank" rel="noopener">
                            <iconify-icon icon="{{ $iconName }}"></iconify-icon>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <p class="footer-credit">Developed By: <a href="https://bentraytech.com/">Bent Ray Technologies</a>
        </p>
    </div>
</footer>
</div>

<!-- Scripts -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('js/iconify-icon.min.js') }}"></script>
<script src="{{ asset('js/gsap.min.js') }}"></script>
<script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('js/text-split.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<!-- ── Custom Footer Scripts (raw HTML/JS from admin) ── -->
{!! $siteSettings->getByKey('footer_scripts', '') !!}
</body>

</html>
