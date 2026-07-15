@extends('admin::layouts.app')
@section('page_title', 'Social Media Settings')

@section('admin_content')

    @php
        $s = $settings->keyBy('key');

        $knownKeys = [
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'whatsapp_url',
            'viber_url',
            'tiktok_url',
        ];

        $customLinks = $settings->whereNotIn('key', $knownKeys);

        $isActive = fn($key) => $s[$key]->is_active ?? true;
    @endphp

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <iconify-icon icon="mdi:share-variant" class="mr-2"></iconify-icon> Social Media Links
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.social.update') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="ic:outline-facebook" style="color:#1877F2;"
                                        class="mr-2"></iconify-icon> Facebook URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_facebook_url"
                                        name="is_active[facebook_url]" value="1"
                                        {{ $isActive('facebook_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_facebook_url"></label>
                                </div>
                            </label>
                            <input type="url" name="facebook_url"
                                value="{{ old('facebook_url', $s['facebook_url']->value ?? '') }}"
                                class="form-control @error('facebook_url') is-invalid @enderror"
                                placeholder="https://facebook.com/yourpage" />
                            @error('facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mdi:twitter" style="color:#1DA1F2;" class="mr-2"></iconify-icon>
                                    Twitter URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_twitter_url"
                                        name="is_active[twitter_url]" value="1"
                                        {{ $isActive('twitter_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_twitter_url"></label>
                                </div>
                            </label>
                            <input type="url" name="twitter_url"
                                value="{{ old('twitter_url', $s['twitter_url']->value ?? '') }}"
                                class="form-control @error('twitter_url') is-invalid @enderror"
                                placeholder="https://twitter.com/yourhandle" />
                            @error('twitter_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mdi:instagram" style="color:#E4405F;"
                                        class="mr-2"></iconify-icon> Instagram URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_instagram_url"
                                        name="is_active[instagram_url]" value="1"
                                        {{ $isActive('instagram_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_instagram_url"></label>
                                </div>
                            </label>
                            <input type="url" name="instagram_url"
                                value="{{ old('instagram_url', $s['instagram_url']->value ?? '') }}"
                                class="form-control @error('instagram_url') is-invalid @enderror"
                                placeholder="https://instagram.com/yourhandle" />
                            @error('instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mdi:youtube" style="color:#FF0000;" class="mr-2"></iconify-icon>
                                    YouTube URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_youtube_url"
                                        name="is_active[youtube_url]" value="1"
                                        {{ $isActive('youtube_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_youtube_url"></label>
                                </div>
                            </label>
                            <input type="url" name="youtube_url"
                                value="{{ old('youtube_url', $s['youtube_url']->value ?? '') }}"
                                class="form-control @error('youtube_url') is-invalid @enderror"
                                placeholder="https://youtube.com/yourchannel" />
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mdi:linkedin" style="color:#0A66C2;"
                                        class="mr-2"></iconify-icon> LinkedIn URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_linkedin_url"
                                        name="is_active[linkedin_url]" value="1"
                                        {{ $isActive('linkedin_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_linkedin_url"></label>
                                </div>
                            </label>
                            <input type="url" name="linkedin_url"
                                value="{{ old('linkedin_url', $s['linkedin_url']->value ?? '') }}"
                                class="form-control @error('linkedin_url') is-invalid @enderror"
                                placeholder="https://linkedin.com/company/yourcompany" />
                            @error('linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mingcute:whatsapp-line" style="color:#25D366;"
                                        class="mr-2"></iconify-icon> What'sApp URL</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_whatsapp_url"
                                        name="is_active[whatsapp_url]" value="1"
                                        {{ $isActive('whatsapp_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_whatsapp_url"></label>
                                </div>
                            </label>
                            <input type="url" name="whatsapp_url"
                                value="{{ old('whatsapp_url', $s['whatsapp_url']->value ?? '') }}"
                                class="form-control @error('whatsapp_url') is-invalid @enderror"
                                placeholder="https://wa.me/yourphonenumber" />
                            @error('whatsapp_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="mdi:viber" style="color:#7360F2;"
                                        class="mr-2"></iconify-icon> Viber</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_viber_url"
                                        name="is_active[viber_url]" value="1"
                                        {{ $isActive('viber_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_viber_url"></label>
                                </div>
                            </label>
                            <input type="url" name="viber_url"
                                value="{{ old('viber_url', $s['viber_url']->value ?? '') }}"
                                class="form-control @error('viber_url') is-invalid @enderror"
                                placeholder="https://invite.viber.com/yourphonenumber" />
                            @error('viber_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span><iconify-icon icon="ic:baseline-tiktok" class="mr-2"></iconify-icon> TikTok</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active_tiktok_url"
                                        name="is_active[tiktok_url]" value="1"
                                        {{ $isActive('tiktok_url') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active_tiktok_url"></label>
                                </div>
                            </label>
                            <input type="url" name="tiktok_url"
                                value="{{ old('tiktok_url', $s['tiktok_url']->value ?? '') }}"
                                class="form-control @error('tiktok_url') is-invalid @enderror"
                                placeholder="https://www.tiktok.com/@yourusername" />
                            @error('tiktok_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Dynamically added custom social links: toggle + delete button ── --}}
                    @foreach ($customLinks as $link)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <iconify-icon icon="{{ $link->icon ?: 'mdi:link-variant' }}"
                                            class="mr-2"></iconify-icon>
                                        {{ ucwords(str_replace(['_url', '_'], ['', ' '], $link->key)) }}
                                    </span>
                                    <div class="d-flex align-items-center">
                                        <div class="custom-control custom-switch mr-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="active_{{ $link->key }}" name="is_active[{{ $link->key }}]"
                                                value="1" {{ $link->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="active_{{ $link->key }}"></label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger py-0 px-2"
                                            data-delete-key="{{ $link->key }}">
                                            <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </label>
                                <input type="url" name="{{ $link->key }}"
                                    value="{{ old($link->key, $link->value) }}"
                                    class="form-control @error($link->key) is-invalid @enderror"
                                    placeholder="https://..." />
                                @error($link->key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                </div>
                <hr>
                <button type="submit" class="btn btn-info">
                    <iconify-icon icon="mdi:content-save-outline" class="mr-1"></iconify-icon> Save Social Settings
                </button>
            </form>
        </div>
    </div>

    {{-- ── Add New Media section ── --}}
    <div class="card card-outline card-secondary mt-3">
        <div class="card-header">
            <h3 class="card-title">
                <iconify-icon icon="mdi:plus-circle-outline" class="mr-2"></iconify-icon> Add New Media
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.social.store') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Platform Name</label>
                            <input type="text" name="key" class="form-control @error('key') is-invalid @enderror"
                                placeholder="e.g. pinterest_url" required />
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Icon Name</label>
                            <input type="text" name="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                placeholder="e.g. mdi:pinterest" />
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>URL</label>
                            <input type="url" name="value"
                                class="form-control @error('value') is-invalid @enderror"
                                placeholder="https://pinterest.com/yourprofile" required />
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-block">
                            <iconify-icon icon="mdi:plus"></iconify-icon> Add
                        </button>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">
                    Browse icon names at <a href="https://icon-sets.iconify.design/"
                        target="_blank">icon-sets.iconify.design</a>
                    (e.g. <code>mdi:pinterest</code>). New links are active by default. Use the toggle to hide/show, or the
                    trash icon to delete permanently.
                </small>
            </form>
        </div>
    </div>

    {{-- ── Hidden delete forms — outside all other forms to keep valid HTML ── --}}
    @foreach ($customLinks as $link)
        <form id="delete-form-{{ $link->key }}" action="{{ route('admin.settings.social.destroy', $link->key) }}"
            method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@section('extra_js')
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-delete-key]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const key = btn.getAttribute('data-delete-key');
                    const form = document.getElementById('delete-form-' + key);
                    if (!form) {
                        console.error('Delete form not found for key:', key);
                        return;
                    }
                    if (confirm('Permanently delete "' + key + '"? This cannot be undone.')) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
