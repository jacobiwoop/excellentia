<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // 👈 Important pour l'annotation

class ProfilController extends Controller
{
    /**
     * Affiche le formulaire d'édition du profil
     */
    public function edit()
    {
        return view('superadmin.profil.edit');
    }

    /**
     * Met à jour le profil du super admin
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6|confirmed',
        ]);

        /** @var User $user */ // 👈 Corrige l'erreur Intelephense ici
        $user = Auth::user();

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // ✅ Plus d'erreur ici

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
