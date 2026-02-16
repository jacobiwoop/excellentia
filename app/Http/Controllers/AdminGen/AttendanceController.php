<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Filiere;
use App\Models\Student;
use App\Models\Site;         // Ajouté
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Afficher la liste des présences
    public function index(Request $request)
    {
        $attendances = Attendance::with(['student.filiere', 'formateur'])
            ->latest()
            ->paginate(20);

        $stats = [
            'present' => Attendance::where('status', 1)->count(),
            'justified' => Attendance::where('status', 2)->count(),
            'absent' => Attendance::where('status', 0)->count(),
            'total' => Attendance::count()
        ];

        return view('admingen.attendance.index', [
            'attendances' => $attendances,
            'stats' => $stats,
            'filieres' => Filiere::all(),
            'formateurs' => User::where('role', 'formateur')->get(),
            'sites' => Site::all()   // Ajouter les sites ici si besoin dans la vue
        ]);
    }

    // Formulaire de sélection d'un site, filière et date
    public function create()
    {
        return view('admingen.attendance.create', [
            'filieres' => Filiere::all(),
            'sites' => Site::all(),    // Envoi des sites
        ]);
    }

    // Affichage des étudiants à marquer présents (filtrage site + filière)
    public function showStudents(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'filiere_id' => 'required|exists:filieres,id',
            'date' => 'required|date',
        ]);

        // Récupérer les étudiants de la filière ET du site sélectionnés
        $students = Student::where('filiere_id', $request->filiere_id)
            ->where('site_id', $request->site_id)  // Ajout filtrage site
            ->get();

        return view('admingen.attendance.mark', [
            'students' => $students,
            'date' => $request->date,
            'filiere_id' => $request->filiere_id,
            'site_id' => $request->site_id,  // Optionnel, si besoin dans la vue
        ]);
    }

    // Enregistrement des présences
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'statuses' => 'required|array',
        ]);

        foreach ($request->statuses as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                [
                    'status' => $status,
                    'formateur_id' => auth()->id() // peut être null si admin général
                ]
            );
        }

        return redirect()->route('admingen.attendance.overview')
            ->with('success', 'Présences enregistrées avec succès.');
    }
}
