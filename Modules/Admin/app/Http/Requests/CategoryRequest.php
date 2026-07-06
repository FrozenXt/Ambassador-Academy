<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow all users who reach this point
    }

    public function rules()
    {
        // Determine if this is an update or create
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name'        => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
            'status'      => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.unique'   => 'This category name is already taken',
            'status.required' => 'Category status is required',
            'status.in'       => 'Invalid status selected',
            'image.image'     => 'The file must be an image',
            'image.mimes'     => 'Allowed image types: jpg, jpeg, png, webp, gif, svg',
            'image.max'       => 'Image size must not exceed 2MB',
        ];
    }
}
