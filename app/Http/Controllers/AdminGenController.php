<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminGenController extends Controller
{
    public function dashboard()
    {
        // Charger les sites avec le compte des étudiants et formateurs
        $sites = Site::withCount(['students', 'formateurs'])->get();
        
        $studentCount = Student::count();
        $formateurCount = User::where('role', 'formateur')->count();
        $adminCount = User::where('role', 'admin_site')->count();

        return view('admingen.dashboard', compact('sites', 'studentCount', 'formateurCount', 'adminCount'));
    }

    public function showSite($id)
    {
        $site = Site::with(['students', 'formateurs'])->findOrFail($id);
        return view('admingen.sites.show', compact('site'));
    }
}