<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Bulk delete
        if ($this->routeIs('admin.media.bulk-delete')) {
            return [
                'ids'   => 'required|array',
                'ids.*' => 'required|integer|exists:media,id',
            ];
        }

        // Store (upload files)
        if ($this->isMethod('post')) {
            return [
                'files'   => 'required|array',
                'files.*' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,mp4,mov,pdf,doc,docx,xls,xlsx',

                'alt_text'    => 'nullable|string|max:255',
                'title'       => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'tags'        => 'nullable|string',
            ];
        }

        // Update
        return [
            'title'       => 'nullable|string|max:255',
            'alt_text'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tags'        => 'nullable|string',
        ];
    }
}
