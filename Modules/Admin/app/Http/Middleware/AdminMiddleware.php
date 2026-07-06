<?php

namespace Modules\Admin\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_logged_in')) {
            return redirect('/admin/login')
                ->withErrors(['email' => 'Please login to access admin panel.']);
        }

        // Re-login into Auth if session exists but Auth is lost
        // (happens after session restore, queue workers, etc.)
        if (!Auth::check()) {
            $user = \Modules\Common\Entities\User::find(session('admin_id'));
            if ($user) {
                Auth::login($user);
            } else {
                session()->flush();
                return redirect('/admin/login');
            }
        }

        return $next($request);
    }
}
