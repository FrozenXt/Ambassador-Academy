<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'facebook_url'  => 'nullable|url',
            'twitter_url'   => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url'   => 'nullable|url',
            'linkedin_url'  => 'nullable|url',
        ];
    }
}
