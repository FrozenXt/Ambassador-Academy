@include('web::layouts.header')
@if ($settings['recaptcha_site_key'] ?? false)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Contact Us</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Contact Us</span>
        </div>
        <p class="banner-lead">We would love to hear from you. Get in touch with us for any queries or information.
        </p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ GET IN TOUCH + FORM ============ -->
<section class="contact-section">
    <div class="container contact-grid">

        <div class="contact-col" data-aos="fade-right">
            <h2>Get In Touch</h2>
            <div class="tri-divider"><span></span><span></span><span></span></div>

            <div class="contact-info-list">
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-green"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h5>Our Address</h5>
                        <p>{{ $settings['site_address']->value ?? '123 Education Street, Kathmandu, Nepal' }}</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-maroon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <h5>Phone Number</h5>
                        <p>
                            {{ $settings['site_phone']->value ?? '+977 1 1234567' }}
                            @if (!empty($settings['site_telephone']->value ?? null))
                                <br>{{ $settings['site_telephone']->value }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-gold"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h5>Email Address</h5>
                        <p>{{ $settings['site_email']->value ?? 'info@ambassadorschool.edu.np' }}</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-green"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <h5>School Hours</h5>
                        <p>
                            {{ $settings['opening_hours_weekday']->value ?? 'Mon - Fri: 8:00 AM - 4:00 PM' }}
                            @if (!empty($settings['opening_hours_weekend']->value ?? null))
                                <br>{{ $settings['opening_hours_weekend']->value }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if ($socialLinks->isNotEmpty())
                <div class="contact-follow">
                    <h5>Follow Us</h5>
                    <div class="socials">
                        @foreach ($socialLinks as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                                aria-label="{{ $social['label'] }}">
                                <i class="{{ $social['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="contact-col" data-aos="fade-left">
            <h2>Send Us A Message</h2>
            <div class="tri-divider"><span></span><span></span><span></span></div>

            @if (session('success'))
                <div class="form-alert form-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="form-alert form-alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="form-alert form-alert-error">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="contact-form-card" id="contactForm" action="{{ route('contact.process') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            placeholder="First Name *" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name *"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address *"
                        required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="" {{ old('subject') ? '' : 'selected' }} disabled>Select Subject</option>
                        <option value="Admissions Enquiry"
                            {{ old('subject') == 'Admissions Enquiry' ? 'selected' : '' }}>Admissions Enquiry</option>
                        <option value="General Information"
                            {{ old('subject') == 'General Information' ? 'selected' : '' }}>General Information
                        </option>
                        <option value="Feedback & Suggestions"
                            {{ old('subject') == 'Feedback & Suggestions' ? 'selected' : '' }}>Feedback & Suggestions
                        </option>
                        <option value="Careers" {{ old('subject') == 'Careers' ? 'selected' : '' }}>Careers</option>
                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                </div>

                @if ($settings['recaptcha_site_key'] ?? false)
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ $settings['recaptcha_site_key']->value }}"></div>
                    </div>
                @endif

                <button type="submit" class="btn btn-dark-green">Send Message <i
                        class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>

    </div>
</section>

<!-- ============ MAP ============ -->
<section class="map-section" data-aos="fade-up">
    <div class="map-visual">
        @if (!empty($settings['google_map_embed']->value ?? null))
            {!! $settings['google_map_embed']->value !!}
        @else
            <iframe title="{{ $settings['site_name']->value ?? 'Ambassador Academy' }} Location"
                src="https://maps.google.com/maps?q=Kathmandu%20Durbar%20Square&t=&z=15&ie=UTF8&iwloc=&output=embed"
                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
        <div class="map-pin-card">
            <i class="fa-solid fa-location-dot"></i>
            <div>
                <h5>{{ $settings['site_name']->value ?? 'Ambassador Academy' }}</h5>
                <p>{{ $settings['site_address']->value ?? '123 Education Street, Kathmandu, Nepal' }}</p>
            </div>
        </div>
    </div>
    <div class="map-cta-panel">
        <h3>We Are Here</h3>
        <p>Visit our campus and experience {{ $settings['site_name']->value ?? 'Ambassador Academy' }}.</p>
        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($settings['site_address']->value ?? 'Kathmandu, Nepal') }}"
            target="_blank" rel="noopener" class="btn btn-dark-green"
            style="background:var(--gold); color:#3a2a08;">
            Directions <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- ============ FOOTER (identical to every page) ============ -->
@include('web::layouts.footer')
