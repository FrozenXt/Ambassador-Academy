<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\EventService;
use Modules\Admin\Http\Requests\EventRequest;
use Modules\Common\Entities\Event;

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'type', 'period']);
        $events  = $this->service->getPaginated($filters);

        return view('admin::events.index', compact('events', 'filters'));
    }

    public function create()
    {
        return view('admin::events.create');
    }

    public function store(EventRequest $request)
    {
        $this->service->create(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(int $id)
    {
        $event = $this->service->findById($id);
        return view('admin::events.edit', compact('event'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('web::event-detail', compact('event'));
    }

    public function update(EventRequest $request, int $id)
    {
        $this->service->update(
            $id,
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted.');
    }

    public function toggleStatus(int $id)
    {
        $this->service->toggleStatus($id);
        return back()->with('success', 'Status updated.');
    }

    public function toggleFeatured(int $id)
    {
        $this->service->toggleFeatured($id);
        return back()->with('success', 'Featured status updated.');
    }

    public function removeImage(int $id)
    {
        $this->service->removeImage($id);
        return back()->with('success', 'Image removed.');
    }
    public function reorder(EventRequest $request)
    {
        foreach ($request->validated()['items'] as $item) {
            Event::find($item['id'])->update([
                'order' => $item['order']
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function checkSlug(EventRequest $request)
    {
        $slug = $request->slug;
        $id   = $request->id;

        $query = Event::where('slug', $slug);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        return response()->json([
            'exists' => $query->exists()
        ]);
    }
}
