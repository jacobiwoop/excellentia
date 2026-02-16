<?php

namespace App\Http\Controllers\Admingen;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Site;
use App\Models\Filiere;
use App\Models\Grade;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Facades\File;
 use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;


class StudentController extends Controller
{
    
    public function index(Request $request)
{
    // Récupération des données pour les filtres
    $promotions = Promotion::orderBy('date_debut', 'desc')->get();
    $filieres = Filiere::orderBy('nom')->get();
    
    // Construction de la requête
   $students = Student::with('site', 'filiere', 'promotion')
    ->when($request->promotion_id, function($query, $promotionId) {
        return $query->where('promotion_id', $promotionId);
    })
    ->when($request->filiere_id, function($query, $filiereId) {
        return $query->where('filiere_id', $filiereId);
    })
    ->orderBy('created_at', 'desc') // ✅ Ajout du tri
    ->paginate(25)
    ->appends($request->query());

    
    return view('admingen.students.index', compact('students', 'promotions', 'filieres'));
}



public function store(Request $request)
{
   

    $request->validate([
        'nom_prenom' => 'required|string|max:255',
        'telephone' => 'required|string|max:20',
        'email' => 'nullable|email|unique:students,email',
        'date_naissance' => 'nullable|date',
'lieu_naissance' => 'nullable|string|max:255',

        'sexe' => 'required|in:M,F',
        'filiere_id' => 'required|exists:filieres,id',
        'promotion_id' => 'required|exists:promotions,id',
        'photo' => 'nullable|image|max:2048',
    ]);
$site = Site::findOrFail($request->site_id);
$filiere = Filiere::findOrFail($request->filiere_id);

// Génération du matricule
do {
    $random = mt_rand(1000, 9999);
    $matricule = $filiere->code . '-' . $random . '-' . $site->code;
} while (Student::where('matricule', $matricule)->exists());

// 📸 Upload de la photo
$photoPath = null;
if ($request->hasFile('photo')) {
    $directory = $_SERVER['DOCUMENT_ROOT'] . '/uploads/students_photos';

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
    $request->file('photo')->move($directory, $photoName);
    $photoPath = 'uploads/students_photos/' . $photoName;
}

Student::create([
    'nom_prenom' => $request->nom_prenom,
    'matricule' => $matricule,
    'telephone' => $request->telephone,
    'email' => $request->email,
    'date_naissance' => $request->date_naissance,
    'lieu_naissance' => $request->lieu_naissance,
    'sexe' => $request->sexe,
    'site_id' => $request->site_id, // ✅ on prend la valeur depuis le formulaire
    'filiere_id' => $request->filiere_id,
    'promotion_id' => $request->promotion_id,
    'photo' => $photoPath,
]);

    return redirect()->route('admingen.students.index')->with('success', 'Étudiant créé avec le matricule: ' . $matricule);
}

    
    public function show($id)
    {
        $student = Student::with([
            'site', 
            'filiere',
            'promotion',
            'grades.assignation.subject' // Charger les notes avec leurs relations
        ])->findOrFail($id);
    
        // Récupérer toutes les notes de l'étudiant
        $grades = $student->grades;
    
        return view('admingen.students.show', compact('student', 'grades'));
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        
        if ($student->photo && Storage::exists($student->photo)) {
            Storage::delete($student->photo);
        }

        $student->delete();

        return redirect()->route('admingen.students.index')->with('success', 'Étudiant supprimé avec succès.');
    }

    public function showBulletin($studentId, $term = 1)
{
    $student = Student::with(['filiere', 'site', 'promotion'])->findOrFail($studentId);
    $grades = Grade::where('student_id', $studentId)
                 ->where('term', $term)
                 ->with(['assignation.subject'])
                 ->get();

    return view('admingen.students.show', [
        'student' => $student,
        'grades' => $grades,
        'currentTerm' => $term,
        'bulletinGrades' => $grades // Pour le bulletin spécifiquement
    ]);
}


public function updateStatut(Request $request, $id)
{
    $request->validate([
        'statut' => 'required|in:en_cours,termine,abandonne',
    ]);

    $student = Student::findOrFail($id);
    $student->statut = $request->statut;
    $student->save();

    return back()->with('success', 'Statut mis à jour avec succès.');
}
}