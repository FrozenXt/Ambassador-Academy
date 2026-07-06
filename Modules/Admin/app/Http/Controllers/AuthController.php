<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Services\AuthService;
use Modules\Admin\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect('/admin/dashboard');
        }
        return view('admin::auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $result = $this->authService->attemptLogin(
            $request->email,
            $request->password
        );

        if ($result['success']) {
            session([
                'admin_logged_in' => true,
                'admin_id'        => $result['admin']->id,
                'admin_name'      => $result['admin']->name,
                'admin_email'     => $result['admin']->email,
            ]);

            // ADD THIS — logs the user into Laravel's Auth system
            Auth::login($result['admin']);

            return redirect('/admin/dashboard');
        }

        return back()
            ->withErrors(['email' => $result['message']])
            ->withInput();
    }
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/admin/login');
    }
}
