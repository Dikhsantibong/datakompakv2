<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna terautentikasi dan apakah mereka adalah admin atau super admin
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
