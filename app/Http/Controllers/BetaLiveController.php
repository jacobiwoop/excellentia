<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetaLiveController extends Controller
{
    public function create()
    {
        // On récupère les promotions du formateur connecté (ou toutes si admin)
        // Simplification pour la beta : on prend toutes les promotions
        $promotions = \App\Models\Promotion::all();
        return view('beta.create', compact('promotions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'promotion_id' => 'required|exists:promotions,id',
            'date_debut' => 'required|date',
        ]);

        $live = \App\Models\CourseLive::create([
            'titre' => $request->titre,
            'meeting_id' => 'excellentia-live-' . uniqid(), // ID unique pour Jitsi
            'date_debut' => $request->date_debut,
            'formateur_id' => Auth::id(),
            'promotion_id' => $request->promotion_id,
            'is_active' => true,
        ]);

        return redirect()->route('beta.host', ['live' => $live->id]);
    }

    public function host(\App\Models\CourseLive $live)
    {
        // Vérification élémentaire de sécurité (le créateur est l'hôte)
        if ($live->formateur_id !== Auth::id()) {
            abort(403, "Seul le créateur peut lancer ce live.");
        }

        return view('beta.room', [
            'live' => $live,
            'isHost' => true,
            'user' => Auth::user()
        ]);
    }

    public function join(\App\Models\CourseLive $live)
    {
        // Dans la vrai vie, on vérifierait si le user appartient à la promo
        // Pour la beta, on laisse ouvert à tout user connecté

        return view('beta.room', [
            'live' => $live,
            'isHost' => false,
            'user' => Auth::user()
        ]);
    }
}
