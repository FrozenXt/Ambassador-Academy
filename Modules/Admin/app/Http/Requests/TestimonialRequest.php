<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated admins
    }

    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'position'    => 'nullable|string|max:255',
            'company'     => 'nullable|string|max:255',
            'content'     => 'required|string|min:10',
            'rating'      => 'required|integer|min:1|max:5',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
            'status'      => 'required|in:active,inactive',
            'order'       => 'nullable|integer',
            'is_featured' => 'required|boolean',
        ];
    }
}
