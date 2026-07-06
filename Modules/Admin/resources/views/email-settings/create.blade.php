@extends('admin::layouts.app')
@section('page_title', 'Add Email Configuration')

@section('page_actions')
    <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i> New Email Configuration
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email-settings.store') }}" method="POST">
                        @csrf

                        {{-- Mailer Type --}}
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Mailer <span class="text-danger">*</span>
                            </label>
                            <select name="mailer" class="form-control @error('mailer') is-invalid @enderror"
                                onchange="toggleMailerHelp(this.value)">
                                <option value="smtp" {{ old('mailer', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP
                                </option>
                                <option value="sendmail" {{ old('mailer') == 'sendmail' ? 'selected' : '' }}>Sendmail
                                </option>
                                <option value="mailgun" {{ old('mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ old('mailer') == 'ses' ? 'selected' : '' }}>Amazon SES
                                </option>
                                <option value="postmark" {{ old('mailer') == 'postmark' ? 'selected' : '' }}>Postmark
                                </option>
                            </select>
                            @error('mailer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SMTP Settings --}}
                        <div class="row">
                            <div class="col-md-8">
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
                            <div class="col-md-4">
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordInput"
                                            value="{{ old('password') }}"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="App password or SMTP password" autocomplete="new-password" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePassword()">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Encryption <span class="text-danger">*</span>
                                    </label>
                                    <select name="encryption"
                                        class="form-control @error('encryption') is-invalid @enderror">
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
                        </div>

                        <hr>

                        {{-- From Details --}}
                        <h6 class="font-weight-bold text-muted mb-3">
                            <i class="fas fa-user mr-2"></i> From Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
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
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Admin Notification Emails
                            </label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-bell"></i>
                                    </span>
                                </div>

                                <input type="text" name="admin_mail"
                                    value="{{ old('admin_mail', $setting->admin_mail ?? '') }}"
                                    class="form-control @error('admin_mail') is-invalid @enderror"
                                    placeholder="admin@gmail.com, manager@gmail.com, support@gmail.com" />
                            </div>

                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Add multiple emails separated by comma (,). Example: admin@gmail.com, support@gmail.com
                            </small>

                            @error('admin_mail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">
                                CC Emails
                            </label>

                            <input type="text" name="cc_mail" value="{{ old('cc_mail', $setting->cc_mail ?? '') }}"
                                class="form-control @error('cc_mail') is-invalid @enderror"
                                placeholder="cc1@gmail.com, cc2@gmail.com" />

                            <small class="text-muted">
                                These emails will be CC'ed on every contact notification (comma separated)
                            </small>

                            @error('cc_mail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">
                                BCC Emails
                            </label>

                            <input type="text" name="bcc_mail"
                                value="{{ old('bcc_mail', $setting->bcc_mail ?? '') }}"
                                class="form-control @error('bcc_mail') is-invalid @enderror"
                                placeholder="bcc1@gmail.com, bcc2@gmail.com" />

                            <small class="text-muted">
                                These emails will be hidden recipients (BCC)
                            </small>

                            @error('bcc_mail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group mb-0">
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Configuration
                        </button>
                        <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Help Sidebar --}}
        <div class="col-md-4">

            {{-- Common Presets --}}
            <div class="card card-outline card-info mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-magic mr-2"></i> Quick Presets
                    </h3>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        Click to auto-fill SMTP settings:
                    </p>
                    @foreach ([['name' => 'Gmail', 'host' => 'smtp.gmail.com', 'port' => 587, 'enc' => 'tls', 'color' => 'danger'], ['name' => 'Outlook', 'host' => 'smtp.office365.com', 'port' => 587, 'enc' => 'tls', 'color' => 'primary'], ['name' => 'Yahoo', 'host' => 'smtp.mail.yahoo.com', 'port' => 587, 'enc' => 'tls', 'color' => 'warning'], ['name' => 'Zoho', 'host' => 'smtp.zoho.com', 'port' => 587, 'enc' => 'tls', 'color' => 'success'], ['name' => 'Mailgun', 'host' => 'smtp.mailgun.org', 'port' => 587, 'enc' => 'tls', 'color' => 'secondary'], ['name' => 'SendGrid', 'host' => 'smtp.sendgrid.net', 'port' => 587, 'enc' => 'tls', 'color' => 'info']] as $preset)
                        <button type="button"
                            class="btn btn-outline-{{ $preset['color'] }} btn-sm btn-block mb-1 text-left"
                            onclick="applyPreset('{{ $preset['host'] }}', {{ $preset['port'] }}, '{{ $preset['enc'] }}')">
                            <i class="fas fa-server mr-2"></i>
                            {{ $preset['name'] }}
                            <small class="text-muted float-right">
                                {{ $preset['host'] }}
                            </small>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Tips --}}
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-2"></i> Tips
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            For Gmail, use an <strong>App Password</strong> not your regular password
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            Port <strong>587</strong> = TLS, Port <strong>465</strong> = SSL
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            Password is <strong>encrypted</strong> before saving
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            Use <strong>Send Test Email</strong> to verify settings work
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        // Apply preset values
        function applyPreset(host, port, encryption) {
            document.querySelector('[name=host]').value = host;
            document.querySelector('[name=port]').value = port;
            document.querySelector('[name=encryption]').value = encryption;

            // Flash the fields
            ['[name=host]', '[name=port]', '[name=encryption]'].forEach(function(sel) {
                var el = document.querySelector(sel);
                el.style.background = '#d1fae5';
                setTimeout(function() {
                    el.style.background = '';
                }, 1000);
            });
        }

        // Toggle password visibility
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
