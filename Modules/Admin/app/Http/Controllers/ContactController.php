<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\ContactService;
use Modules\Admin\Http\Requests\ContactReplyRequest;
use Modules\Common\Entities\Contact;
use App\Exports\ContactsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * List contacts
     */
    /**
     * List contacts
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $query = Contact::query();

        // Booking messages
        if ($request->source === 'booking') {
            $query->whereNotNull('model');
        }
        // Normal contact messages
        else {
            $query->whereNull('model');
        }

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $contacts = $query->latest()->paginate(15);

        $stats = [
            'total' => (clone $query)->count(),

            'unread' => (clone $query)
                ->where('status', 'unread')
                ->count(),

            'read' => (clone $query)
                ->where('status', 'read')
                ->count(),

            'replied' => (clone $query)
                ->whereNotNull('admin_reply')
                ->count(),
        ];

        return view('admin::contacts.index', compact(
            'contacts',
            'stats',
            'filters'
        ));
    }

    /**
     * Show single contact
     */
    public function show(Contact $contact)
    {
        return view('admin::contacts.show', compact('contact'));
    }

    /**
     * Reply to contact (using FormRequest)
     */
    public function reply(ContactReplyRequest $request, Contact $contact)
    {
        $this->contactService->replyToContact(
            $contact->id,
            $request->admin_reply
        );

        return redirect()
            ->route('admin.contacts.show', $contact->id)
            ->with('success', 'Reply sent successfully.');
    }

    /**
     * Delete contact
     */
    public function destroy($id)
    {
        $this->contactService->deleteContact($id);

        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully!');
    }

    /**
     * Bulk actions (delete / mark as read)
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = json_decode($request->input('ids'), true);

        if ($action === 'delete') {
            $this->contactService->bulkDelete($ids);
            return redirect()->route('admin.contacts.index')
                ->with('success', 'Messages deleted successfully!');
        }

        if ($action === 'mark_read') {
            $this->contactService->bulkMarkRead($ids);
            return redirect()->route('admin.contacts.index')
                ->with('success', 'Messages marked as read!');
        }

        return back()->with('error', 'Invalid action!');
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $filters  = $request->only(['search', 'status']);
        $filename = 'contacts-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new ContactsExport($filters), $filename);
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $query = Contact::latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $filters['search'] . '%'])
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        $contacts = $query->get();
        $stats    = $this->contactService->getStats();
        $status   = $filters['status'] ?? null;

        $filename = 'contacts-' . now()->format('Y-m-d-His') . '.pdf';

        $pdf = Pdf::loadView('exports.contacts-pdf', compact('contacts', 'stats', 'status'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download($filename);
    }
}
