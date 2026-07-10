<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow admin users
    }

    public function rules(): array
    {
        $serviceId = $this->route('id') ?? null; // For update

        return [
            'title'            => 'required|string|max:255',
            'type'             => 'nullable|string|max:255',
            'slug'             => 'nullable|string|unique:services,slug' . ($serviceId ? ",$serviceId" : ''),
            'description'      => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'icon'             => 'nullable|string|max:100',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,gif|max:7168',
            'status'           => 'required|in:active,inactive',
            'order'            => 'nullable|integer',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
        ];
    }
}
