@extends('admin::layouts.app')
@section('page_title', 'Edit General Settings')

@section('page_actions')
    <button type="submit" form="settingsForm" class="btn btn-primary btn-sm">
        <i class="fas fa-save mr-1"></i> Save Changes
    </button>
    {{-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#resetModal">
        <i class="fas fa-undo mr-1"></i> Reset
    </button> --}}
@endsection

@section('admin_content')
    @php
        $s = $settings->keyBy('key'); // Get settings keyed by key
    @endphp

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i> Edit General Settings
            </h3>
        </div>

        <div class="card-body">
            <form id="settingsForm" action="{{ route('admin.settings.general.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Site Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Site Name <span class="text-danger">*</span></label>
                            <input type="text" name="site_name"
                                value="{{ old('site_name', $s['site_name']->value ?? '') }}"
                                class="form-control @error('site_name') is-invalid @enderror" placeholder="Enter site name">
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Site Sub-Name <span class="text-danger">*</span></label>
                            <input type="text" name="site_sub" value="{{ old('site_sub', $s['site_sub']->value ?? '') }}"
                                class="form-control @error('site_sub') is-invalid @enderror"
                                placeholder="Enter site sub-name">
                            @error('site_sub')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Site Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Site Email</label>
                            <input type="email" name="site_email"
                                value="{{ old('site_email', $s['site_email']->value ?? '') }}"
                                class="form-control @error('site_email') is-invalid @enderror"
                                placeholder="info@example.com">
                            @error('site_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="site_phone"
                                value="{{ old('site_phone', $s['site_phone']->value ?? '') }}" class="form-control"
                                placeholder="+977-9800000000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telephone Number</label>
                            <input type="text" name="site_telephone"
                                value="{{ old('site_telephone', $s['site_telephone']->value ?? '') }}" class="form-control"
                                placeholder="+977-980009098">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="site_address"
                                value="{{ old('site_address', $s['site_address']->value ?? '') }}" class="form-control"
                                placeholder="Kathmandu, Nepal">
                        </div>
                    </div>

                    <!-- Opening Hours (Weekday) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Opening Hours (Sun – Thu)</label>
                            <input type="text" name="opening_hours_weekday"
                                value="{{ old('opening_hours_weekday', $s['opening_hours_weekday']->value ?? '') }}"
                                class="form-control" placeholder="11:00 AM – 11:00 PM">
                        </div>
                    </div>

                    <!-- Opening Hours (Weekend) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Opening Hours (Fri – Sat)</label>
                            <input type="text" name="opening_hours_weekend"
                                value="{{ old('opening_hours_weekend', $s['opening_hours_weekend']->value ?? '') }}"
                                class="form-control" placeholder="11:00 AM – 1:00 AM">
                        </div>
                    </div>

                    <!-- Site Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Site Description</label>
                            <textarea name="site_description" rows="3" class="form-control"
                                placeholder="Short description about your site...">{{ old('site_description', $s['site_description']->value ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon -->
                <div class="row">
                    <!-- Site Logo -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Site Logo</label>

                            @php
                                $logo = $s['site_logo']->value ?? null;
                            @endphp

                            <!-- Existing Logo Preview -->
                            @if (!empty($logo))
                                <div class="mb-2">
                                    <img id="logoPreviewOld" src="{{ Storage::url($logo) }}"
                                        style="max-height:60px; border:1px solid #ddd; padding:5px;">
                                </div>
                            @endif

                            <!-- Upload Input -->
                            <div class="custom-file">
                                <input type="file" name="site_logo" class="custom-file-input" id="logoInput"
                                    accept="image/*" onchange="previewImage(this,'logoPreview')">

                                <label class="custom-file-label" for="logoInput">
                                    Choose new logo...
                                </label>
                            </div>

                            <!-- Keep old value -->
                            <input type="hidden" name="old_site_logo" value="{{ $logo }}">

                            <!-- New Preview -->
                            <div class="mt-2">
                                <img id="logoPreview"
                                    style="max-height:60px; display:none; border:1px solid #ddd; padding:5px;">
                            </div>

                            <small class="text-muted">
                                JPG, PNG, WebP, SVG or GIF. Recommended: 200x60px
                            </small>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Favicon</label>
                            @php $favicon = old('old_site_favicon', $s['site_favicon']->value ?? ''); @endphp
                            @if ($favicon)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $favicon) }}" id="faviconPreviewOld"
                                        style="width:32px;height:32px;border:1px solid #ddd;padding:4px;">
                                </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" name="site_favicon" class="custom-file-input" id="faviconInput"
                                    accept="image/png,image/svg+xml,image/jpg,image/jpeg,image/webp,image/gif"
                                    onchange="previewImage(this,'faviconPreview')">
                                <label class="custom-file-label" for="faviconInput">Choose new favicon...</label>
                            </div>
                            <input type="hidden" name="old_site_favicon" value="{{ $favicon }}">
                            <div class="mt-2">
                                <img id="faviconPreview" style="width:32px;height:32px; display:none;">
                            </div>
                            <small class="text-muted">PNG or SVG. 32x32px recommended.</small>
                        </div>
                    </div>
                </div>
                <hr>
                <h5 class="mb-3"><i class="fas fa-shield-alt mr-2"></i> Google reCAPTCHA</h5>

                <div class="row">
                    <!-- Site Key -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>reCAPTCHA Site Key</label>
                            <input type="text" name="recaptcha_site_key"
                                value="{{ old('recaptcha_site_key', $s['recaptcha_site_key']->value ?? '') }}"
                                class="form-control" placeholder="Enter Site Key">
                        </div>
                    </div>

                    <!-- Secret Key -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>reCAPTCHA Secret Key</label>
                            <input type="text" name="recaptcha_secret_key"
                                value="{{ old('recaptcha_secret_key', $s['recaptcha_secret_key']->value ?? '') }}"
                                class="form-control" placeholder="Enter Secret Key">
                        </div>
                    </div>
                </div>
                @php
                    $mapEmbed = old('google_map_embed', $s['google_map_embed']->value ?? '');
                @endphp

                <div class="form-group">
                    <label>Google Map Embed</label>

                    <textarea name="google_map_embed" id="google_map_embed" rows="5" class="form-control"
                        placeholder="Paste Google Maps iframe embed code here">{{ $mapEmbed }}</textarea>

                    <small class="text-muted">
                        you have to use a full iframe code , then omly it will work else it wont.
                    </small>
                </div>

                {{-- Preview --}}
                <div class="form-group">
                    <label>Map Preview</label>

                    <div id="mapPreview" class="border rounded overflow-hidden bg-light" style="height:350px;">

                        @if (!empty($mapEmbed))
                            {!! $mapEmbed !!}
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                Map preview will appear here
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Settings
                    </button>
                    <button type="button" class="btn btn-default ml-2" onclick="window.location.reload()">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Reset Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Reset Settings</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reset all general settings to default?</p>
                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.settings.reset', 'general') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);

                // Update label
                const label = input.nextElementSibling;
                if (label) label.innerText = input.files[0].name;
            }
        }

        $(document).ready(function() {
            bsCustomFileInput.init();

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif
        });
    </script>
@endsection
