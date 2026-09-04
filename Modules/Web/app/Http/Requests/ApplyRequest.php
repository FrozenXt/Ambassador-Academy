<?php

namespace Modules\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'applying_for'          => 'required|string|max:255',
            'first_name'            => 'required|string|max:255',
            'middle_name'           => 'nullable|string|max:255',
            'last_name'             => 'required|string|max:255',
            'dob'                   => 'required|date|before:today',
            'gender'                => 'required|in:Male,Female,Other',
            'guardian_name'         => 'required|string|max:255',
            'email'                 => 'required|email',
            'phone'                 => 'required|string|max:30',
            'address'               => 'required|string',
            'g-recaptcha-response'  => 'nullable|string',
        ];
    }
}
