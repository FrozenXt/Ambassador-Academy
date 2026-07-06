<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow admin users
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'currency'          => 'required|string|max:10',
            'period'            => 'required|string|max:50',
            'features'          => 'nullable|array',
            'features.*'        => 'nullable|string|max:255',
            'is_popular'        => 'sometimes|boolean',
            'sort_order'        => 'nullable|integer',
            'is_active'         => 'sometimes|boolean',
            'button_text'       => 'required|string|max:100',
            'button_url'        => 'nullable|string|max:255',
        ];
    }
}
