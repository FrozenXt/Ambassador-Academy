<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Web\Http\Requests\ApplyRequest;
use Modules\Common\Services\ApplicationService;

class ApplyController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index()
    {
        return view('web::web.apply');
    }

    public function store(ApplyRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'unread';

            $this->applicationService->createApplication($data);
        } catch (\Exception $e) {
            Log::error('Saving application to database failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Sorry, something went wrong submitting your application.');
        }

        return back()->with('success', 'Your application has been submitted successfully.');
    }
}
