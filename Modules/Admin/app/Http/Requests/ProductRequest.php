<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow admin users
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'url'         => 'nullable|url|max:500',
            'features'    => 'nullable|array',
            'features.*'  => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
        ];
    }
}
