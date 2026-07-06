<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Detect which method is calling
        if ($this->routeIs('admin.counters.reorder')) {
            return $this->reorderRules();
        }

        return $this->commonRules();
    }

    /**
     * Common rules for store & update
     */
    protected function commonRules()
    {
        return [
            'title'       => 'required|string|max:255',
            'number'      => 'required|string|max:50',
            'suffix'      => 'nullable|string|max:20',
            'prefix'      => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:20',
            'status'      => 'required|in:active,inactive',
            'order'       => 'nullable|integer|min:0',
        ];
    }

    /**
     * Rules for reorder
     */
    protected function reorderRules()
    {
        return [
            'items'         => 'required|array',
            'items.*.id'    => 'required|integer|exists:counters,id',
            'items.*.order' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'number.required' => 'Number is required.',
            'items.required' => 'Reorder items are required.',
        ];
    }
}
