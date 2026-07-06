<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow access for admin users
    }

    public function rules(): array
    {
        $routeName = $this->route()->getName();

        // Menu create/update
        if ($routeName === 'admin.menus.store' || $routeName === 'admin.menus.update') {
            $menuId = $this->menu?->id ?? null; // For update
            return [
                'name'     => 'required|string|max:255',
                'slug'     => 'nullable|string|unique:menus,slug' . ($menuId ? ',' . $menuId : ''),
                'location' => 'nullable|string|in:header,footer,sidebar',
                'status'   => 'required|in:active,inactive',
            ];
        }

        // Add/Update menu item
        if ($routeName === 'admin.menus.addItem' || $routeName === 'admin.menus.updateItem') {
            return [
                'label'     => 'required|string|max:255',
                'url'       => 'nullable|string|max:255',
                'icon'      => 'nullable|string|max:100',
                'target'    => 'required|in:_self,_blank',
                'parent_id' => 'nullable|integer|exists:menu_items,id',
                'status'    => 'required|in:active,inactive',
            ];
        }

        // Reorder menu items
        if ($routeName === 'admin.menus.reorder') {
            return [
                'items'           => 'required|array',
                'items.*.id'      => 'required|exists:menu_items,id',
                'items.*.order'   => 'required|integer',
                'items.*.parent_id' => 'nullable|integer',
            ];
        }

        // Slug check
        if ($routeName === 'admin.menus.checkSlug') {
            return [
                'slug'   => 'required|string|max:255',
                'ignore' => 'nullable|integer|exists:menus,id',
            ];
        }

        return [];
    }
}
