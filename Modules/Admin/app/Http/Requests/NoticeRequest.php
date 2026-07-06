<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow admin users
    }

    public function rules(): array
    {
        $routeName = $this->route()->getName();

        // Create / Update Notice
        if ($routeName === 'admin.notices.store' || $routeName === 'admin.notices.update') {
            $noticeId = $this->notice?->id ?? null; // For update
            return [
                'title'            => 'required|string|max:255',
                'short_description' => 'nullable|string|max:500',
                'content'          => 'required|string',
                'type'             => 'required|in:notice,news',
                'priority'         => 'required|in:low,medium,high,urgent',
                'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status'           => 'boolean',
                'is_featured'      => 'boolean',
                'published_date'   => 'nullable|date',
                'expiry_date'      => 'nullable|date|after:published_date',
                'tags'             => 'nullable|string',
                'slug'             => 'nullable|string|unique:notices,slug' . ($noticeId ? ',' . $noticeId : ''),
            ];
        }

        // Reorder Notices
        if ($routeName === 'admin.notices.reorder') {
            return [
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:notices,id',
            ];
        }

        // Slug check
        if ($routeName === 'admin.notices.checkSlug') {
            return [
                'slug' => 'required|string|max:255',
                'id'   => 'nullable|integer|exists:notices,id',
            ];
        }

        return [];
    }
}
