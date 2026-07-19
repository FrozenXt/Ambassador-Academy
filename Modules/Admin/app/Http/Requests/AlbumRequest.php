<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlbumRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $albumId = $this->route('album');

        return [
            'title' => 'required|string|max:255',

            'code' => $albumId
                ? 'nullable' // 👉 on update: don't require
                : 'required|string|max:255|unique:albums,code', // 👉 on create

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('albums', 'slug')->ignore($albumId),
            ],
            'is_gallery_category' => 'boolean',

            'description' => 'nullable|string|max:5000',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The album title is required.',
            'title.max' => 'The album title must not exceed 255 characters.',
            // 'code.required' => 'The album code is required.',
            // 'code.max' => 'The album code must not exceed 255 characters.',
            // 'code.unique' => 'This album code is already taken.',
            'slug.unique' => 'This slug is already taken.',
            'status.required' => 'Please select a status.',
            'cover_image.image' => 'The cover image must be an image file.',
            'cover_image.mimes' => 'The cover image must be a file of type: jpeg, png, jpg, gif, webp.',
            'cover_image.max' => 'The cover image must not exceed 5MB.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title)
            ]);
        }

        $this->merge([
            'is_featured' => $this->has('is_featured'),
            'sort_order' => $this->sort_order ?? 0
        ]);
    }
}
