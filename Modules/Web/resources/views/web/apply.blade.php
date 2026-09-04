@include('web::layouts.header')
<!-- ============ PAGE BANNER ============ -->
<section class="page-banner">
    <div class="container" data-aos="fade-up" data-aos-duration="800">
        <h1>Apply Now</h1>
        <div class="heading-underline"></div>
        <div class="breadcrumb">
            <a href="index.html">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="current">Apply Now</span>
        </div>
        <p class="banner-lead">Begin your child's journey towards excellence. Admissions are open for the academic
            year 2025/26.</p>
    </div>
    <svg class="page-banner-wave" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="currentColor" d="M0,80 C480,0 960,80 1440,10 L1440,80 L0,80 Z"></path>
    </svg>
</section>

<!-- ============ ADMISSION PROCESS ============ -->
<section class="process-section">
    <div class="container">
        <h2>Admission Process</h2>
        <div class="tri-divider"><span></span><span></span><span></span></div>

        <div class="process-track">
            <div class="process-node" data-aos="fade-up" data-aos-delay="0">
                <div class="process-icon bg-green"><i class="fa-solid fa-file-lines"></i><span
                        class="process-num">01</span></div>
                <h4>Fill the Application Form</h4>
            </div>
            <div class="process-node" data-aos="fade-up" data-aos-delay="80">
                <div class="process-icon bg-maroon"><i class="fa-solid fa-file-circle-check"></i><span
                        class="process-num">02</span></div>
                <h4>Submit Required Documents</h4>
            </div>
            <div class="process-node" data-aos="fade-up" data-aos-delay="160">
                <div class="process-icon bg-gold"><i class="fa-solid fa-people-arrows"></i><span
                        class="process-num">03</span></div>
                <h4>Interaction / Assessment (if applicable)</h4>
            </div>
            <div class="process-node" data-aos="fade-up" data-aos-delay="240">
                <div class="process-icon bg-green"><i class="fa-solid fa-envelope-circle-check"></i><span
                        class="process-num">04</span></div>
                <h4>Admission Confirmation</h4>
            </div>
            <div class="process-node" data-aos="fade-up" data-aos-delay="320">
                <div class="process-icon bg-maroon"><i class="fa-solid fa-file-invoice-dollar"></i><span
                        class="process-num">05</span></div>
                <h4>Fee Payment &amp; Documentation</h4>
            </div>
            <div class="process-node" data-aos="fade-up" data-aos-delay="400">
                <div class="process-icon bg-gold"><i class="fa-solid fa-school-flag"></i><span
                        class="process-num">06</span></div>
                <h4>Welcome to Ambassador School</h4>
            </div>
        </div>
    </div>
</section>

<!-- ============ APPLICATION FORM ============ -->
<section class="application-section">
    <div class="container">
        <h2>Application Form</h2>
        <div class="tri-divider"><span></span><span></span><span></span></div>

        <div class="application-grid">

            <form class="application-form-card" id="applicationForm" data-aos="fade-right"
                action="{{ route('apply.store') }}" method="POST">
                @csrf

                @if (session('success'))
                    <div class="form-alert form-alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="form-alert form-alert-error">{{ session('error') }}</div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label for="applyingFor">Applying For *</label>
                        <select id="applyingFor" name="applying_for" required>
                            <option value="" selected disabled>Select Grade</option>
                            <option value="Pre-KG" {{ old('applying_for') == 'Pre-KG' ? 'selected' : '' }}>Pre-KG
                            </option>
                            <option value="KG" {{ old('applying_for') == 'KG' ? 'selected' : '' }}>KG</option>
                            <option value="Grade 1 - 5" {{ old('applying_for') == 'Grade 1 - 5' ? 'selected' : '' }}>
                                Grade 1 – 5</option>
                            <option value="Grade 6 - 8" {{ old('applying_for') == 'Grade 6 - 8' ? 'selected' : '' }}>
                                Grade 6 – 8</option>
                            <option value="Grade 9 - 12" {{ old('applying_for') == 'Grade 9 - 12' ? 'selected' : '' }}>
                                Grade 9 – 12</option>
                        </select>
                        @error('applying_for')
                            <small style="color:var(--maroon)">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Student's Full Name *</label>
                    <div class="form-row-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name"
                            required>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                            placeholder="Middle Name">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name"
                            required>
                    </div>
                    @error('first_name')
                        <small style="color:var(--maroon)">{{ $message }}</small>
                    @enderror
                    @error('last_name')
                        <small style="color:var(--maroon)">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required>
                        @error('dob')
                            <small style="color:var(--maroon)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="" selected disabled>Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <small style="color:var(--maroon)">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="guardianName">Parent/Guardian Name *</label>
                    <input type="text" id="guardianName" name="guardian_name" value="{{ old('guardian_name') }}"
                        placeholder="Full Name" required>
                    @error('guardian_name')
                        <small style="color:var(--maroon)">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="applyEmail">Email Address *</label>
                        <input type="email" id="applyEmail" name="email" value="{{ old('email') }}"
                            placeholder="you@example.com" required>
                        @error('email')
                            <small style="color:var(--maroon)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="applyPhone">Phone Number *</label>
                        <input type="tel" id="applyPhone" name="phone" value="{{ old('phone') }}"
                            placeholder="+977" required>
                        @error('phone')
                            <small style="color:var(--maroon)">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" placeholder="Full residential address" required>{{ old('address') }}</textarea>
                    @error('address')
                        <small style="color:var(--maroon)">{{ $message }}</small>
                    @enderror
                </div>

                @if (!empty($settings['recaptcha_site_key']?->value))
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ $settings['recaptcha_site_key']->value }}"></div>
                    </div>
                @endif

                <button type="submit" class="btn btn-dark-green">Submit Application <i
                        class="fa-solid fa-arrow-right"></i></button>
            </form>

            <div class="application-sidebar" data-aos="fade-left">
                <div class="doc-required-card">
                    <h4>Documents Required</h4>
                    <div class="doc-item">
                        <div class="doc-icon"><i class="fa-solid fa-file-lines"></i></div>
                        <span>Birth Certificate<br>(Photocopy)</span>
                    </div>
                    <div class="doc-item">
                        <div class="doc-icon"><i class="fa-solid fa-image"></i></div>
                        <span>Passport Size<br>Photograph</span>
                    </div>
                    <div class="doc-item">
                        <div class="doc-icon"><i class="fa-solid fa-file-shield"></i></div>
                        <span>Previous School<br>Report Card</span>
                    </div>
                    <div class="doc-item">
                        <div class="doc-icon"><i class="fa-solid fa-id-card"></i></div>
                        <span>Parent/Guardian ID<br>Proof</span>
                    </div>
                    <span class="doc-note">* All documents should be clear and valid.</span>
                </div>

                {{-- <div class="help-card">
                    <h5>Need Help?</h5>
                    <p>Our admission team is here to assist you with any queries.</p>
                    <a href="contact.html" class="btn btn-primary btn-block">Contact Admissions Office <i
                            class="fa-solid fa-phone"></i></a>
                    <span class="help-contact"><i class="fa-solid fa-phone"></i> +977 1 1234567</span>
                    <span class="help-contact"><i class="fa-solid fa-envelope"></i>
                        admissions@ambassadorschool.edu.np</span>
                </div> --}}
            </div>

        </div>
    </div>
</section>

<!-- ============ FOOTER (identical to every page) ============ -->
@include('web::layouts.footer')
