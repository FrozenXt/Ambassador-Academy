<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Web\Http\Requests\ContactRequest;
use Modules\Common\Services\ContactService;
use Modules\Common\Entities\SiteSetting;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $settings = SiteSetting::whereIn('key', [
            'site_name',
            'site_email',
            'site_phone',
            'site_telephone',
            'site_address',
            'recaptcha_site_key',
            'google_map_embed',
            'opening_hours_weekday',
            'opening_hours_weekend',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'whatsapp_url',
            'viber_url',
            'tiktok_url',
        ])
            ->get()
            ->keyBy('key');

        $contactAlbum = \Modules\Common\Entities\Album::where('code', 'contact')->first();
        $contactImage = $contactAlbum ? $contactAlbum->gallery->first() : null;

        $socialPlatforms = [
            'facebook_url'  => ['icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
            'twitter_url'   => ['icon' => 'fa-brands fa-twitter', 'label' => 'Twitter'],
            'instagram_url' => ['icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
            'youtube_url'   => ['icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
            'linkedin_url'  => ['icon' => 'fa-brands fa-linkedin-in', 'label' => 'LinkedIn'],
            'whatsapp_url'  => ['icon' => 'fa-brands fa-whatsapp', 'label' => 'WhatsApp'],
            'viber_url'     => ['icon' => 'fa-brands fa-viber', 'label' => 'Viber'],
            'tiktok_url'    => ['icon' => 'fa-brands fa-tiktok', 'label' => 'TikTok'],
        ];

        $socialLinks = collect($socialPlatforms)
            ->filter(fn($meta, $key) => !empty($settings[$key]->value ?? null))
            ->map(fn($meta, $key) => [
                'url'   => $settings[$key]->value,
                'icon'  => $meta['icon'],
                'label' => $meta['label'],
            ])
            ->values();

        return view('web::web.contact', compact('settings', 'contactAlbum', 'contactImage', 'socialLinks'));
    }

    public function process(ContactRequest $request)
    {
        $siteKey = SiteSetting::where('key', 'recaptcha_site_key')->value('value');
        $secret  = SiteSetting::where('key', 'recaptcha_secret_key')->value('value');

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
            $data['type'] = 'contact';
            $data['status'] = 'unread';

            $this->contactService->createContact($data);
        } catch (\Exception $e) {
            Log::error('Saving contact to database failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Sorry, something went wrong saving your message.');
        }

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
