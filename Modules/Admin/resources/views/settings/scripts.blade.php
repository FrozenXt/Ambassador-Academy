@extends('admin::layouts.app')
@section('page_title', 'Custom Scripts')

@section('admin_content')

    @php $s = $settings->keyBy('key'); @endphp

    <div class="card card-outline card-dark">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-code mr-2"></i> Custom Scripts
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Warning:</strong> Only add trusted scripts here. Malicious scripts can harm your site.
            </div>
            <form action="{{ route('admin.settings.scripts.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>
                        <i class="fas fa-arrow-up mr-1"></i> Header Scripts
                        <small class="text-muted">(injected inside &lt;head&gt; tag)</small>
                    </label>
                    <textarea name="header_scripts" rows="8" class="form-control" style="font-family:monospace;font-size:13px;"
                        placeholder="<!-- Scripts to inject in <head> -->">{{ old('header_scripts', $s['header_scripts']->value ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label>
                        <i class="fas fa-arrow-down mr-1"></i> Footer Scripts
                        <small class="text-muted">(injected before &lt;/body&gt; tag)</small>
                    </label>
                    <textarea name="footer_scripts" rows="8" class="form-control" style="font-family:monospace;font-size:13px;"
                        placeholder="<!-- Scripts to inject before </body> -->">{{ old('footer_scripts', $s['footer_scripts']->value ?? '') }}</textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-save mr-1"></i> Save Scripts
                </button>
            </form>
        </div>
    </div>

@endsection
