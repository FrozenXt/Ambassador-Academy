@extends('admin::layouts.app')
@section('page_title', 'SEO Settings')

@section('admin_content')

    @php $s = $settings->keyBy('key'); @endphp

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-search mr-2"></i> SEO Settings
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.seo.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $s['meta_title']->value ?? '') }}"
                        class="form-control" placeholder="My CMS — Home" />
                    <small class="text-muted">Recommended: 50-60 characters</small>
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="3" class="form-control"
                        placeholder="Short description for search engines...">{{ old('meta_description', $s['meta_description']->value ?? '') }}</textarea>
                    <small class="text-muted">Recommended: 150-160 characters</small>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_keywords"
                        value="{{ old('meta_keywords', $s['meta_keywords']->value ?? '') }}" class="form-control"
                        placeholder="keyword1, keyword2, keyword3" />
                </div>
                <div class="form-group">
                    <label>Google Analytics Measurement ID</label>
                    <input type="text" name="google_analytics" class="form-control"
                        style="font-family:monospace;font-size:13px;" placeholder="G-XXXXXXXXXX"
                        value="{{ old('google_analytics', $s['google_analytics']->value ?? '') }}">
                    <small class="text-muted">Enter your GA4 Measurement ID only (e.g. G-XXXXXXXXXX) — not the full
                        script.</small>
                </div>
                <hr>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Save SEO Settings
                </button>
            </form>
        </div>
    </div>

@endsection
