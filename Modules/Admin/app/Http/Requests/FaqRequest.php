<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Reorder case
        if ($this->routeIs('admin.faqs.reorder')) {
            return [
                'items' => 'required|array',
                'items.*.id' => 'required|integer|exists:faqs,id',
                'items.*.order' => 'required|integer',
            ];
        }

        return [
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
            'category'    => 'nullable|string|max:100',
            'status'      => 'required|in:active,inactive',
            'order'       => 'nullable|integer|min:0',
            'is_featured' => 'required|boolean',
        ];
    }
}
