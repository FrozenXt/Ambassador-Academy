@extends('admin::layouts.app')
@section('page_title', 'Edit Email Configuration')

@section('page_actions')
    <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit — {{ $setting->from_name }}
                        @if ($setting->is_active)
                            <span class="badge badge-success ml-2">Active</span>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email-settings.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Mailer Type --}}
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Mailer <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="mailer" class="form-control @error('mailer') is-invalid @enderror"
                                value="{{ old('mailer', 'smtp') }}" placeholder="Enter mailer (e.g. smtp)">

                            @error('mailer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SMTP Settings --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">SMTP Host <span class="text-danger">*</span></label>
                                    <input type="text" name="host" value="{{ old('host', $setting->host) }}"
                                        class="form-control @error('host') is-invalid @enderror"
                                        placeholder="e.g. smtp.gmail.com" />
                                    @error('host')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Port <span class="text-danger">*</span></label>
                                    <input type="number" name="port" value="{{ old('port', $setting->port) }}"
                                        class="form-control @error('port') is-invalid @enderror" />
                                    @error('port')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ old('username', $setting->username) }}"
                                        class="form-control @error('username') is-invalid @enderror" autocomplete="off" />
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordInput"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Leave empty to keep current" autocomplete="new-password" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePassword()">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Leave empty to keep current password.</small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Encryption <span class="text-danger">*</span></label>
                                    <select name="encryption"
                                        class="form-control @error('encryption') is-invalid @enderror">
                                        <option value="tls"
                                            {{ old('encryption', $setting->encryption) == 'tls' ? 'selected' : '' }}>TLS
                                        </option>
                                        <option value="ssl"
                                            {{ old('encryption', $setting->encryption) == 'ssl' ? 'selected' : '' }}>SSL
                                        </option>
                                        <option value="none"
                                            {{ old('encryption', $setting->encryption) == 'none' ? 'selected' : '' }}>None
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
                        <h6 class="font-weight-bold text-muted mb-3"><i class="fas fa-user mr-2"></i> From Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">From Address <span class="text-danger">*</span></label>
                                    <input type="email" name="from_address"
                                        value="{{ old('from_address', $setting->from_address) }}"
                                        class="form-control @error('from_address') is-invalid @enderror" />
                                    @error('from_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">From Name <span class="text-danger">*</span></label>
                                    <input type="text" name="from_name"
                                        value="{{ old('from_name', $setting->from_name) }}"
                                        class="form-control @error('from_name') is-invalid @enderror" />
                                    @error('from_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Admin Notification Email --}}
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

                        {{-- Active Toggle --}}
                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActive"
                                    value="1" {{ old('is_active', $setting->is_active) ? 'checked' : '' }} />
                                <label class="custom-control-label font-weight-bold" for="isActive">Set as Active
                                    Configuration</label>
                            </div>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Update
                            Configuration</button>
                        <a href="{{ route('admin.email-settings.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}

    </div>
@endsection

@section('extra_js')
    <script>
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
    </script>
@endsection
