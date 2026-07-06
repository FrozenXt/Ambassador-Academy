<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow all admins to proceed
    }

    public function rules()
    {
        // Determine if this is an update or create
        $bannerId = $this->route('banner') ? $this->route('banner')->id : null;

        return [
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'image'         => $bannerId
                ? 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:6144'
                : 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:6144',
            'button_text'   => 'nullable|string|max:100',
            'button_url'    => 'nullable|string|max:255',
            'button_text_2' => 'nullable|string|max:100',
            'button_url_2'  => 'nullable|string|max:255',
            'status'        => 'required|in:active,inactive',
            'order'         => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Banner title is required.',
            'image.required' => 'Banner image is required.',
            'status.required' => 'Banner status is required.',
            'status.in'      => 'Invalid status selected.',
            'image.image'    => 'The file must be an image.',
            'image.mimes'    => 'Allowed image types: jpg, jpeg, png, webp, gif, svg.',
            'image.max'      => 'Image size must not exceed 6MB.',
        ];
    }
}
