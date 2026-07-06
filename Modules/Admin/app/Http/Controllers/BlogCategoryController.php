<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\BlogCategoryService;
use Modules\Common\Entities\BlogCategory;

class BlogCategoryController extends Controller
{
    protected $service;

    public function __construct(BlogCategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters    = $request->only(['search', 'status']);
        $categories = $this->service->getAll($filters);
        return view('admin::blog-categories.index', compact('categories', 'filters'));
    }

    public function create()
    {
        return view('admin::blog-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
            'order'       => 'nullable|integer',
        ]);

        $this->service->create(
            $request->only(['name', 'slug', 'description', 'status', 'order']),
            $request->file('image')
        );

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category created.');
    }

    public function edit(int $id)
    {
        $category = $this->service->findById($id);
        return view('admin::blog-categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:blog_categories,slug,' . $id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
            'order'       => 'nullable|integer',
        ]);

        $this->service->update(
            $id,
            $request->only(['name', 'slug', 'description', 'status', 'order']),
            $request->file('image')
        );

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category deleted.');
    }
}
