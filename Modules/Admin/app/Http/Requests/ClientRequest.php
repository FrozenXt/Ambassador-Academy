<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow all admins
    }

    public function rules()
    {
        $clientId = $this->route('client') ? $this->route('client') : null;

        return [
            // Basic Information
            'name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $clientId,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',

            // Address Information
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',

            // Contact Person Details
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_designation' => 'nullable|string|max:255',

            // Business Details
            'industry_type' => 'nullable|string|max:100',
            'employee_count' => 'nullable|integer|min:0',
            'annual_revenue' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',

            // Account Management
            'assigned_to' => 'nullable|exists:users,id',
            'client_type' => 'nullable|in:individual,business,government,nonprofit',
            'payment_terms' => 'nullable|in:immediate,net_7,net_15,net_30,net_60',
            'credit_limit' => 'nullable|numeric|min:0',

            // Status and Settings
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
            'image' => $clientId ? 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048' : 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Client name is required.',
            'image.required' => 'Client image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed image types: jpg, jpeg, png, webp, svg.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
