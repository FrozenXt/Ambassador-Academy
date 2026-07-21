@extends('admin::layouts.app')
@section('page_title', 'Add Email Configuration')

@section('extra_css')
    <style>
        .section-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eef0f2;
        }

        .form-section {
            margin-bottom: 26px;
        }

        .form-section:last-of-type {
            margin-bottom: 0;
        }

        .form-group label {
            font-size: .82rem;
            margin-bottom: 5px;
        }

        .form-group .form-text,
        .form-group small.text-muted {
            font-size: .72rem;
        }

        .form-control {
            font-size: .88rem;
        }

        /* mobile: stack action buttons full-width, keep order Save then Cancel */
        @media (max-width: 576px) {
            .form-actions {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .form-actions .btn {
                width: 100%;
                margin-left: 0 !important;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('page_actions')
    <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-envelope mr-2"></i> New Email Configuration
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.email-settings.store') }}" method="POST">
                        @csrf

                        {{-- ── SERVER SETTINGS ── --}}
                        <div class="form-section">
                            <div class="section-label">
                                <i class="fas fa-server"></i> Server Settings
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Mailer <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mailer"
                                    class="form-control @error('mailer') is-invalid @enderror"
                                    value="{{ old('mailer', 'smtp') }}" placeholder="e.g. smtp">
                                @error('mailer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-8">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            SMTP Host <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="host" value="{{ old('host') }}"
                                            class="form-control @error('host') is-invalid @enderror"
                                            placeholder="e.g. smtp.gmail.com" />
                                        @error('host')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Port <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="port" value="{{ old('port', 587) }}"
                                            class="form-control @error('port') is-invalid @enderror" min="1"
                                            max="65535" />
                                        @error('port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="username" value="{{ old('username') }}"
                                            class="form-control @error('username') is-invalid @enderror"
                                            placeholder="your@email.com" autocomplete="off" />
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="passwordInput"
                                                value="{{ old('password') }}"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="App password" autocomplete="new-password" />
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword()" aria-label="Show password">
                                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold">
                                    Encryption <span class="text-danger">*</span>
                                </label>
                                <select name="encryption" class="form-control @error('encryption') is-invalid @enderror">
                                    <option value="tls" {{ old('encryption', 'tls') == 'tls' ? 'selected' : '' }}>
                                        TLS (Recommended)</option>
                                    <option value="ssl" {{ old('encryption') == 'ssl' ? 'selected' : '' }}>SSL
                                    </option>
                                    <option value="none" {{ old('encryption') == 'none' ? 'selected' : '' }}>None
                                    </option>
                                </select>
                                @error('encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── FROM DETAILS ── --}}
                        <div class="form-section">
                            <div class="section-label">
                                <i class="fas fa-user"></i> From Details
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            From Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="from_address" value="{{ old('from_address') }}"
                                            class="form-control @error('from_address') is-invalid @enderror"
                                            placeholder="noreply@yoursite.com" />
                                        @error('from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">
                                            From Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="from_name" value="{{ old('from_name') }}"
                                            class="form-control @error('from_name') is-invalid @enderror"
                                            placeholder="Your Site Name" />
                                        @error('from_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── NOTIFICATION RECIPIENTS ── --}}
                        <div class="form-section">
                            <div class="section-label">
                                <i class="fas fa-paper-plane"></i> Notification Recipients
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Admin Notification Emails</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-bell"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="admin_mail"
                                        value="{{ old('admin_mail', $setting->admin_mail ?? '') }}"
                                        class="form-control @error('admin_mail') is-invalid @enderror"
                                        placeholder="admin@gmail.com, manager@gmail.com" />
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Separate multiple emails with a comma.
                                </small>
                                @error('admin_mail')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">CC Emails</label>
                                        <input type="text" name="cc_mail"
                                            value="{{ old('cc_mail', $setting->cc_mail ?? '') }}"
                                            class="form-control @error('cc_mail') is-invalid @enderror"
                                            placeholder="cc1@gmail.com" />
                                        <small class="text-muted d-block mt-1">
                                            CC'ed on every notification.
                                        </small>
                                        @error('cc_mail')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">BCC Emails</label>
                                        <input type="text" name="bcc_mail"
                                            value="{{ old('bcc_mail', $setting->bcc_mail ?? '') }}"
                                            class="form-control @error('bcc_mail') is-invalid @enderror"
                                            placeholder="bcc1@gmail.com" />
                                        <small class="text-muted d-block mt-1">
                                            Hidden recipients (BCC).
                                        </small>
                                        @error('bcc_mail')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── STATUS ── --}}
                        <div class="form-section mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActive"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isActive">
                                    Set as Active Configuration
                                </label>
                            </div>
                            <small class="text-muted">
                                This will deactivate any other active configuration.
                            </small>
                        </div>

                        <hr>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Configuration
                            </button>
                            <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function applyPreset(host, port, encryption) {
            document.querySelector('[name=host]').value = host;
            document.querySelector('[name=port]').value = port;
            document.querySelector('[name=encryption]').value = encryption;

            ['[name=host]', '[name=port]', '[name=encryption]'].forEach(function(sel) {
                var el = document.querySelector(sel);
                el.style.background = '#d1fae5';
                setTimeout(function() {
                    el.style.background = '';
                }, 1000);
            });
        }

        function togglePassword() {
            var input = document.getElementById('passwordInput');
            var icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
@endsection
