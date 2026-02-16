<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;

class ProgrammeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // le formateur connecté

        // Récupérer les programmes qui lui sont assignés
        $programmes = Programme::with(['subject', 'site', 'filieres'])
            ->where('formateur_id', $user->id)
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->paginate(10);

        return view('formateur.programmes.index', compact('programmes'));
    }
}
