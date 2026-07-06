<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\TestimonialService;
use Modules\Admin\Http\Requests\TestimonialRequest;
use Modules\Common\Entities\Testimonial;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    protected $service;

    public function __construct(TestimonialService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters      = $request->only(['search', 'status', 'rating', 'is_featured']);
        $testimonials = $this->service->getPaginated($filters);

        return view('admin::testimonials.index', compact('testimonials', 'filters'));
    }

    public function create()
    {
        return view('admin::testimonials.create');
    }

    public function store(TestimonialRequest $request)
    {
        $this->service->create(
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial added successfully.');
    }

    public function edit(int $id)
    {
        $testimonial = $this->service->findById($id);
        return view('admin::testimonials.edit', compact('testimonial'));
    }
    public function show($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin::testimonials.show', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, int $id)
    {
        $this->service->update(
            $id,
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
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

    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->input('orders'); // <-- Fix here

            if (!$orders || !is_array($orders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order data'
                ], 422);
            }

            foreach ($orders as $item) {
                $testimonial = Testimonial::find($item['id']);
                if ($testimonial) {
                    $testimonial->order = $item['order'];
                    $testimonial->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            // Log the exception to debug
            Log::error('Testimonial order update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order'
            ], 500);
        }
    }
    public function trash()
    {
        $testimonials = Testimonial::onlyTrashed()->get();

        return view('admin::testimonials.trash', compact('testimonials'));
    }
}
