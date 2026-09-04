<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Web\Http\Requests\ApplyRequest;
use Modules\Common\Services\ApplicationService;
use Modules\Common\Entities\SiteSetting;

class ApplyController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index()
    {
        $settings = SiteSetting::whereIn('key', ['recaptcha_site_key'])
            ->get()
            ->keyBy('key');

        return view('web::web.apply', compact('settings'));
    }

    public function store(ApplyRequest $request)
    {
        $siteKey = SiteSetting::where('key', 'recaptcha_site_key')->value('value');
        $secret  = SiteSetting::where('key', 'recaptcha_secret_key')->value('value');

        // Only enforce reCAPTCHA if it's actually configured
        if ($siteKey && $secret) {
            if (!$request->filled('g-recaptcha-response')) {
                return back()
                    ->withInput()
                    ->with('error', 'Please complete the reCAPTCHA check.');
            }

            try {
                $response = Http::asForm()->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'secret'   => $secret,
                        'response' => $request->input('g-recaptcha-response'),
                        'remoteip' => $request->ip(),
                    ]
                );
            } catch (\Exception $e) {
                Log::error('reCAPTCHA verification request failed: ' . $e->getMessage());

                return back()
                    ->withInput()
                    ->with('error', 'Could not verify reCAPTCHA right now. Please try again.');
            }

            if (!($response->json()['success'] ?? false)) {
                Log::warning('reCAPTCHA verification rejected.', [
                    'response' => $response->json(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Captcha verification failed. Please try again.');
            }
        }

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
