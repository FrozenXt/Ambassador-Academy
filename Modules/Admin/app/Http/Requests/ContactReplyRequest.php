<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactReplyRequest extends FormRequest
{
    /**
     * Allow request or not
     */
    public function authorize()
    {
        return true; // later you can add admin auth check
    }

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'admin_reply' => 'required|string|min:5|max:2000',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages()
    {
        return [
            'admin_reply.required' => 'Reply message is required.',
            'admin_reply.min'      => 'Reply must be at least 5 characters.',
            'admin_reply.max'      => 'Reply cannot exceed 2000 characters.',
        ];
    }
}
