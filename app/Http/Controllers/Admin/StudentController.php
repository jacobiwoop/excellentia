<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Site;
use App\Models\Filiere;
use App\Models\Grade;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
  public function index()
{
    $adminSiteId = Auth::user()->site_id;

    // ✅ FILTRER UNIQUEMENT LES ÉTUDIANTS EN COURS
    $students = Student::where('site_id', $adminSiteId)
        ->where('statut', 'en_cours') // ✅ AJOUT DU FILTRE
        ->with(['site', 'filiere', 'promotion'])
        ->paginate(15);

    return view('admin.students.index', compact('students'));
}

    public function create()
    {
        $adminSiteId = Auth::user()->site_id;
        $site = Site::findOrFail($adminSiteId);

        $filieres = Filiere::all();
        $promotions = Promotion::all();
        $sexes = ['M' => 'Masculin', 'F' => 'Féminin'];

        return view('admin.students.create', [
            'site' => $site,
            'filieres' => $filieres,
            'promotions' => $promotions,
            'sexes' => $sexes
        ]);
    }

    public function store(Request $request)
    {
        $adminSiteId = Auth::user()->site_id;

        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'telephone' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'sexe' => 'required|in:M,F',
            'filiere_id' => 'required|exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $filiere = Filiere::findOrFail($request->filiere_id);
        $site = Site::findOrFail($adminSiteId);

        do {
            $random = mt_rand(1000, 9999);
            $matricule = $filiere->code . '-' . $random . '-' . $site->code;
        } while (Student::where('matricule', $matricule)->exists());

        Student::create([
            'nom_prenom' => $request->nom_prenom,
            'matricule' => $matricule,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'sexe' => $request->sexe,
            'site_id' => $adminSiteId,
            'filiere_id' => $request->filiere_id,
            'promotion_id' => $request->promotion_id,
            'photo' => $request->file('photo') ? $request->file('photo')->store('students_photos', 'public') : null,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Étudiant créé avec le matricule : ' . $matricule);
    }

    public function edit(Student $student)
    {
        if ($student->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        $filieres = Filiere::all();
        $promotions = Promotion::all();
        $sexes = ['M' => 'Masculin', 'F' => 'Féminin'];

        return view('admin.students.edit', compact('student', 'filieres', 'promotions', 'sexes'));
    }

    public function update(Request $request, Student $student)
    {
        if ($student->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'filiere_id' => 'required|exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'nom_prenom',
            'telephone',
            'email',
            'date_naissance',
            'lieu_naissance',
            'sexe',
            'filiere_id',
            'promotion_id',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students_photos', 'public');
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Student $student)
    {
        if ($student->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Étudiant supprimé avec succès.');
    }

    public function show($id)
    {
        $student = Student::with([
            'site',
            'filiere',
            'promotion',
            'grades.assignation.subject'
        ])->findOrFail($id);

        $grades = $student->grades;

        return view('admin.students.show', compact('student', 'grades'));
    }
}
