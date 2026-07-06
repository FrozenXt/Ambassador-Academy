<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\BlogService;
use Modules\Common\Services\BlogCategoryService;

class BlogController extends Controller
{
    protected $blogService;
    protected $categoryService;

    public function __construct(BlogService $blogService, BlogCategoryService $categoryService)
    {
        $this->blogService      = $blogService;
        $this->categoryService  = $categoryService;
    }

    public function index(Request $request)
    {
        $filters    = $request->only(['search', 'status', 'category_id', 'is_featured']);
        $blogs      = $this->blogService->getPaginated($filters);
        $categories = $this->categoryService->getActive();

        return view('admin::blogs.index', compact('blogs', 'filters', 'categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->getActive();
        return view('admin::blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:blogs,slug',
            'excerpt'          => 'nullable|string|max:700',
            'content'          => 'nullable|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'category_id'      => 'nullable|exists:blog_categories,id',
            'status'           => 'required|in:published,draft',
            'tags'             => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'order'            => 'nullable|integer',
        ]);

        $this->blogService->create(
            $request->only([
                'title',
                'slug',
                'excerpt',
                'content',
                'category_id',
                'status',
                'tags',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'order',
                'is_featured',
                'allow_comments',
            ]),
            $request->file('featured_image')
        );

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(int $id)
    {
        $blog       = $this->blogService->findById($id);
        $categories = $this->categoryService->getActive();
        return view('admin::blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:blogs,slug,' . $id,
            'excerpt'          => 'nullable|string|max:700',
            'content'          => 'nullable|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'category_id'      => 'nullable|exists:blog_categories,id',
            'status'           => 'required|in:published,draft',
            'tags'             => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'order'            => 'nullable|integer',
        ]);

        $this->blogService->update(
            $id,
            $request->only([
                'title',
                'slug',
                'excerpt',
                'content',
                'category_id',
                'status',
                'tags',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'order',
                'is_featured',
                'allow_comments',
            ]),
            $request->file('featured_image')
        );

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->blogService->delete($id);
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog moved to trash.');
    }

    public function trash()
    {
        $blogs = $this->blogService->getTrashed();
        return view('admin::blogs.trash', compact('blogs'));
    }

    public function restore(int $id)
    {
        $this->blogService->restore($id);
        return redirect()->route('admin.blogs.trash')
            ->with('success', 'Blog restored successfully.');
    }

    public function forceDelete(int $id)
    {
        $this->blogService->forceDelete($id);
        return redirect()->route('admin.blogs.trash')
            ->with('success', 'Blog permanently deleted.');
    }

    public function toggleStatus(int $id)
    {
        $this->blogService->toggleStatus($id);
        return back()->with('success', 'Blog status updated.');
    }

    public function toggleFeatured(int $id)
    {
        $this->blogService->toggleFeatured($id);
        return back()->with('success', 'Blog featured status updated.');
    }

    public function removeImage(int $id)
    {
        $this->blogService->removeImage($id);
        return back()->with('success', 'Image removed.');
    }
    public function updateSortOrder(Request $request)
    {
        try {
            foreach ($request->orders as $item) {
                $this->blogService->updateOrder($item['id'], $item['sort_order']);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
