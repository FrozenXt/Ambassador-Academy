<?php


namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Common\Entities\Pricing;
use Illuminate\Routing\Controller;
use Modules\Common\Services\PricingService;
use Modules\Admin\Http\Requests\PricingRequest;

class PricingController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active']);
        $pricings = $this->pricingService->getPaginatedPricings($filters, $request->get('per_page', 10));

        return view('admin::pricings.index', compact('pricings'));
    }

    public function create()
    {
        return view('admin::pricings.create');
    }

    public function store(PricingRequest $request)
    {
        $this->pricingService->createPricing($request->validated());

        return redirect()->route('admin.pricings.index')
            ->with('success', 'Pricing plan created successfully.');
    }

    public function edit($id)
    {
        $pricing = $this->pricingService->getPricingById($id);
        return view('admin::pricings.edit', compact('pricing'));
    }

    public function update(PricingRequest $request, $id)
    {
        $this->pricingService->updatePricing($id, $request->validated());

        return redirect()->route('admin.pricings.index')
            ->with('success', 'Pricing plan updated successfully.');
    }

    public function destroy($id)
    {
        $this->pricingService->deletePricing($id);

        return redirect()->route('admin.pricings.index')
            ->with('success', 'Pricing plan deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $this->pricingService->togglePricingStatus($id);

        return redirect()->route('admin.pricings.index')
            ->with('success', 'Pricing status updated successfully.');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $order) {
            Pricing::where('id', $order['id'])->update([
                'sort_order' => $order['sort_order']
            ]);
        }

        return response()->json(['success' => true]);
    }
}
