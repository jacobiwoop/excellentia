<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with([
            'student:id,nom_prenom,site_id,filiere_id',
            'student.site:id,nom',
            'student.filiere:id,nom',
            'assignation.subject:id,nom',
        ]);
 
        // Filtres existants
        if ($request->filled('filiere_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('filiere_id', $request->filiere_id);
            });
        }

        if ($request->filled('site_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('assignation.subject', function ($q) use ($request) {
                $q->where('id', $request->subject_id);
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // ✅ Nouveau filtre par trimestre
        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $grades = $query->latest()->get();

        $filieres = \App\Models\Filiere::all();
        $sites = \App\Models\Site::all();
        $subjects = \App\Models\Subject::all();

        return view('admingen.grades.index', compact('grades', 'filieres', 'sites', 'subjects'));
    }
}
