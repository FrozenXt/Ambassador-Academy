<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\CounterService;
use Modules\Admin\Http\Requests\CounterRequest;

class CounterController extends Controller
{
    protected $service;

    public function __construct(CounterService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters  = $request->only(['search', 'status']);
        $counters = $this->service->getPaginated($filters);

        return view('admin::counters.index', compact('counters', 'filters'));
    }

    public function create()
    {
        return view('admin::counters.create');
    }

    public function store(CounterRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter created successfully.');
    }
    public function edit(int $id)
    {
        $counter = $this->service->findById($id);
        return view('admin::counters.edit', compact('counter'));
    }

    public function update(CounterRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter deleted.');
    }

    public function toggleStatus(int $id)
    {
        $this->service->toggleStatus($id);
        return back()->with('success', 'Counter status updated.');
    }

    public function reorder(CounterRequest $request)
    {
        $this->service->updateOrder($request->validated()['items']);

        return response()->json(['success' => true]);
    }
}
