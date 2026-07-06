<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FooterSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'footer_text'  => 'nullable|string',
            'footer_about' => 'nullable|string',
        ];
    }
}
