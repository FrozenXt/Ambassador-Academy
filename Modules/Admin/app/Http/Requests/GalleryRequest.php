<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Common\Entities\Gallery;

class GalleryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'album_id' => 'required|exists:albums,id',

            // MEDIA TYPE SYSTEM
            'file_type' => 'required|in:image,video,youtube',

            // CMS TYPE SYSTEM
            'image_type' => 'required|in:' . implode(',', array_keys(Gallery::getImageTypes())),
            'image_position' => 'nullable|string|max:50',
            'type_settings' => 'nullable|array',

            // CONTENT
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_alt' => 'nullable|string|max:255',

            'sort_order' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',

            // MEDIA INPUT (IMPORTANT FIX)
            'media' => $this->isMethod('POST')
                ? 'required_if:file_type,image,video|file|mimes:jpg,jpeg,png,mp4,mov,webp|max:20480'
                : 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,webp|max:20480',
            'youtube_url' => 'required_if:file_type,youtube|nullable|url',
        ];

        // FLOATING IMAGE RULE
        if ($this->input('image_type') === Gallery::TYPE_FLOATING_IMAGE) {
            $rules['image_position'] = 'required|in:' . implode(',', array_keys(Gallery::getFloatingPositions()));
        }

        // BANNER RULE
        if ($this->input('image_type') === Gallery::TYPE_BANNER) {
            $rules['type_settings.link'] = 'nullable|url';
            $rules['type_settings.target'] = 'nullable|in:_self,_blank';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'album_id.required' => 'Please select an album',
            'album_id.exists' => 'Selected album does not exist',
            'image_type.required' => 'Please select an image type',
            'image_type.in' => 'Invalid image type selected',
            'image_position.required' => 'Please select a position for floating image',
            'image.required' => 'Please select an image to upload',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Image must be type: jpeg, png, jpg, gif, webp',
            'image.max' => 'Image size must not exceed 5MB'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort_order' => $this->sort_order !== '' && $this->sort_order !== null
                ? (int) $this->sort_order
                : null,
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
