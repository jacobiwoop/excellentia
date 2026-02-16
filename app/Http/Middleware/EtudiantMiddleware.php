<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class EtudiantMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->guard('student')->check()) {
            return redirect()->route('etudiant.login.form');
        }
        
        return $next($request);
    }
}
