@include('web::layouts.header')

<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Contact Us</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="index.html">Home</a>
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
                        <p>123 Education Street,<br>Kathmandu, Nepal</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-maroon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <h5>Phone Number</h5>
                        <p>+977 1 1234567<br>+977 9801234567</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-gold"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h5>Email Address</h5>
                        <p>info@ambassadorschool.edu.np<br>admissions@ambassadorschool.edu.np</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon bg-green"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <h5>School Hours</h5>
                        <p>Mon - Fri: 8:00 AM - 4:00 PM<br>Saturday: 9:00 AM - 1:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="contact-follow">
                <h5>Follow Us</h5>
                <div class="socials">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="contact-col" data-aos="fade-left">
            <h2>Send Us A Message</h2>
            <div class="tri-divider"><span></span><span></span><span></span></div>

            <form class="contact-form-card" id="contactForm">
                <div class="form-group">
                    <input type="text" placeholder="Your Name *" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email Address *" required>
                </div>
                <div class="form-group">
                    <input type="tel" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" required>
                        <option value="" selected disabled>Select Subject</option>
                        <option>Admissions Enquiry</option>
                        <option>General Information</option>
                        <option>Feedback & Suggestions</option>
                        <option>Careers</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" placeholder="Write your message here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-dark-green">Send Message <i
                        class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>

    </div>
</section>

<!-- ============ MAP ============ -->
<section class="map-section" data-aos="fade-up">
    <div class="map-visual">
        <iframe title="Ambassador School Location"
            src="https://maps.google.com/maps?q=Kathmandu%20Durbar%20Square&t=&z=15&ie=UTF8&iwloc=&output=embed"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <div class="map-pin-card">
            <i class="fa-solid fa-location-dot"></i>
            <div>
                <h5>Ambassador School</h5>
                <p>123 Education Street, Kathmandu, Nepal</p>
            </div>
        </div>
    </div>
    <div class="map-cta-panel">
        <h3>We Are Here</h3>
        <p>Visit our campus and experience Ambassador School.</p>
        <a href="https://maps.google.com" target="_blank" rel="noopener" class="btn btn-dark-green"
            style="background:var(--gold); color:#3a2a08;">Directions <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</section>

<!-- ============ FOOTER (identical to every page) ============ -->
@include('web::layouts.footer')
