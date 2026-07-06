@extends('admin::layouts.app')
@section('page_title', 'Social Media Settings')

@section('admin_content')

    @php $s = $settings->keyBy('key'); @endphp

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-share-alt mr-2"></i> Social Media Links
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.social.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fab fa-facebook text-primary mr-2"></i> Facebook URL</label>
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
                            <label><i class="fab fa-twitter text-info mr-2"></i> Twitter URL</label>
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
                            <label><i class="fab fa-instagram text-danger mr-2"></i> Instagram URL</label>
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
                            <label><i class="fab fa-youtube text-danger mr-2"></i> YouTube URL</label>
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
                            <label><i class="fab fa-linkedin text-primary mr-2"></i> LinkedIn URL</label>
                            <input type="url" name="linkedin_url"
                                value="{{ old('linkedin_url', $s['linkedin_url']->value ?? '') }}"
                                class="form-control @error('linkedin_url') is-invalid @enderror"
                                placeholder="https://linkedin.com/company/yourcompany" />
                            @error('linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-save mr-1"></i> Save Social Settings
                </button>
            </form>
        </div>
    </div>

@endsection
