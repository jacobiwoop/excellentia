<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Assignation;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
   public function index()
{
    $formateur = Auth::user();

    // ✅ Récupérer UNIQUEMENT les assignations avec des étudiants actifs
    $assignations = Assignation::where('formateur_id', $formateur->id)
        ->with(['subject', 'filiere', 'site'])
        ->whereHas('filiere.students', function($query) {
            $query->where('statut', 'en_cours');
        })
        ->get()
        ->filter(function($assignation) {
            // ✅ Double vérification : étudiant dans la filière ET le site
            return Student::where('filiere_id', $assignation->filiere_id)
                ->where('site_id', $assignation->site_id)
                ->where('statut', 'en_cours')
                ->exists();
        });

    if ($assignations->isEmpty()) {
        return view('formateur.grades.index', [
            'matiereGroups' => collect([]),
            'message' => 'Aucun étudiant actif dans vos classes assignées.'
        ]);
    }

    // Grouper par matière
    $matiereGroups = $assignations->groupBy('subject_id')->map(function($group) {
        $subject = $group->first()->subject;
        
        // ✅ Compter les VRAIS étudiants pour cette matière
        $totalStudents = $group->sum(function($assignation) {
            return Student::where('filiere_id', $assignation->filiere_id)
                ->where('site_id', $assignation->site_id)
                ->where('statut', 'en_cours')
                ->count();
        });
        
        $combinations = $group->map(function($a) {
            $count = Student::where('filiere_id', $a->filiere_id)
                ->where('site_id', $a->site_id)
                ->where('statut', 'en_cours')
                ->count();
            return $a->filiere->nom . ' - ' . $a->site->nom . ' (' . $count . ')';
        });
        
        return [
            'subject' => $subject,
            'assignations' => $group,
            'count' => $combinations->count(),
            'total_students' => $totalStudents, // ✅ AJOUT
            'combinations' => $combinations
        ];
    });

    return view('formateur.grades.index', [
        'matiereGroups' => $matiereGroups
    ]);
}

public function show($assignation_id)
{
    $assignation = Assignation::with(['subject', 'filiere', 'site'])
        ->where('id', $assignation_id)
        ->where('formateur_id', Auth::id())
        ->firstOrFail();

    // ✅ Récupérer TOUTES les assignations de cette matière (pour le sélecteur)
    $relatedAssignations = Assignation::where('formateur_id', Auth::id())
        ->where('subject_id', $assignation->subject_id)
        ->with(['filiere', 'site'])
        ->get();

    $availableTerms = $assignation->trimestres ?? [1, 2, 3];

    $students = Student::where('filiere_id', $assignation->filiere_id)
        ->where('site_id', $assignation->site_id)
        ->where('statut', 'en_cours')
        ->with(['grades' => function ($query) use ($assignation_id, $availableTerms) {
            $query->where('assignation_id', $assignation_id)
                ->whereIn('term', $availableTerms)
                ->orderBy('term');
        }])
        ->get();

    return view('formateur.grades.show', [
        'assignation' => $assignation,
        'relatedAssignations' => $relatedAssignations,
        'students' => $students,
        'availableTerms' => $availableTerms
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignation_id' => 'required|exists:assignations,id',
            'grades' => 'required|array',
            'grades.*.*.interro1' => 'nullable|numeric|min:0|max:20',
            'grades.*.*.interro2' => 'nullable|numeric|min:0|max:20',
            'grades.*.*.interro3' => 'nullable|numeric|min:0|max:20',
            'grades.*.*.devoir' => 'nullable|numeric|min:0|max:20',
        ]);

        // ✅ AJOUTÉ : Vérifier les trimestres autorisés
        $assignation = Assignation::findOrFail($validated['assignation_id']);
        $allowedTerms = $assignation->trimestres ?? [1, 2, 3];

        foreach ($validated['grades'] as $studentId => $terms) {
            foreach ($terms as $term => $grades) {
                // ✅ AJOUTÉ : Ignorer les trimestres non autorisés
                if (!in_array($term, $allowedTerms)) {
                    continue;
                }

                // Calcul des moyennes
                $interros = array_filter([
                    $grades['interro1'] ?? null,
                    $grades['interro2'] ?? null,
                    $grades['interro3'] ?? null
                ], fn($n) => is_numeric($n));

                $moy_interro = count($interros) ? round(array_sum($interros) / count($interros), 2) : null;

                $devoir = $grades['devoir'] ?? null;
                $moy_finale = is_numeric($moy_interro) && is_numeric($devoir)
                    ? round(($moy_interro + $devoir) / 2, 2)
                    : null;

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'assignation_id' => $validated['assignation_id'],
                        'term' => $term
                    ],
                    array_merge($grades, [
                        'moy_interro' => $moy_interro,
                        'moy_finale' => $moy_finale
                    ])
                );
            }
        }

        return back()->with('success', 'Toutes les notes ont été enregistrées!');
    }

    public function studentGrades(Student $student)
    {
        $grades = $student->grades()
            ->with('assignation.subject')
            ->get()
            ->groupBy('term');

        return view('student.grades', [
            'gradesByTrimestre' => $grades,
            'student' => $student
        ]);
    }

    public function generateBulletin($assignationId, $term)
    {
        $assignation = Assignation::with([
            'filiere.students.grades' => function ($q) use ($assignationId, $term) {
                $q->where('assignation_id', $assignationId)
                    ->where('term', $term);
            },
            'subject',
            'filiere',
            'site'
        ])->findOrFail($assignationId);

        // ✅ AJOUTÉ : Vérifier que le trimestre est valide pour cette assignation
        $allowedTerms = $assignation->trimestres ?? [1, 2, 3];
        
        if (!in_array($term, $allowedTerms)) {
            return redirect()->back()->with('error', 'Ce trimestre n\'est pas disponible pour cette matière.');
        }

        $students = $assignation->filiere->students->map(function ($student) use ($assignationId) {
            $grade = $student->grades->firstWhere('assignation_id', $assignationId);

            return [
                'student' => $student,
                'grade' => $grade,
                'moyenne' => $grade?->moy_finale
            ];
        });

        return view('formateur.bulletin.show', compact('assignation', 'students', 'term'));
    }
}