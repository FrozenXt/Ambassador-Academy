<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all admin users
    }

    public function rules(): array
    {
        $pageId = $this->route('id') ?? null;

        return [
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:pages,slug' . ($pageId ? ',' . $pageId : ''),
            'content'          => 'nullable|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'layout'           => 'required|in:default,full-width,sidebar,landing',
            'status'           => 'required|in:published,draft',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'order'            => 'nullable|integer',
        ];
    }
}
