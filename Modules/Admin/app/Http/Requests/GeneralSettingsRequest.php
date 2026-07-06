<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated admins
    }

    public function rules()
    {
        return [
            'site_name'        => 'required|string|max:255',
            'site_email'       => 'nullable|email',
            'site_phone'       => 'nullable|string|max:20',
            'site_address'     => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'site_favicon'     => 'nullable|file|mimes:png,ico,svg|max:512',
            'google_map_embed' => 'nullable|string',
        ];
    }
}
