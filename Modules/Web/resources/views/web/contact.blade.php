@include('web::layouts.header')

<div class="breadcrump-wrapper">
    <div class="container">
        <div class="breadcrump-header">
            <h1>Contact Us</h1>
        </div>
    </div>
</div>

<section class="keep-touch-section section-padding">
    <div class="container">
        <div class="content-wrapper">
            <div class="row align-items-center g-4 g-lg-0">
                <!-- Contact card -->
                <div class="col-12 col-lg-7 order-1 order-lg-2">
                    <div class="contact-card">
                        <h2>Keep In Touch</h2>
                        <p class="subtitle">
                            Send us mail if you have anything to suggest
                        </p>
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.process') }}" method="POST" id="contactForm">
                            @csrf
                            <input type="text" name="name" class="form-control" placeholder="Your Name*"
                                required />
                            <input type="email" name="email" class="form-control" placeholder="Your Email Address*"
                                required />
                            <textarea name="message" class="form-control" placeholder="Message Here*" required></textarea>
                            <div class="recaptcha-wrap">
                                <div class="g-recaptcha"
                                    data-sitekey="{{ $settings['recaptcha_site_key']->value ?? '' }}" data-theme="light"
                                    data-callback="onRecaptchaSuccess" data-expired-callback="onRecaptchaExpired">
                                </div>

                                <div class="recaptcha-error" id="recaptchaError" style="display:none;">
                                    Please complete the reCAPTCHA check.
                                </div>

                                @error('g-recaptcha-response')
                                    <div class="text-danger mt-2">
                                        Please verify that you are not a robot.
                                    </div>
                                @enderror

                                @error('captcha')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-send mt-2">
                                SEND
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Plate illustration -->
                <div class="col-12 col-lg-5 order-3">
                    <div class="plate-wrap">
                        <img src="{{ $contactImage ? Storage::url($contactImage->path) : asset('image/3.jpg') }}"
                            alt="{{ $contactImage->title ?? 'contact' }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('web::layouts.footer')

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    let recaptchaVerified = false;

    function onRecaptchaSuccess() {
        recaptchaVerified = true;
        document.getElementById('recaptchaError').style.display = 'none';
    }

    function onRecaptchaExpired() {
        recaptchaVerified = false;
    }

    document.getElementById('contactForm').addEventListener('submit', function(e) {
        if (!recaptchaVerified) {
            e.preventDefault();
            document.getElementById('recaptchaError').style.display = 'block';
        }
    });
</script>
