@php
    $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
    $logoUrl = $siteSettings->getByKey('site_logo') ? Storage::url($siteSettings->getByKey('site_logo')) : null;
    $siteName = $siteSettings->getByKey('site_name', 'Ambassador School');
    $siteTagline = $siteSettings->getByKey('site_sub', 'INSPIRE · INNOVATE · ACHIEVE');
@endphp

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-about">
            <a href="{{ route('home') }}" class="logo footer-logo">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="logo-img">
                @else
                    <div class="logo-badge"><i class="fa-solid fa-shield-halved"></i></div>
                @endif
                <div class="logo-text">
                    <span class="logo-title">{{ $siteName }}</span>
                    <span class="logo-tagline">{{ $siteTagline }}</span>
                </div>
            </a>
            <p>{{ $siteSettings->getByKey('footer_about', 'Inspiring young minds, building strong values and preparing future leaders to make a difference.') }}
            </p>

            <div class="footer-socials">
                @php
                    $activeSocialLinks = $siteSettings
                        ->getByGroup('social')
                        ->filter(fn($link) => $link->is_active && !empty($link->value));
                @endphp

                @foreach ($activeSocialLinks as $link)
                    @php
                        $label = ucwords(str_replace(['_url', '_'], ['', ' '], $link->key));
                        $faIconMap = [
                            'facebook' => 'fa-brands fa-facebook-f',
                            'twitter' => 'fa-brands fa-twitter',
                            'instagram' => 'fa-brands fa-instagram',
                            'youtube' => 'fa-brands fa-youtube',
                            'linkedin' => 'fa-brands fa-linkedin-in',
                            'tiktok' => 'fa-brands fa-tiktok',
                        ];
                        $iconClass =
                            collect($faIconMap)->first(fn($v, $k) => str_contains($link->key, $k)) ??
                            'fa-solid fa-link';
                    @endphp
                    <a href="{{ $link->value }}" aria-label="{{ $label }}" target="_blank" rel="noopener">
                        <i class="{{ $iconClass }}"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="footer-links">
            <h5>Quick Links</h5>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('services') }}">Services</a></li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h5>Contact Us</h5>
            <ul>
                <li><i class="fa-solid fa-location-dot"></i>
                    {{ $siteSettings->getByKey('site_address', '123 Education Street, Kathmandu, Nepal') }}</li>
                <li><i class="fa-solid fa-phone"></i> {{ $siteSettings->getByKey('site_phone', '+977 1 1234567') }}
                </li>
                <li><i class="fa-solid fa-envelope"></i>
                    {{ $siteSettings->getByKey('site_email', 'info@ambassadorschool.edu.np') }}</li>
                <li>
                    <i class="fa-solid fa-clock"></i>
                    {{ $siteSettings->getByKey('opening_hours_weekday', 'Mon - Fri: 8:00 AM - 4:00 PM') }}<br>
                    <span
                        class="indent">{{ $siteSettings->getByKey('opening_hours_weekend', 'Saturday: 9:00 AM - 1:00 PM') }}</span>
                </li>
            </ul>
        </div>

        <div class="footer-map">
            <h5>Location</h5>

            <div class="map-embed">
                {!! $siteSettings->getByKey('google_map_embed') !!}
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</p>
            <p>Developed by Sujal Lamichhane</p>
        </div>
    </div>
</footer>

<a href="#" class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></a>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Custom Footer Scripts (raw HTML/JS from admin) -->
{!! $siteSettings->getByKey('footer_scripts', '') !!}
</body>

</html>
