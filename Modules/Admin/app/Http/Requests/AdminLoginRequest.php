<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all admins to make this request
        return true;
    }

    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email is required',
            'email.email'       => 'Email must be a valid email address',
            'password.required' => 'Password is required',
        ];
    }
}
