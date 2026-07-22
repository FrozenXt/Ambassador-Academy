<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow admin users
    }

    public function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'base' => 'nullable|string',
            'style' => 'nullable|string',
            'served' => 'nullable|string',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'status'          => 'required',
            'url'             => 'nullable|string',
            'features'        => 'nullable|array',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,webp,svg,avif|max:2048',
            'category_ids'    => 'required|array|min:1',
            'category_ids.*'  => 'exists:categories,id',
        ];
    }
}
