<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Common\Services\ServiceServiceInterface;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ServiceRequest;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceServiceInterface $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $services = $this->serviceService->getPaginatedServices($filters);

        return view('admin::services.index', compact('services', 'filters'));
    }

    public function create()
    {
        return view('admin::services.create');
    }

    public function store(ServiceRequest $request)
    {
        try {
            $this->serviceService->createService(
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('admin.services.index')
                ->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $service = $this->serviceService->getServiceById($id);
            return view('admin::services.edit', compact('service'));
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Service not found.');
        }
    }

    public function update(ServiceRequest $request, int $id)
    {
        try {
            // Check if this is an AJAX request for cropping existing image
            if ($request->ajax() && $request->hasFile('cropped_image')) {
                $service = $this->serviceService->updateServiceImage($id, $request->file('cropped_image'));

                return response()->json([
                    'success' => true,
                    'message' => 'Image cropped successfully.',
                    'image_url' => asset('storage/' . $service->image)
                ]);
            }

            // Regular update with optional new image
            $this->serviceService->updateService(
                $id,
                $request->validated(),
                $request->file('image')
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service updated successfully.'
                ]);
            }

            return redirect()->route('admin.services.index')
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update service: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->serviceService->deleteService($id);
            return redirect()->route('admin.services.index')
                ->with('success', 'Service moved to trash.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete service: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        try {
            $services = $this->serviceService->getTrashedServices();
            return view('admin::services.trash', compact('services'));
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Failed to load trash: ' . $e->getMessage());
        }
    }

    public function restore(int $id)
    {
        try {
            $this->serviceService->restoreService($id);
            return redirect()->route('admin.services.trash')
                ->with('success', 'Service restored successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore service: ' . $e->getMessage());
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $this->serviceService->forceDeleteService($id);
            return redirect()->route('admin.services.trash')
                ->with('success', 'Service permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete service: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $this->serviceService->toggleServiceStatus($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    public function removeImage(int $id)
    {
        try {
            $this->serviceService->removeImage($id);

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
                    'message' => 'Failed to remove image.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to remove image.');
        }
    }

    public function show($id)
    {
        try {
            $service = $this->serviceService->getServiceById($id);

            return view('admin::services.show', compact('service'));
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Service not found.');
        }
    }

    public function reorder(Request $request)
    {
        try {
            $orders = $request->input('orders');
            $this->serviceService->updateServiceOrder($orders);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order.'
            ], 500);
        }
    }
}
