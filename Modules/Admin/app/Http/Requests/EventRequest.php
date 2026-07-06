<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Reorder ko lagi validation
        if ($this->routeIs('admin.events.reorder')) {
            return [
                'items' => 'required|array',
                'items.*.id' => 'required|integer|exists:events,id',
                'items.*.order' => 'required|integer',
            ];
        }

        // Slug check ko lagi validation
        if ($this->routeIs('admin.events.check-slug')) {
            return [
                'slug' => 'required|string|max:255',
                'id'   => 'nullable|integer',
            ];
        }

        return [
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|unique:events,slug,' . $this->route('id'),
            'short_description' => 'nullable|string|max:500',
            'content'           => 'nullable|string',

            'image' => $this->isMethod('post')
                ? 'nullable|image|mimes:jpg,jpeg,png,webp,svg,gif|max:3072'
                : 'nullable|image|mimes:jpg,jpeg,png,webp,svg,gif|max:3072',

            'location'          => 'nullable|string|max:255',
            'venue'             => 'nullable|string|max:255',

            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',

            'organizer'         => 'nullable|string|max:255',
            'contact_email'     => 'nullable|email',
            'contact_phone'     => 'nullable|string|max:20',
            'registration_url'  => 'nullable|url',

            'type'   => 'required|in:event,announcement',
            'status' => 'required|in:published,draft',

            'order'        => 'nullable|integer',
            'is_featured'  => 'nullable|boolean',
        ];
    }
}
