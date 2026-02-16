<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::user();
        
        // Vérification que l'admin a bien un site associé
        if (!$admin->site_id) {
            abort(403, "Aucun site associé à votre compte. Contactez le superadmin.");
        }
        
        // Charge le site avec le compte des étudiants (méthode optimale)
        $site = Site::withCount('students')->find($admin->site_id);
        
        // Solution alternative si withCount ne marche pas
        if (!$site) {
            $students_count = Student::where('site_id', $admin->site_id)->count();
            return view('admin.dashboard', [
                'students_count' => $students_count,
                'messages_count' => 0
            ]);
        }
        
        return view('admin.dashboard', [
            'site' => $site,
            'students_count' => $site->students_count,
            'messages_count' => 0
        ]);
    }
}