<?php


namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\NoticeService;
use Modules\Common\Entities\Notice;
use Modules\Admin\Http\Requests\NoticeRequest;

class NoticeController extends Controller
{
    protected $noticeService;

    public function __construct(NoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $notices = $this->noticeService->search($request->search);
        } else {
            $notices = $this->noticeService->getAll(15);
        }

        $statistics = $this->noticeService->getStatistics();


        return view('admin::notices.index', compact('notices', 'statistics'));
    }

    public function create()
    {
        return view('admin::notices.create');
    }

    public function store(NoticeRequest $request)
    {
        $this->noticeService->create($request->validated());

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }


    public function show($id)
    {
        $notice = $this->noticeService->find($id);
        return view('admin::notices.show', compact('notice'));
    }

    public function edit($id)
    {
        $notice = $this->noticeService->find($id);
        return view('admin::notices.edit', compact('notice'));
    }

    public function update(NoticeRequest $request, $id)
    {
        $this->noticeService->update($id, $request->validated());

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy($id)
    {
        $this->noticeService->delete($id);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $notice = $this->noticeService->toggleStatus($id);
        return response()->json(['success' => true, 'status' => $notice->status]);
    }

    public function toggleFeatured($id)
    {
        $notice = $this->noticeService->toggleFeatured($id);
        return response()->json(['success' => true, 'is_featured' => $notice->is_featured]);
    }


    public function reorder(NoticeRequest $request)
    {
        $ids = $request->validated()['ids'];

        foreach ($ids as $order => $id) {
            Notice::where('id', $id)->update(['sort_order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function checkSlug(NoticeRequest $request)
    {
        $slug = $request->validated()['slug'];
        $id = $request->validated()['id'] ?? null;

        $query = Notice::where('slug', $slug);
        if ($id) $query->where('id', '!=', $id);

        return response()->json(['exists' => $query->exists()]);
    }
}
