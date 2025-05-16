<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna terautentikasi dan apakah mereka adalah admin atau super admin
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && !auth()->user()->isOperator() && !auth()->user()->isStaf() && !auth()->user()->isTlRonUpkd() && !auth()->user()->isTlRon() && !auth()->user()->isStasiun())) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}