<?php

namespace App\Http\Controllers\AdminGen;

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

    $grouped = $assignationsRaw->groupBy(function ($item) {
        return $item->site_id . '-' . $item->formateur_id . '-' . $item->subject_id;
    });

    $filieresCount = Filiere::count();

    $assignations = $grouped->map(function ($group) use ($filieresCount) {
        $first = $group->first();

        $filiereNames = $group->pluck('filiere.nom')->unique();
        $count = $filiereNames->count();

        if ($count === $filieresCount) {
            $filiere_nom = 'Toutes les filières';
        } elseif ($count === 1) {
            $filiere_nom = $filiereNames->first();
        } else {
            $filiere_nom = $count . ' filière' . ($count > 1 ? 's' : '') . ' sélectionnée' . ($count > 1 ? 's' : '');
        }

        return (object)[
            'id' => $first->id,
            'site_id' => $first->site_id,
            'formateur_id' => $first->formateur_id,
            'subject_id' => $first->subject_id,
            'site' => $first->site,
            'formateur' => $first->formateur,
            'subject' => $first->subject,
            'filiere_nom' => $filiere_nom,
        ];
    });

    return view('admingen.assignations.index', [
        'assignations' => $assignations,
    ]);
}

    // Formulaire de création
    public function create()
    {
        $sites = Site::all();
        $formateurs = User::where('role', 'formateur')->get();
        $filieres = Filiere::all();
        $subjects = Subject::all();

        return view('admingen.assignations.create', compact('sites', 'formateurs', 'filieres', 'subjects'));
    }

    // Stocker assignations (plusieurs filières)
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required',
            'formateur_id' => 'required',
            'subject_id' => 'required',
            'filiere_ids' => 'required|array|min:1',
        ]);

        foreach ($request->filiere_ids as $filiere_id) {
            Assignation::create([
                'site_id' => $request->site_id,
                'formateur_id' => $request->formateur_id,
                'subject_id' => $request->subject_id,
                'filiere_id' => $filiere_id,
            ]);
        }

        return redirect()->route('admingen.assignations.index')->with('success', 'Assignation(s) créée(s) avec succès.');
    }

    // Formulaire de modification
    public function edit($id)
    {
        $assignation = Assignation::findOrFail($id);
        $sites = Site::all();
        $formateurs = User::where('role', 'formateur')->get();
        $filieres = Filiere::all();
        $subjects = Subject::all();

        // Récupérer toutes les filières liées à cette assignation (même formateur, sujet, site)
        $relatedAssignations = Assignation::where('formateur_id', $assignation->formateur_id)
            ->where('subject_id', $assignation->subject_id)
            ->where('site_id', $assignation->site_id)
            ->get();

        $filiereIds = $relatedAssignations->pluck('filiere_id')->toArray();

        return view('admingen.assignations.edit', compact('assignation', 'sites', 'formateurs', 'filieres', 'subjects', 'filiereIds'));
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $request->validate([
            'site_id' => 'required',
            'formateur_id' => 'required',
            'subject_id' => 'required',
            'filiere_ids' => 'required|array|min:1',
        ]);

        $assignation = Assignation::findOrFail($id);

        // Supprimer toutes les assignations liées au même formateur, sujet et site
        Assignation::where('formateur_id', $assignation->formateur_id)
            ->where('subject_id', $assignation->subject_id)
            ->where('site_id', $assignation->site_id)
            ->delete();

        // Créer les nouvelles assignations avec les filières sélectionnées
        foreach ($request->filiere_ids as $filiere_id) {
            Assignation::create([
                'site_id' => $request->site_id,
                'formateur_id' => $request->formateur_id,
                'subject_id' => $request->subject_id,
                'filiere_id' => $filiere_id,
            ]);
        }

        return redirect()->route('admingen.assignations.index')->with('success', 'Assignation mise à jour avec succès.');
    }

    // Supprimer une assignation
public function destroy($id)
{
    $assignation = Assignation::findOrFail($id);
    $assignation->delete();

    return redirect()->route('admingen.assignations.index')->with('success', 'Assignation supprimée.');
}

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

    return redirect()->route('admingen.assignations.index')->with('success', 'Assignation supprimée avec succès.');
}


}
