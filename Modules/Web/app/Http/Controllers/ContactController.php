<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Services\EmailSettingService;
use Modules\Common\Entities\SiteSetting;
use Modules\Common\Entities\Contact;

class ContactController extends Controller
{
    protected $emailSettingService;

    public function __construct(EmailSettingService $emailSettingService)
    {
        $this->emailSettingService = $emailSettingService;
    }

    public function index()
    {
        $settings = SiteSetting::whereIn('key', ['recaptcha_site_key'])
            ->get()
            ->keyBy('key');

        return view('web::web.contact', compact('settings'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email',
            'message'              => 'required|string|min:10',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA check.',
        ]);

        // -------------------------
        // CAPTCHA VALIDATION
        // -------------------------
        $secret = SiteSetting::where('key', 'recaptcha_secret_key')->value('value');

        if ($secret) {
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

                return response()->json([
                    'success' => false,
                    'message' => 'Could not verify reCAPTCHA right now. Please try again.',
                ], 500);
            }

            if (!($response->json()['success'] ?? false)) {
                Log::warning('reCAPTCHA verification rejected.', [
                    'response' => $response->json(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Captcha verification failed. Please try again.',
                ], 422);
            }
        }

        // -------------------------
        // SAVE TO DATABASE
        // -------------------------
        try {
            Contact::create([
                'type'    => 'contact',
                'name'    => $request->name,
                'email'   => $request->email,
                'message' => $request->message,
                'status'  => 'unread',
            ]);
        } catch (\Exception $e) {
            Log::error('Saving contact to database failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, something went wrong saving your message.',
            ], 500);
        }

        // -------------------------
        // MAIL CONFIG
        // -------------------------
        $activeSetting = $this->emailSettingService->getActive();

        if (!$activeSetting) {
            Log::error('processContact: no active EmailSetting found.');

            return response()->json([
                'success' => false,
                'message' => 'Mail is not configured yet. Please contact the site owner.',
            ], 500);
        }

        $this->emailSettingService->applyToConfig($activeSetting);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'userMessage' => $request->message,
            'sentAt'  => now()->format('d M Y, h:i A'),
        ];

        $adminEmail = $activeSetting->admin_mail;

        // -------------------------
        // SEND MAIL
        // -------------------------
        try {
            Mail::send('common::emails.admin-notification', $data, function ($mail) use ($adminEmail, $activeSetting, $request) {

                $parseEmails = function ($value) {
                    return collect(explode(',', (string) $value))
                        ->map(fn($email) => trim($email))
                        ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                        ->values()
                        ->all();
                };

                $adminEmails = $parseEmails($adminEmail);

                if (empty($adminEmails)) {
                    throw new \Exception('No valid admin email addresses configured.');
                }

                $mail->to($adminEmails)
                    ->from($activeSetting->from_address, $activeSetting->from_name)
                    ->replyTo($request->email, $request->name)
                    ->subject('New Contact Form Message from ' . $request->name);

                if ($activeSetting->cc_mail) {
                    $ccEmails = $parseEmails($activeSetting->cc_mail);
                    if (!empty($ccEmails)) {
                        $mail->cc($ccEmails);
                    }
                }

                if ($activeSetting->bcc_mail) {
                    $bccEmails = $parseEmails($activeSetting->bcc_mail);
                    if (!empty($bccEmails)) {
                        $mail->bcc($bccEmails);
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Contact mail send failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Note: the contact was already saved to the DB above, so we don't
            // fail the whole request just because the email didn't send.
            return response()->json([
                'success' => true,
                'message' => 'Your message has been saved. (Email notification failed, but your message was received.)',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
        ]);
    }
}
