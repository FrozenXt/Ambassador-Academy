<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\CategoryService;
use Modules\Common\Entities\Category;
use Modules\Admin\Http\Requests\CategoryRequest;

use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters    = $request->only(['search', 'status']);
        $categories = $this->categoryService->getPaginatedCategories($filters);

        return view('admin::categories.index', compact('categories', 'filters'));
    }

    public function create()
    {
        return view('admin::categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->createCategory(
            $request->only(['name', 'description', 'status']),
            $request->file('image')
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin::categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory(
            $category->id,
            $request->only(['name', 'description', 'status']),
            $request->file('image')
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category->id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    public function updateOrder(Request $request)
    {
        foreach ($request->orders as $order) {
            Category::where('id', $order['id'])
                ->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
