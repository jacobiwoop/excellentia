<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetaLiveController extends Controller
{
    public function index()
    {
        $lives = \App\Models\CourseLive::where('is_active', true)->orderBy('created_at', 'desc')->get();

        // Démarcation simple : Si connecté via 'web' (User), on affiche le bouton "Créer"
        // Si 'student' (Student), on affiche juste la liste.
        $canCreate = Auth::guard('web')->check() && (Auth::user()->hasRole('formateur') || Auth::user()->hasRole('admin'));

        return view('beta.index', compact('lives', 'canCreate'));
    }

    public function create()
    {
        // Sécurité : Seuls Formateurs/Admins peuvent créer
        if (!Auth::guard('web')->check() || (!Auth::user()->hasRole('formateur') && !Auth::user()->hasRole('admin'))) {
            abort(403, "Accès réservé aux formateurs.");
        }

        $promotions = \App\Models\Promotion::all();
        return view('beta.create', compact('promotions'));
    }

    public function store(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'promotion_id' => 'required|exists:promotions,id',
            'date_debut' => 'required|date',
        ]);

        $live = \App\Models\CourseLive::create([
            'titre' => $request->titre,
            'meeting_id' => 'excellentia-live-' . uniqid(),
            'date_debut' => $request->date_debut,
            'formateur_id' => Auth::id(),
            'promotion_id' => $request->promotion_id,
            'is_active' => true,
        ]);

        return redirect()->route('beta.host', ['live' => $live->id]);
    }

    public function host(\App\Models\CourseLive $live)
    {
        // Seul le créateur (User) peut hoster
        if (!Auth::guard('web')->check() || $live->formateur_id !== Auth::id()) {
            abort(403, "Seul le créateur peut lancer ce live.");
        }

        $user = Auth::user();
        $displayName = $user->name; // User standard a 'name'

        return view('beta.room', [
            'live' => $live,
            'isHost' => true,
            'user' => $user,
            'displayName' => $displayName,
            'roomName' => $live->meeting_id
        ]);
    }

    public function join(\App\Models\CourseLive $live)
    {
        // Récupération de l'utilisateur (Soit Web/Formateur, Soit Student)
        $user = null;
        $displayName = 'Invité';

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $displayName = $user->name;
        } elseif (Auth::guard('student')->check()) {
            $user = Auth::guard('student')->user();
            $displayName = $user->nom_prenom ?? 'Étudiant';
        } else {
            return redirect()->route('login'); // Ou student.login
        }

        return view('beta.room', [
            'live' => $live,
            'isHost' => false,
            'user' => $user,
            'displayName' => $displayName,
            'roomName' => $live->meeting_id
        ]);
    }
    public function stop(\App\Models\CourseLive $live)
    {
        // Seul le créateur peut arrêter le live
        if (!Auth::guard('web')->check() || $live->formateur_id !== Auth::id()) {
            abort(403, "Action non autorisée.");
        }

        $live->update(['is_active' => false]);

        return redirect()->route('beta.index')->with('success', 'Le live a été terminé.');
    }
}
