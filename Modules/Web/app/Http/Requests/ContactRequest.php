<?php

namespace Modules\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email',
            'phone'                 => ['nullable', 'string', 'max:30'],
            'subject'               => 'required|string|max:255',
            'message'               => 'required|string|min:10',
            'g-recaptcha-response'  => 'nullable|string',
        ];
    }
}
