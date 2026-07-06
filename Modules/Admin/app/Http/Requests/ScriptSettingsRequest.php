<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScriptsSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
        ];
    }
}
