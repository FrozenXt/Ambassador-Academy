@extends('admin::layouts.app')
@section('page_title', 'Footer Settings')

@section('admin_content')

    @php $s = $settings->keyBy('key'); @endphp

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shoe-prints mr-2"></i> Footer Settings
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.footer.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Footer Copyright Text</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $s['footer_text']->value ?? '') }}"
                        class="form-control" placeholder="© 2026 My CMS. All rights reserved." />
                </div>
                <div class="form-group">
                    <label>Footer About Text</label>
                    <textarea name="footer_about" rows="4" class="form-control" placeholder="Short about text for footer...">{{ old('footer_about', $s['footer_about']->value ?? '') }}</textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-save mr-1"></i> Save Footer Settings
                </button>
            </form>
        </div>
    </div>

@endsection
