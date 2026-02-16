<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceOverviewController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->date ?? now()->format('Y-m-d');
    
        $filieres = Filiere::with(['students.attendances' => function($query) use ($selectedDate) {
            $query->whereDate('date', $selectedDate)->with('formateur');
        }])->get()->map(function ($filiere) {
            $students = $filiere->students->map(function ($student) {
                $attendance = $student->attendances->first();
                return [
                    'id' => $student->id,
                    'nom' => $student->nom_prenom,
                    'matricule' => $student->matricule,
                    'status' => $attendance->status ?? null, // ← Changé à null au lieu de 0
                    'formateur' => $attendance->formateur->name ?? null // ← Laissez comme avant
                ];
            });
    
            // Calcul uniquement des présences explicites (status = 1)
            $presentCount = $students->where('status', 1)->count();
            $totalStudents = $filiere->students->count();
    
            return [
                'id' => $filiere->id,
                'nom' => $filiere->nom,
                'total' => $totalStudents,
                'present' => $presentCount,
                'absent' => $students->where('status', 0)->count(), // Seulement les absences explicites
                'taux' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0,
                'formateurs' => $students->pluck('formateur')->filter()->unique()->implode(', ') ?: '-',
                'students' => $students
            ];
        });
    

        $globalStats = [
            'total' => $filieres->sum('total'),
            'present' => $filieres->sum('present'),
            'taux' => $filieres->avg('taux')
        ];

        return view('admingen.attendance.overview', compact('filieres', 'globalStats', 'selectedDate'));
    }
}