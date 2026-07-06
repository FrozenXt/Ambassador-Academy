<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Get the authenticated admin user
        $adminId = session('admin_id');

        if (!$adminId) {
            abort(403, 'Unauthorized.');
        }

        $user = \Modules\Common\Entities\User::find($adminId);

        if (!$user || !$user->hasPermissionTo($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
