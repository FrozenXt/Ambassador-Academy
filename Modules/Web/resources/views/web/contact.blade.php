@php
    $pageTitle = 'Contact Us | Sultan Arabic Restaurant';
    $pageDescription =
        "Get in touch with Sultan & Papas, Kathmandu's luxury Arabic dining destination. Find our location, contact details, reservation information, and opening hours.";
    $pageKeywords =
        'restaurant location Kathmandu, Arabic restaurant Kathmandu, contact restaurant Nepal, dining reservations Lalitpur, Sultan and Papas contact details';
@endphp
@include('web::layouts.header')

<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">Contact Us</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ── Page Contact SECTION ── -->
<section class="page-contact section-padding">
    <div class="container position-relative z-1">
        <div class="contact-card reveal delay-5">

            <!-- Food image -->
            <div class="food-panel reveal-left delay-6">
                <img src="{{ asset('images/16.png') }}" alt="Sultan's signature dish" />
            </div>

            <!-- Form -->
            <div class="form-panel reveal-right delay-6">
                <h2 class="form-title">Connect with Sultan's</h2>
                <p class="form-sub">
                    Reach out to experience authentic Arabic hospitality and fine dining in Kathmandu.
                </p>

                <form id="contactForm" method="POST" novalidate autocomplete="off">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="nameField" placeholder="Name" maxlength="80"
                            required autocomplete="name" name="name" aria-describedby="nameError" />
                        <div class="invalid-feedback" id="nameError">
                            Please enter your name.
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <input type="email" class="form-control" id="emailField" placeholder="E-mail" maxlength="120"
                            required autocomplete="email" name="email" aria-describedby="emailError" />
                        <div class="invalid-feedback" id="emailError">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <textarea class="form-control" id="msgField" placeholder="Message" rows="4" maxlength="1000" name="message"
                            required aria-describedby="msgError"></textarea>

                        <div class="char-counter" id="charCounter">0 / 1000</div>

                        <div class="invalid-feedback" id="msgError">
                            Please write a message (at least 10 characters).
                        </div>
                    </div>

                    <div class="recaptcha-wrap">
                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
                            data-theme="light" data-callback="onRecaptchaSuccess"
                            data-expired-callback="onRecaptchaExpired">
                        </div>

                        <div class="recaptcha-error" id="recaptchaError">
                            Please complete the reCAPTCHA check.
                        </div>
                    </div>

                    <!-- Status -->
                    <div id="formStatus"></div>

                    <button type="submit" class="btn-send" id="submitBtn">
                        Send Message
                        <span class="spinner" aria-hidden="true"></span>
                    </button>

                </form>
            </div>

        </div>
    </div>
</section>

<!-- ── Contact SECTION ── -->
@include('web::layouts.location')
@include('web::layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('contactForm');
        const nameField = document.getElementById('nameField');
        const emailField = document.getElementById('emailField');
        const msgField = document.getElementById('msgField');
        const submitBtn = document.getElementById('submitBtn');
        const status = document.getElementById('formStatus');
        const charCounter = document.getElementById('charCounter');
        const recaptchaError = document.getElementById('recaptchaError');

        if (!form) return;

        // Character Counter
        msgField.addEventListener('input', function() {
            charCounter.textContent = `${this.value.length} / 1000`;
        });

        function validateName() {
            const value = nameField.value.trim();

            if (value.length < 2) {
                nameField.classList.add('is-invalid');
                return false;
            }

            nameField.classList.remove('is-invalid');
            return true;
        }

        function validateEmail() {
            const value = emailField.value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(value)) {
                emailField.classList.add('is-invalid');
                return false;
            }

            emailField.classList.remove('is-invalid');
            return true;
        }

        function validateMessage() {
            const value = msgField.value.trim();

            if (value.length < 10) {
                msgField.classList.add('is-invalid');
                return false;
            }

            msgField.classList.remove('is-invalid');
            return true;
        }

        function validateRecaptcha() {
            const response = grecaptcha.getResponse();

            if (!response) {
                recaptchaError.style.display = 'block';
                return false;
            }

            recaptchaError.style.display = 'none';
            return true;
        }

        nameField.addEventListener('blur', validateName);
        emailField.addEventListener('blur', validateEmail);
        msgField.addEventListener('blur', validateMessage);

        form.addEventListener('submit', async function(e) {

            e.preventDefault();

            const isValid =
                validateName() &&
                validateEmail() &&
                validateMessage() &&
                validateRecaptcha();

            if (!isValid) return;

            submitBtn.disabled = true;

            status.style.display = 'block';
            status.className = 'success';
            status.textContent = 'Sending message...';

            try {

                const formData = new FormData(form);

                formData.append(
                    'g-recaptcha-response',
                    grecaptcha.getResponse()
                );

                const response = await fetch('{{ route('contact.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const text = await response.text();

                console.log('Response:', text);

                const result = JSON.parse(text);

                if (result.success) {

                    status.className = 'success';
                    status.textContent = '✓ Message sent successfully.';

                    form.reset();
                    charCounter.textContent = '0 / 1000';
                    grecaptcha.reset();

                } else {

                    status.className = 'error';
                    status.textContent =
                        result.message || 'Failed to send message.';
                }

            } catch (error) {

                console.error(error);

                status.className = 'error';
                status.textContent =
                    'Something went wrong. Please try again.';
            }

            submitBtn.disabled = false;
        });

    });
</script>
