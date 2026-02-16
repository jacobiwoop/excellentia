<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin_site') {
            return $next($request);
        }

        abort(403, 'Accès refusé.');
    }
}