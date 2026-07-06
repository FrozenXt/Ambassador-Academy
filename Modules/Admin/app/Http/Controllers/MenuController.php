<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Common\Entities\Menu;
use Modules\Common\Entities\MenuItem;
use Modules\Admin\Http\Requests\MenuRequest;
use Modules\Common\Entities\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // List all menus
    public function index()
    {
        $menus = Menu::withCount('allItems')->latest()->get();
        return view('admin::menus.index', compact('menus'));
    }

    // Create menu form
    public function create()
    {
        return view('admin::menus.create');
    }

    // Store new menu
    public function store(MenuRequest $request)
    {
        $slug = $request->slug ?? \Illuminate\Support\Str::slug($request->name);

        Menu::create([
            'name'     => $request->name,
            'slug'     => $slug,
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    // Edit menu + manage items
    public function edit(Menu $menu)
    {
        $menu->load(['items.children']);
        $parentItems = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('admin::menus.edit', compact('menu', 'parentItems'));
    }

    // Update menu details
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'slug'     => 'nullable|string|unique:menus,slug,' . $menu->id,
            'location' => 'nullable|string|in:header,footer,sidebar',
            'status'   => 'required|in:active,inactive',
        ]);

        $menu->update([
            'name'     => $request->name,
            'slug'     => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menu updated successfully.');
    }

    // Delete menu
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    // Add menu item
    public function addItem(MenuRequest $request, Menu $menu)
    {
        $maxOrder = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $request->parent_id ?: null)
            ->max('order') ?? 0;

        MenuItem::create([
            'menu_id'   => $menu->id,
            'parent_id' => $request->parent_id ?: null,
            'label'     => $request->label,
            'url'       => $request->url,
            'icon'      => $request->icon,
            'target'    => $request->target,
            'order'     => $maxOrder + 1,
            'status'    => $request->status,
        ]);

        return redirect()->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menu item added successfully.');
    }
    // Update menu item
    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $request->validate([
            'label'  => 'required|string|max:255',
            'url'    => 'nullable|string|max:255',
            'icon'   => 'nullable|string|max:100',
            'target' => 'required|in:_self,_blank',
            'status' => 'required|in:active,inactive',
        ]);

        $item->update([
            'label'  => $request->label,
            'url'    => $request->url,
            'icon'   => $request->icon,
            'target' => $request->target,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menu item updated.');
    }

    // Delete menu item
    public function deleteItem(Menu $menu, MenuItem $item)
    {
        $item->children()->delete();
        $item->delete();

        return redirect()->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menu item deleted.');
    }

    // Reorder items via AJAX
    public function reorder(Request $request)
    {
        $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:menu_items,id',
            'items.*.order'  => 'required|integer',
            'items.*.parent_id' => 'nullable|integer',
        ]);

        foreach ($request->items as $item) {
            MenuItem::where('id', $item['id'])->update([
                'order'     => $item['order'],
                'parent_id' => $item['parent_id'] ?: null,
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->slug);

        $query = Menu::where('slug', $slug);

        // Ignore current menu (for edit page)
        if ($request->ignore) {
            $query->where('id', '!=', $request->ignore);
        }

        return response()->json([
            'slug' => $slug,
            'exists' => $query->exists()
        ]);
    }
}
