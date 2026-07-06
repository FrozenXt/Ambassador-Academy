<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Entities\Product;
use Modules\Common\Services\EmailSettingService;

class PageController extends Controller
{
    protected $emailSettingService;

    public function __construct(EmailSettingService $emailSettingService)
    {
        $this->emailSettingService = $emailSettingService;
    }

    public function home()
    {
        $mainCourses = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Main Course'))
            ->orderBy('sort_order')
            ->get();

        $appetizers = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Appetizers'))
            ->orderBy('sort_order')
            ->get();

        $desserts = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Desserts'))
            ->orderBy('sort_order')
            ->get();

        return view('web::web.home', compact('mainCourses', 'appetizers', 'desserts'));
    }

    public function about()
    {
        return view('web::web.about');
    }

    public function menu()
    {
        $mainCourses = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Main Course'))
            ->orderBy('sort_order')
            ->get();

        $appetizers = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Appetizers'))
            ->orderBy('sort_order')
            ->get();

        $desserts = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Desserts'))
            ->orderBy('sort_order')
            ->get();

        return view('web::web.menu', compact('mainCourses', 'appetizers', 'desserts'));
    }

    public function gallery()
    {
        return view('web::web.gallery');
    }

    public function services()
    {
        return view('web::web.services');
    }

    public function contact()
    {
        return view('web::web.contact');
    }

    public function processContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        $activeSetting = $this->emailSettingService->getActive();

        if (!$activeSetting) {
            return response()->json([
                'success' => false,
                'message' => 'Mail is not configured yet. Please contact the site owner.',
            ], 500);
        }

        // Push the admin's active SMTP settings into runtime config
        $this->emailSettingService->applyToConfig($activeSetting);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->message,
            'sentAt'  => now()->format('d M Y, h:i A'),
        ];

        $adminEmail = config('mail.admin_email');

        try {
            Mail::send('web::emails.contact', $data, function ($mail) use ($adminEmail, $activeSetting, $request) {
                $mail->to($adminEmail)
                    ->from($activeSetting->from_address, $activeSetting->from_name)
                    ->replyTo($request->email, $request->name)
                    ->subject('New Contact Form Message from ' . $request->name);

                if ($activeSetting->cc_mail) {
                    $mail->cc($activeSetting->cc_mail);
                }
                if ($activeSetting->bcc_mail) {
                    $mail->bcc($activeSetting->bcc_mail);
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, something went wrong sending your message.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
        ]);
    }
}
