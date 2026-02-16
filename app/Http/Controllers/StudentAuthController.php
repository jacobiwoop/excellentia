<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.etudiant_login');
    }

   // StudentAuthController.php
   public function login(Request $request)
   {
       $request->validate(['matricule' => 'required|string']);
       
       $student = Student::where('matricule', $request->matricule)->first();
   
       if (!$student) {
           return back()->withErrors(['matricule' => 'Matricule invalide']);
       }
   
       auth()->guard('student')->login($student);
       return redirect()->route('etudiant.dashboard');
   }

    // StudentAuthController.php
    public function logout(Request $request)
    {
        // Déconnexion spécifique au guard student
        Auth::guard('student')->logout();
        
        // Invalidation de session
        $request->session()->invalidate();
        
        // Régénération du token CSRF
        $request->session()->regenerateToken();
    
        // Redirection vers le login étudiant
        return redirect()->route('etudiant.login.form');
    }
}