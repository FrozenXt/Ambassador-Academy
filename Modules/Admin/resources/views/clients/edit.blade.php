@extends('admin::layouts.app')
@section('page_title', 'Edit Client')

@section('page_actions')
    <a href="{{ route('admin.clients.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            {{-- Main Content --}}
            <div class="col-md-8">

                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>
                            Edit — {{ $client->display_name }}
                            <small class="text-muted ml-2">
                                <code>{{ $client->client_code }}</code>
                            </small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $client->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company_name"
                                        value="{{ old('company_name', $client->company_name) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $client->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" value="{{ old('mobile', $client->mobile) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" value="{{ old('website', $client->website) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tax Number</label>
                                    <input type="text" name="tax_number"
                                        value="{{ old('tax_number', $client->tax_number) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Reg. Number</label>
                                    <input type="text" name="registration_number"
                                        value="{{ old('registration_number', $client->registration_number) }}"
                                        class="form-control" />
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
                                    <input type="text" name="address_line1"
                                        value="{{ old('address_line1', $client->address_line1) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address Line 2</label>
                                    <input type="text" name="address_line2"
                                        value="{{ old('address_line2', $client->address_line2) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" value="{{ old('city', $client->city) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" value="{{ old('state', $client->state) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code"
                                        value="{{ old('postal_code', $client->postal_code) }}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" value="{{ old('country', $client->country) }}"
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
                                        value="{{ old('contact_person_name', $client->contact_person_name) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" name="contact_person_designation"
                                        value="{{ old('contact_person_designation', $client->contact_person_designation) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Email</label>
                                    <input type="email" name="contact_person_email"
                                        value="{{ old('contact_person_email', $client->contact_person_email) }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Phone</label>
                                    <input type="text" name="contact_person_phone"
                                        value="{{ old('contact_person_phone', $client->contact_person_phone) }}"
                                        class="form-control" />
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
                                    <input type="text" name="industry_type"
                                        value="{{ old('industry_type', $client->industry_type) }}" class="form-control"
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
                                    <input type="number" name="employee_count"
                                        value="{{ old('employee_count', $client->employee_count) }}" class="form-control"
                                        min="0" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select name="currency" class="form-control">
                                        @foreach (['USD', 'NPR', 'INR', 'EUR', 'GBP'] as $cur)
                                            <option value="{{ $cur }}"
                                                {{ old('currency', $client->currency) == $cur ? 'selected' : '' }}>
                                                {{ $cur }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Annual Revenue</label>
                                    <input type="number" name="annual_revenue"
                                        value="{{ old('annual_revenue', $client->annual_revenue) }}" class="form-control"
                                        min="0" step="0.01" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label>Credit Limit</label>
                                    <input type="number" name="credit_limit"
                                        value="{{ old('credit_limit', $client->credit_limit) }}" class="form-control"
                                        min="0" step="0.01" />
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
                        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $client->notes) }}</textarea>
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
                        <div class="text-center mb-3">
                            @if ($client->image)
                                <img src="{{ asset('storage/' . $client->image) }}" id="editImagePreview"
                                    class="rounded-circle" style="width:120px;height:120px;object-fit:cover;" />
                                <div class="mt-2">
                                    <form action="{{ route('admin.clients.remove-image', $client->id) }}" method="POST"
                                        onsubmit="return confirm('Remove this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt mr-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div id="editAvatarBox"
                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:120px;height:120px;background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                    <span class="text-white"
                                        style="font-size:2rem;font-weight:500;">{{ $client->initials }}</span>
                                </div>
                                <img id="editImagePreview" src="#" class="rounded-circle d-none"
                                    style="width:120px;height:120px;object-fit:cover;" />
                            @endif
                        </div>

                        <div class="custom-file">
                            <input type="file" name="image"
                                class="custom-file-input @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" id="clientImageInput"
                                onchange="previewClientImage(this)" />
                            <label class="custom-file-label" for="clientImageInput">
                                {{ $client->image ? 'Change image...' : 'Choose file...' }}
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">
                            JPG, PNG, GIF or SVG. Max 2MB. Recommended: 200×200px
                            @if ($client->image)
                                <br>Leave empty to keep current image.
                            @endif
                        </small>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Account Settings --}}
                <div class="card card-outline card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i> Account Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Client Type</label>
                            <select name="client_type" class="form-control">
                                @foreach (['business', 'individual', 'government', 'nonprofit'] as $type)
                                    <option value="{{ $type }}"
                                        {{ old('client_type', $client->client_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Terms</label>
                            <select name="payment_terms" class="form-control">
                                @foreach (['immediate', 'net_7', 'net_15', 'net_30', 'net_60'] as $term)
                                    <option value="{{ $term }}"
                                        {{ old('payment_terms', $client->payment_terms) == $term ? 'selected' : '' }}>
                                        {{ $term == 'immediate' ? 'Immediate' : 'Net ' . substr($term, 4) . ' Days' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActive"
                                    value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isActive">Active Client</label>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.clients.update', $client->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Your input fields go here -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save mr-1"></i> Update Client
                            </button>
                        </div>
                    </form>

                    <!-- Cancel link -->
                    <div class="card-footer">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-default btn-block">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>

                </div>

                {{-- Client Info --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i> Client Info
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted pl-3">Code</td>
                                <td><code>{{ $client->client_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Type</td>
                                <td>
                                    <span class="badge badge-{{ $client->client_type_badge }}">
                                        {{ $client->client_type_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Verified</td>
                                <td>
                                    @if ($client->is_verified)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i> Yes
                                        </span>
                                    @else
                                        <form action="{{ route('admin.clients.verify', $client->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-primary">
                                                Verify Now
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-3">Joined</td>
                                <td>{{ $client->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST"
                            onsubmit="return confirm('Delete {{ addslashes($client->display_name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm btn-block">
                                <i class="fas fa-trash mr-1"></i> Delete Client
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('extra_js')
    <script>
        function previewClientImage(input) {
            var preview = document.getElementById('editImagePreview');
            var box = document.getElementById('editAvatarBox');
            var label = input.nextElementSibling;

            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (box) box.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                label.textContent = '{{ $client->image ? 'Change image...' : 'Choose file...' }}';
                preview.classList.add('d-none');
                if (box) box.style.display = 'inline-flex';
                preview.src = '#';
            }
        }
    </script>
@endsection
