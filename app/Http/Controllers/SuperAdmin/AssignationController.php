<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignation;
use App\Models\Site;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Subject;

class AssignationController extends Controller
{
    // Liste des assignations
  public function index()
{
    $assignationsRaw = Assignation::with(['site', 'formateur', 'subject', 'filiere'])->get();

    // ✅ Grouper intelligemment : site → formateur → matière → filières
    $assignationsGrouped = $assignationsRaw->groupBy('site_id')->map(function($siteGroup) {
        $site = $siteGroup->first()->site;
        
        // Grouper par formateur dans ce site
        $formateurs = $siteGroup->groupBy('formateur_id')->map(function($formateurGroup) {
            $formateur = $formateurGroup->first()->formateur;
            
            // Grouper par matière
            $matieres = $formateurGroup->groupBy('subject_id')->map(function($matiereGroup) {
                $filieresCount = \App\Models\Filiere::count();
                $subject = $matiereGroup->first()->subject;
                $filiereNames = $matiereGroup->pluck('filiere.nom')->unique();
                $count = $filiereNames->count();
                
                if ($count === $filieresCount) {
                    $filiere_display = 'Toutes les filières';
                } elseif ($count === 1) {
                    $filiere_display = $filiereNames->first();
                } else {
                    $filiere_display = $count . ' filière' . ($count > 1 ? 's' : '');
                }
                
                return [
                    'subject' => $subject,
                    'filiere_display' => $filiere_display,
                    'filiere_count' => $count,
                    'assignation_id' => $matiereGroup->first()->id,
                ];
            });
            
            return [
                'formateur' => $formateur,
                'matieres' => $matieres,
                'total_matieres' => $matieres->count()
            ];
        });
        
        return [
            'site' => $site,
            'formateurs' => $formateurs
        ];
    });

    return view('superadmin.assignations.index', [
        'assignationsGrouped' => $assignationsGrouped,
    ]);
}

    // Formulaire de création
   public function create()
{
    $sites = Site::all();
    $formateurs = User::where('role', 'formateur')->get();
    $filieres = Filiere::all();
    $subjects = Subject::with('filieres')->get();
    
    // ✅ Préparer les données pour JavaScript (format simplifié)
    $subjectFilieresMap = $subjects->mapWithKeys(function($subject) {
        return [$subject->id => $subject->filieres->pluck('id')->toArray()];
    })->toArray();

    return view('superadmin.assignations.create', compact('sites', 'formateurs', 'filieres', 'subjects', 'subjectFilieresMap'));
}

    // ✅ MODIFIÉ : Stocker assignations avec PLUSIEURS matières
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'formateur_id' => 'required|exists:users,id',
            'subject_ids' => 'required|array|min:1',      // ✅ MODIFIÉ : Plusieurs matières
            'subject_ids.*' => 'exists:subjects,id',       // ✅ AJOUTÉ
            'filiere_ids' => 'required|array|min:1',
            'filiere_ids.*' => 'exists:filieres,id',
            'trimestres' => 'required|array|min:1',
            'trimestres.*' => 'integer|in:1,2,3',
        ]);

        $count = 0;

        // ✅ Boucler sur TOUTES les matières sélectionnées
        foreach ($request->subject_ids as $subject_id) {
            // ✅ Pour chaque filière
            foreach ($request->filiere_ids as $filiere_id) {
                Assignation::create([
                    'site_id' => $request->site_id,
                    'formateur_id' => $request->formateur_id,
                    'subject_id' => $subject_id,
                    'filiere_id' => $filiere_id,
                    'trimestres' => $request->trimestres,
                ]);
                $count++;
            }
        }

        return redirect()->route('superadmin.assignations.index')
            ->with('success', "$count assignation(s) créée(s) avec succès.");
    }

    // Formulaire de modification
  public function edit($id)
{
    $assignation = Assignation::findOrFail($id);
    $sites = Site::all();
    $formateurs = User::where('role', 'formateur')->get();
    $filieres = Filiere::all();
    $subjects = Subject::with('filieres')->get(); // ✅ Charger avec filières

    // Récupérer toutes les assignations liées
    $relatedAssignations = Assignation::where('formateur_id', $assignation->formateur_id)
        ->where('subject_id', $assignation->subject_id)
        ->where('site_id', $assignation->site_id)
        ->get();

    $filiereIds = $relatedAssignations->pluck('filiere_id')->toArray();
    
    // ✅ Préparer les données pour JavaScript
    $subjectFilieresMap = $subjects->mapWithKeys(function($subject) {
        return [$subject->id => $subject->filieres->pluck('id')->toArray()];
    })->toArray();

    return view('superadmin.assignations.edit', compact('assignation', 'sites', 'formateurs', 'filieres', 'subjects', 'filiereIds', 'subjectFilieresMap'));
}

    // ✅ MODIFIÉ : Mise à jour avec trimestres
   public function update(Request $request, $id)
{
    $request->validate([
        'site_id' => 'required|exists:sites,id',
        'formateur_id' => 'required|exists:users,id',
        'subject_ids' => 'required|array|min:1',      // ✅ Plusieurs matières
        'subject_ids.*' => 'exists:subjects,id',
        'filiere_ids' => 'required|array|min:1',
        'filiere_ids.*' => 'exists:filieres,id',
        'trimestres' => 'required|array|min:1',
        'trimestres.*' => 'integer|in:1,2,3',
    ]);

    $assignation = Assignation::findOrFail($id);

    // Supprimer toutes les assignations liées
    Assignation::where('formateur_id', $assignation->formateur_id)
        ->where('subject_id', $assignation->subject_id)
        ->where('site_id', $assignation->site_id)
        ->delete();

    $count = 0;

    // ✅ Créer les nouvelles assignations pour chaque matière ET chaque filière
    foreach ($request->subject_ids as $subject_id) {
        foreach ($request->filiere_ids as $filiere_id) {
            Assignation::create([
                'site_id' => $request->site_id,
                'formateur_id' => $request->formateur_id,
                'subject_id' => $subject_id,
                'filiere_id' => $filiere_id,
                'trimestres' => $request->trimestres,
            ]);
            $count++;
        }
    }

    return redirect()->route('superadmin.assignations.index')
        ->with('success', "$count assignation(s) mise(s) à jour avec succès.");
}

    // Supprimer une assignation
    public function destroy($id)
    {
        $assignation = Assignation::findOrFail($id);
        $assignation->delete();

        return redirect()->route('superadmin.assignations.index')
            ->with('success', 'Assignation supprimée.');
    }

    // Supprimer plusieurs assignations (même formateur + matière + site)
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'site_id' => 'required|integer',
            'formateur_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        Assignation::where('site_id', $request->site_id)
            ->where('formateur_id', $request->formateur_id)
            ->where('subject_id', $request->subject_id)
            ->delete();

        return redirect()->route('superadmin.assignations.index')
            ->with('success', 'Assignation supprimée avec succès.');
    }
}