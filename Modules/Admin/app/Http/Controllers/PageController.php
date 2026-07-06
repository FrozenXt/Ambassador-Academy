<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\PageService;
use Modules\Admin\Http\Requests\PageRequest;
use Modules\Common\Entities\Page;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'layout']);
        $pages   = $this->pageService->getPaginatedPages($filters);

        return view('admin::pages.index', compact('pages', 'filters'));
    }

    public function create()
    {
        return view('admin::pages.create');
    }

    public function store(PageRequest $request)
    {
        try {
            $this->pageService->createPage(
                $request->only([
                    'title',
                    'slug',
                    'content',
                    'layout',
                    'status',
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                    'order'
                ]),
                $request->file('featured_image')
            );

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create page: ' . $e->getMessage());
        }
    }


    public function show(int $id)
    {
        try {
            $page = $this->pageService->getPageById($id);
            return view('admin::pages.show', compact('page'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Page not found.');
        }
    }

    public function edit(int $id)
    {
        try {
            $page = $this->pageService->getPageById($id);
            return view('admin::pages.edit', compact('page'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Page not found.');
        }
    }

    public function update(PageRequest $request, int $id)
    {
        try {
            $this->pageService->updatePage(
                $id,
                $request->only([
                    'title',
                    'slug',
                    'content',
                    'layout',
                    'status',
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                    'order'
                ]),
                $request->file('featured_image')
            );

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update page: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->pageService->deletePage($id);
            return redirect()->route('admin.pages.index')
                ->with('success', 'Page moved to trash.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete page: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        try {
            $pages = $this->pageService->getTrashedPages();
            return view('admin::pages.trash', compact('pages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Failed to load trash: ' . $e->getMessage());
        }
    }

    public function restore(int $id)
    {
        try {
            $this->pageService->restorePage($id);
            return redirect()->route('admin.pages.trash')
                ->with('success', 'Page restored successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore page: ' . $e->getMessage());
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $this->pageService->forceDeletePage($id);
            return redirect()->route('admin.pages.trash')
                ->with('success', 'Page permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete page: ' . $e->getMessage());
        }
    }

    /**
     * Remove featured image from a page
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage($id)
    {
        try {
            $this->pageService->removeImage($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image removed successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'Image removed successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove image: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to remove image: ' . $e->getMessage());
        }
    }

    /**
     * Crop existing featured image
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cropImage(Request $request, $id)
    {
        try {
            $request->validate([
                'cropped_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $page = $this->pageService->getPageById($id);

            // Update the image with cropped version
            $this->pageService->updatePage(
                $id,
                $request->only(['title', 'slug', 'content', 'layout', 'status', 'meta_title', 'meta_description', 'meta_keywords', 'order']),
                $request->file('cropped_image')
            );

            $page = $this->pageService->getPageById($id);

            return response()->json([
                'success' => true,
                'message' => 'Image cropped successfully.',
                'image_url' => asset('storage/' . $page->featured_image)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to crop image: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateOrder(Request $request)
    {
        $orders = $request->orders;

        foreach ($orders as $item) {
            Page::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
