<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\ClientService;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'is_active',
            'is_verified',
            'client_type',
            'industry_type',
            'country',
            'assigned_to',
            'date_from',
            'date_to',

        ]);

        $clients = $this->clientService->getPaginatedClients($filters, $request->get('per_page', 10));
        $statistics = $this->clientService->getStatistics();

        return view('admin::clients.index', compact('clients', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('admin::clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(ClientRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('clients', 'public');
        }

        $client = $this->clientService->createClient($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = $this->clientService->getClientById($id);
        return view('admin::clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(ClientRequest $request, $id)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $existing = $this->clientService->getClientById($id);
            if ($existing->image) {
                Storage::disk('public')->delete($existing->image);
            }
            $validated['image'] = $request->file('image')->store('clients', 'public');
        }

        $client = $this->clientService->updateClient($id, $validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }
    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $this->clientService->deleteClient($id);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Toggle client active status.
     */
    public function toggleStatus($id)
    {
        $this->clientService->toggleClientStatus($id);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client status updated successfully.');
    }

    /**
     * Verify a client.
     */
    public function verify($id)
    {
        $this->clientService->verifyClient($id);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client verified successfully.');
    }

    /**
     * Export clients.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'client_type']);
        $clients = $this->clientService->getAllClients($filters);

        // Implement export logic
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    /**
     * Bulk action for clients.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $ids = explode(',', $request->ids);

        switch ($validated['action']) {
            case 'activate':
                $this->clientService->bulkUpdateStatus($ids, true);
                $message = 'Selected clients have been activated.';
                break;
            case 'deactivate':
                $this->clientService->bulkUpdateStatus($ids, false);
                $message = 'Selected clients have been deactivated.';
                break;
            case 'delete':
                $this->clientService->bulkDeleteClients($ids);
                $message = 'Selected clients have been deleted.';
                break;
        }

        return redirect()->route('admin.clients.index')
            ->with('success', $message);
    }

    // ── Trash Management ──
    public function removeImage($id)
    {
        $client = $this->clientService->getClientById($id);

        if ($client->image) {
            Storage::disk('public')->delete($client->image);
            $this->clientService->updateClient($id, ['image' => null]);
        }

        return back()->with('success', 'Image removed successfully.');
    }
}
