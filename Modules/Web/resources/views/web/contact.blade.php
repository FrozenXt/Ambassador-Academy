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
                        <form action="{{ route('contact.process') }}" method="POST">
                            @csrf
                            <input type="text" name="name" class="form-control" placeholder="Your Name*"
                                required />
                            <input type="email" name="email" class="form-control" placeholder="Your Email Address*"
                                required />
                            <textarea name="message" class="form-control" placeholder="Message Here*" required></textarea>
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
