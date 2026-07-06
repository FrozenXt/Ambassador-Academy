<?php

namespace Modules\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow all users to submit
    }

    public function rules()
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone' => ['nullable', 'numeric', 'digits:10'],
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string|min:10',
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }
}
