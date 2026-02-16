<?php
namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Charger les sites avec le compte des étudiants et formateurs
        $sites = Site::withCount(['students', 'formateurs'])->get();
        
        // Statistiques globales
        $studentCount = Student::count();
        $formateurCount = User::where('role', 'formateur')->count();
        $adminCount = User::where('role', 'admin_site')->count();
        
        // Statistiques par statut d'étudiants
        $studentsEnCours = Student::where('statut', 'en_cours')->count();
        $studentsAbandonnes = Student::where('statut', 'abandonné')->count();
        $studentsTermines = Student::where('statut', 'terminé')->count();
        
        return view('superadmin.dashboard', compact(
            'sites', 
            'studentCount', 
            'formateurCount', 
            'adminCount',
            'studentsEnCours',
            'studentsAbandonnes',
            'studentsTermines'
        ));
    }
    
    public function showSite($id)
    {
        $site = Site::with(['students', 'formateurs'])->findOrFail($id);
        return view('superadmin.sites.show', compact('site'));
    }
}