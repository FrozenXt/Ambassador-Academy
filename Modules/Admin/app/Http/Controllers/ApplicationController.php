<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\ApplicationService;
use Modules\Common\Entities\Application;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * List applications
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'applying_for']);

        $applications = $this->applicationService->getPaginatedApplications($filters);
        $stats        = $this->applicationService->getStats();

        return view('admin::applications.index', compact('applications', 'stats', 'filters'));
    }

    /**
     * Show / edit a single application
     */
    public function edit(Application $application)
    {
        $application = $this->applicationService->getApplicationById($application->id);

        return view('admin::applications.edit', compact('application'));
    }

    /**
     * Update status / send reply
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status'      => 'nullable|in:unread,read,replied',
            'admin_reply' => 'nullable|string',
        ]);

        if ($request->filled('admin_reply')) {
            $this->applicationService->replyToApplication($application->id, $request->admin_reply);
        } elseif ($request->filled('status')) {
            $this->applicationService->updateApplication($application->id, [
                'status' => $request->status,
            ]);
        }

        return redirect()
            ->route('admin.applications.edit', $application->id)
            ->with('success', 'Application updated successfully.');
    }

    /**
     * Delete an application
     */
    public function destroy(Application $application)
    {
        $this->applicationService->deleteApplication($application->id);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Bulk actions (delete / mark as read)
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = json_decode($request->input('ids'), true);

        if ($action === 'delete') {
            $this->applicationService->bulkDelete($ids);
            return redirect()->route('admin.applications.index')
                ->with('success', 'Applications deleted successfully!');
        }

        if ($action === 'mark_read') {
            $this->applicationService->bulkMarkRead($ids);
            return redirect()->route('admin.applications.index')
                ->with('success', 'Applications marked as read!');
        }

        return back()->with('error', 'Invalid action!');
    }
}
