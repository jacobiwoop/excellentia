<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsFormateur
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'formateur') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Accès refusé.');
    }
}
