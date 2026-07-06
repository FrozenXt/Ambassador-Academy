<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Common\Entities\User;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $adminId = session('admin_id');

        if (!$adminId) {
            abort(403, 'Unauthorized');
        }

        $user = User::find($adminId);

        if (!$user) {
            abort(403, 'User not found');
        }

        // Check role
        if (!in_array($user->getRoleNames()->first(), $roles)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
