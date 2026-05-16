<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'Admin only');
        }

        $roleRaw = (string) auth()->user()->role;
        $role = strtolower(trim($roleRaw));
        $role = str_replace([' ', '-'], '_', $role);

        if ($role === 'superadmin') {
            $role = 'super_admin';
        }

        $allowed = ['admin', 'super_admin', 'staff', 'viewer'];

        if (!in_array($role, $allowed, true)) {
            abort(403, 'Admin only');
        }

        return $next($request);
    }
}
