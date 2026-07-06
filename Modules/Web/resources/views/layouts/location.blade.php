@php
    $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
@endphp

<section class="contact-section section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <!-- Label -->
            <p class="label-script reveal delay-1">Find Us
            <h2 class="title reveal delay-2">
                Contact & Location
            </h2>
            <!-- Divider -->
            <div class="divider-gold reveal delay-3"><i></i></div>

            <!-- Description -->
            <p class="reveal delay-3">
                Visit us at the heart of Kathmandu. Reservations recommended; walk-ins welcome subject to
                availability.
            </p>
        </div>
        <div class="row align-items-center gy-4 mt-3 position-relative z-1">

            <!-- Left: Get In Touch -->
            <div class="col-md-6 reveal-left delay-4">
                <h3 class="panel-title">Get In Touch</h3>

                <!-- Address -->
                <div class="info-item">
                    <div class="info-icon">
                        <iconify-icon icon="fluent:location-12-regular"></iconify-icon>
                    </div>
                    <div>
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $siteSettings->getByKey('site_address', 'Kathmandu, Nepal') }}</div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="info-item">
                    <div class="info-icon">
                        <iconify-icon icon="fluent:call-24-regular"></iconify-icon>
                    </div>
                    <div>
                        <div class="info-label">Phone</div>
                        <a href="tel:{{ $siteSettings->getByKey('site_phone') }}" target="_blank"
                            class="info-value">{{ $siteSettings->getByKey('site_phone') }}</a>
                        <a href="tel:{{ $siteSettings->getByKey('site_telephone') }}" target="_blank"
                            class="info-value">{{ $siteSettings->getByKey('site_telephone') }}</a>
                    </div>
                </div>

                <!-- Email -->
                <div class="info-item">
                    <div class="info-icon">
                        <iconify-icon icon="fluent:mail-24-regular"></iconify-icon>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <a href="mailto:{{ $siteSettings->getByKey('site_email') }}" target="_blank"
                            class="info-value">{{ $siteSettings->getByKey('site_email') }}</a>
                    </div>
                </div>

                <!-- Hours -->
                <div class="info-item">
                    <div class="info-icon">
                        <iconify-icon icon="tabler:clock"></iconify-icon>
                    </div>
                    <div>
                        <div class="info-label">Opening Hours</div>
                        <div class="info-value">
                            {{ $siteSettings->getByKey('opening_hours_weekday', 'Mon-Fri: 11:00 AM - 10:00 PM') }}<br>
                            {{ $siteSettings->getByKey('opening_hours_weekend', 'Sat-Sun: 12:00 PM - 11:00 PM') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Photo + Map overlay -->
            <div class="col-md-6 reveal-right delay-4">
                <div class="media-stack">

                    <!-- Restaurant photo (dark ambient interior placeholder) -->
                    <div class="photo-card">
                        <div class="photo-placeholder">
                            {!! $siteSettings->getByKey('google_map_embed') !!}
                        </div>
                    </div>

                    <!-- Map card -->
                    <div class="map-card">
                        <div class="map-content">
                            <video autoplay muted loop playsinline>
                                <source src="{{ asset('images/v1.mp4') }}" type="video/mp4" />
                            </video>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->
    </div>
</section>
