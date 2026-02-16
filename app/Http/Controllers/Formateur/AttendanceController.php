<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $formateur = Auth::user();
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        
        $filieres = Filiere::whereHas('assignations', function($query) use ($formateur) {
                $query->where('formateur_id', $formateur->id);
            })
            ->with(['students' => function($query) use ($selectedDate) {
                $query->orderBy('nom_prenom')
                      ->with(['attendances' => function($q) use ($selectedDate) {
                          $q->where('date', $selectedDate);
                      }]);
            }])
            ->get();

        // Stats calculées
        $stats = [
            'present' => 0,
            'absent' => 0,
            'justified' => 0
        ];

        foreach ($filieres as $filiere) {
            foreach ($filiere->students as $student) {
                $status = $student->attendances->first()->status ?? 1;
                if ($status === 1) $stats['present']++;
                if ($status === 0) $stats['absent']++;
                if ($status === 2) $stats['justified']++;
            }
        }

        return view('formateur.attendance.index', [
            'filieres' => $filieres,
            'selectedDate' => $selectedDate,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array'
        ]);

        foreach ($request->attendances as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $request->date,
                    'formateur_id' => Auth::id()
                ],
                [
                    'status' => $data['status'],
                    'marked_by_role' => 'formateur'
                ]
            );
        }

        return back()->with('success', 'Présences enregistrées avec succès!');
    }
}