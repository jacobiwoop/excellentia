<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Programme;

class ProgrammeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $programmes = Programme::whereHas('filieres', function($query) use ($user) {
                $query->where('filieres.id', $user->filiere_id);
            })
            ->where('site_id', $user->site_id)
            ->with(['subject', 'formateur', 'filieres'])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->paginate(10);

        return view('etudiant.programmes.index', [
            'programmes' => $programmes,
            'filiere' => $user->filiere,
        ]);
    }
}
