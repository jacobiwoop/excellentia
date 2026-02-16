<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des présences avec les relations
        $attendances = Attendance::with(['student.filiere', 'formateur'])
            ->latest()
            ->paginate(20);

        // Statistiques simples
        $stats = [
            'present' => Attendance::where('status', 1)->count(),
            'justified' => Attendance::where('status', 2)->count(),
            'absent' => Attendance::where('status', 0)->count(),
            'total' => Attendance::count()
        ];

        return view('superadmin.attendances.index', [
            'attendances' => $attendances,
            'stats' => $stats,
            'filieres' => Filiere::all(),
            'formateurs' => User::where('role', 'formateur')->get()
        ]);
    }
}