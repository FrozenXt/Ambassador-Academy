<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\FaqService;
use Modules\Admin\Http\Requests\FaqRequest;

class FaqController extends Controller
{
    protected $service;

    public function __construct(FaqService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters    = $request->only(['search', 'status', 'category', 'is_featured']);
        $faqs       = $this->service->getPaginated($filters);
        $categories = $this->service->getCategories();

        return view('admin::faqs.index', compact('faqs', 'filters', 'categories'));
    }

    public function create()
    {
        $categories = $this->service->getCategories();
        return view('admin::faqs.create', compact('categories'));
    }

    public function store(FaqRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(int $id)
    {
        $faq        = $this->service->findById($id);
        $categories = $this->service->getCategories();

        return view('admin::faqs.edit', compact('faq', 'categories'));
    }

    public function update(FaqRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus(int $id)
    {
        $this->service->toggleStatus($id);
        return back()->with('success', 'FAQ status updated.');
    }

    public function toggleFeatured(int $id)
    {
        $this->service->toggleFeatured($id);
        return back()->with('success', 'FAQ featured status updated.');
    }

    public function reorder(FaqRequest $request)
    {
        $this->service->updateOrder($request->validated()['items']);

        return response()->json(['success' => true]);
    }
}
