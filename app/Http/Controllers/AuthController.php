<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Crée la vue auth/login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            $user = auth()->user();

            if ($user->role === 'super_admin') {
                return redirect()->intended('/superadmin/dashboard');
            }

            if ($user->role === 'admin_gen') {
                // Ici tu peux choisir la route que tu veux pour admin_gen
                return redirect()->intended('/admingen/dashboard');
            }
    

            if ($user->role === 'admin_site') {
                return redirect()->intended('/admin/accounting_dashboard');
            }

            if ($user->role === 'formateur') {
                return redirect()->intended('/formateur/dashboard');
            }

            // Si le rôle est inconnu
            Auth::logout();
            return back()->withErrors([
                'email' => 'Rôle utilisateur non autorisé.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Déconnexion spécifique au guard web
        Auth::guard('web')->logout();
    
        // Invalidation de session
        $request->session()->invalidate();
        
        // Régénération du token CSRF
        $request->session()->regenerateToken();
    
        // Redirection vers le login admin/formateur
        return redirect()->route('login');
    }
}
