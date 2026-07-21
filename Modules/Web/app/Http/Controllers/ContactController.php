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
        $contactAlbum = \Modules\Common\Entities\Album::where('code', 'contact')->first();
        $contactImage = $contactAlbum ? $contactAlbum->gallery->first() : null;

        return view('web::web.contact', compact('settings', 'contactAlbum', 'contactImage'));
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

            return back()
                ->withInput()
                ->with('error', 'Sorry, something went wrong saving your message.');
        }

        $activeSetting = $this->emailSettingService->getActive();

        if (!$activeSetting) {
            Log::error('processContact: no active EmailSetting found.');

            // Message was saved, so still show success — just log the config issue
            return back()->with('success', 'Your message has been sent successfully.');
        }

        $this->emailSettingService->applyToConfig($activeSetting);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'userMessage' => $request->message,
            'sentAt'      => now()->format('d M Y, h:i A'),
        ];

        $adminEmail = $activeSetting->admin_mail;

        $parseEmails = function ($value) {
            return collect(explode(',', (string) $value))
                ->map(fn($email) => trim($email))
                ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->values()
                ->all();
        };

        // ── Admin notification email ──
        try {
            Mail::send('common::emails.admin-notification', $data, function ($mail) use ($adminEmail, $activeSetting, $request, $parseEmails) {

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

            // Message was already saved successfully — still tell the user it worked
            return back()->with('success', 'Your message has been sent successfully.');
        }

        // ── Customer confirmation email ──
        try {
            Mail::send('common::emails.customer-confirmation', $data, function ($mail) use ($activeSetting, $request) {
                $mail->to($request->email, $request->name)
                    ->from($activeSetting->from_address, $activeSetting->from_name)
                    ->subject("We've Received Your Message — Papa's bar and grill.");
            });
        } catch (\Exception $e) {
            Log::error('Customer confirmation mail send failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
