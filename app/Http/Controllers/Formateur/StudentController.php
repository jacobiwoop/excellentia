<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Site;
use App\Models\Assignation;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $formateur = Auth::user();

        // ✅ Récupère toutes les assignations du formateur avec relations
        $assignations = Assignation::where('formateur_id', $formateur->id)
            ->with(['filiere', 'site'])
            ->get();

        // ✅ Collection pour stocker les filières avec leurs étudiants
        $filieres = collect();

        foreach ($assignations as $assignation) {
            $filiereId = $assignation->filiere_id;
            $siteId = $assignation->site_id;

            // ✅ Vérifie si la filière est déjà dans la collection
            $filiere = $filieres->firstWhere('id', $filiereId);

            if (!$filiere) {
                // ✅ Ajoute la filière avec une collection vide d'étudiants et de sites
                $filiere = $assignation->filiere;
                $filiere->students = collect();
                $filiere->sites = collect();
                $filieres->push($filiere);
            }

            // ✅ Ajoute le site à la liste des sites (sans doublon)
            if (!$filiere->sites->contains('id', $siteId)) {
                $filiere->sites->push($assignation->site);
            }

            // ✅ Récupère les étudiants actifs pour cette filière + site
            $students = Student::where('filiere_id', $filiereId)
                ->where('site_id', $siteId)
                ->where('statut', 'en_cours')
                ->with(['promotion', 'site'])
                ->orderBy('nom_prenom')
                ->get();

            // ✅ Fusionne les étudiants sans doublons (basé sur id)
            $filiere->students = $filiere->students->merge($students)->unique('id')->values();
        }

        // ✅ Filtre pour garder uniquement les filières avec des étudiants
        $filieres = $filieres->filter(function($filiere) {
            return $filiere->students->count() > 0;
        })->values();

        return view('formateur.students.index', compact('filieres'));
    }
}