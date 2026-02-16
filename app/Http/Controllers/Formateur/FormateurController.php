<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Assignation;

class FormateurController extends Controller
{
    public function dashboard()
{
    $formateurId = auth()->id();

    $assignations = Assignation::where('formateur_id', $formateurId)->get();

    $filiereIds = $assignations->pluck('filiere_id')->unique();
    $siteIds = $assignations->pluck('site_id')->unique();

    $nombreEtudiants = Student::where('statut', 'en_cours')
        ->whereIn('filiere_id', $filiereIds)
        ->whereIn('site_id', $siteIds)
        ->distinct()
        ->count('id');

    $nombreMatieres = Assignation::where('formateur_id', $formateurId)
                    ->distinct()
                    ->count('subject_id');

    return view('formateur.dashboard', compact('nombreEtudiants', 'nombreMatieres'));
}

    public function students()
    {
        // Récupérer les étudiants associés au formateur connecté
        // (vous devrez adapter cette partie selon votre relation formateur-étudiants)
        $students = Student::with(['site', 'filiere', 'promotion'])
            ->orderBy('nom_prenom')
            ->paginate(10);

        return view('formateurs.students', compact('students'));
    }
}
