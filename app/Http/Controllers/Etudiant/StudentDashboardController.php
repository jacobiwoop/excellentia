<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    
    // Dans StudentDashboardController.php
    public function dashboard()
    {
        $student = auth()->guard('student')->user();

        // ⚠️ Ajoute ceci pour récupérer les notes
        $grades = Grade::where('student_id', $student->id)->get();

        return view('etudiant.dashboard', compact('student', 'grades'));
    }

    public function bulletin(Request $request, $term = 1)
{
    $student = auth()->user();
    $currentTerm = (int)$term;

    // Récupère les notes du trimestre
    $grades = Grade::with(['assignation.subject'])
        ->where('student_id', $student->id)
        ->where('term', $currentTerm)
        ->get();

    // Calcule la moyenne générale
    $moyenneGenerale = $grades->avg('moy_finale');

  

    return view('etudiant.bulletin', compact(
        'student',
        'grades',
        'currentTerm',
       
    ));
}

private function getMention($moyenne)
{
    if ($moyenne >= 16) return 'Très Bien';
    if ($moyenne >= 14) return 'Bien';
    if ($moyenne >= 12) return 'Assez Bien';
    if ($moyenne >= 10) return 'Passable';
    return 'Échec';
}

private function getMentionColor($moyenne)
{
    if ($moyenne >= 16) return 'text-blue-600';
    if ($moyenne >= 14) return 'text-green-600';
    if ($moyenne >= 12) return 'text-yellow-600';
    if ($moyenne >= 10) return 'text-orange-600';
    return 'text-red-600';
}

}