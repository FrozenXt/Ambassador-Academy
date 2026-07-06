@extends('admin::layouts.app')
@section('page_title', 'Add Client')

@section('page_actions')
    <a href="{{ route('admin.clients.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- Main --}}
            <div class="col-md-8">

                {{-- Basic Info --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-2"></i> Basic Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. John Smith" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                                        class="form-control" placeholder="e.g. Acme Corp" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="email@company.com" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                                        placeholder="+977-9800000000" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" value="{{ old('website') }}"
                                        class="form-control @error('website') is-invalid @enderror"
                                        placeholder="https://..." />
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tax Number</label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Reg. Number</label>
                                    <input type="text" name="registration_number"
                                        value="{{ old('registration_number') }}" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i> Address
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address Line 1</label>
                                    <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                                        class="form-control" placeholder="Street address" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address Line 2</label>
                                    <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                                        class="form-control" placeholder="Apartment, suite, etc." />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" value="{{ old('city') }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" value="{{ old('state') }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" value="{{ old('country') }}"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Person --}}
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-tie mr-2"></i> Contact Person
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="contact_person_name"
                                        value="{{ old('contact_person_name') }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" name="contact_person_designation"
                                        value="{{ old('contact_person_designation') }}" class="form-control"
                                        placeholder="e.g. CEO, Manager" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Email</label>
                                    <input type="email" name="contact_person_email"
                                        value="{{ old('contact_person_email') }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Phone</label>
                                    <input type="text" name="contact_person_phone"
                                        value="{{ old('contact_person_phone') }}" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Details --}}
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-briefcase mr-2"></i> Business Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Industry Type</label>
                                    <input type="text" name="industry_type" value="{{ old('industry_type') }}"
                                        class="form-control" placeholder="e.g. Technology, Finance"
                                        list="industryList" />
                                    <datalist id="industryList">
                                        <option value="Technology">
                                        <option value="Finance">
                                        <option value="Healthcare">
                                        <option value="Education">
                                        <option value="Retail">
                                        <option value="Manufacturing">
                                        <option value="Real Estate">
                                        <option value="Hospitality">
                                        <option value="Media">
                                        <option value="Other">
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Employee Count</label>
                                    <input type="number" name="employee_count" value="{{ old('employee_count') }}"
                                        class="form-control" min="0" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select name="currency" class="form-control">
                                        <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD
                                        </option>
                                        <option value="NPR" {{ old('currency') == 'NPR' ? 'selected' : '' }}>NPR
                                        </option>
                                        <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR
                                        </option>
                                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR
                                        </option>
                                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Annual Revenue</label>
                                    <input type="number" name="annual_revenue" value="{{ old('annual_revenue') }}"
                                        class="form-control" min="0" step="0.01" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Credit Limit</label>
                                    <input type="number" name="credit_limit" value="{{ old('credit_limit') }}"
                                        class="form-control" min="0" step="0.01" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card card-outline card-secondary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sticky-note mr-2"></i> Notes
                        </h3>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" rows="4" class="form-control" placeholder="Internal notes about this client...">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-md-4">

                {{-- Image Upload --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i> Client Logo
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <div class="text-center mb-3">
                                <div id="createAvatarBox"
                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:120px;height:120px;background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                    <i class="fas fa-building fa-3x text-white"></i>
                                </div>
                                <img id="createImagePreview" src="#" class="rounded-circle d-none"
                                    style="width:120px;height:120px;object-fit:cover;" />
                            </div>

                            <div class="custom-file">
                                <input type="file" name="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="clientImageInput"
                                    onchange="previewClientImage(this)" />
                                <label class="custom-file-label" for="clientImageInput">
                                    Choose file...
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                JPG, PNG, GIF or SVG. Max 2MB. Recommended: 200×200px
                            </small>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Account Settings --}}
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Account Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Client Type <span class="text-danger">*</span></label>
                            <select name="client_type" class="form-control @error('client_type') is-invalid @enderror">
                                <option value="business"
                                    {{ old('client_type', 'business') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="individual" {{ old('client_type') == 'individual' ? 'selected' : '' }}>
                                    Individual</option>
                                <option value="government" {{ old('client_type') == 'government' ? 'selected' : '' }}>
                                    Government</option>
                                <option value="nonprofit" {{ old('client_type') == 'nonprofit' ? 'selected' : '' }}>
                                    Non-Profit</option>
                            </select>
                            @error('client_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Payment Terms <span class="text-danger">*</span></label>
                            <select name="payment_terms"
                                class="form-control @error('payment_terms') is-invalid @enderror">
                                <option value="net_30" {{ old('payment_terms', 'net_30') == 'net_30' ? 'selected' : '' }}>
                                    Net 30 Days
                                </option>
                                <option value="immediate" {{ old('payment_terms') == 'immediate' ? 'selected' : '' }}>
                                    Immediate</option>
                                <option value="net_7" {{ old('payment_terms') == 'net_7' ? 'selected' : '' }}>Net 7
                                    Days</option>
                                <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>Net 15
                                    Days</option>
                                <option value="net_60" {{ old('payment_terms') == 'net_60' ? 'selected' : '' }}>Net 60
                                    Days</option>
                            </select>
                            @error('payment_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActive"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isActive">
                                    Active Client
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Save Client
                        </button>
                    </div>
                </div>

                {{-- Help Box --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb mr-2"></i> Info
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                Client code is auto-generated
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                Email must be unique per client
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                You can verify clients after creation
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('extra_js')
    <script>
        function previewClientImage(input) {
            var preview = document.getElementById('createImagePreview');
            var box = document.getElementById('createAvatarBox');
            var label = input.nextElementSibling;

            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    box.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                label.textContent = 'Choose file...';
                preview.classList.add('d-none');
                box.style.display = 'inline-flex';
                preview.src = '#';
            }
        }
    </script>
@endsection
