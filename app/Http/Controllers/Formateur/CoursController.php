<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Assignation;
use App\Models\Filiere;
use App\Models\Promotion;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{

  public function index()
{
    $cours = Cours::where('formateur_id', Auth::id())
        ->with(['assignation.site', 'assignation.filiere', 'promotion'])
        ->get();

    return view('formateur.cours.index', compact('cours'));
}


    public function create()
    {
        $sites = \App\Models\Site::all();
        $filieres = \App\Models\Filiere::all();
        $promotions = Promotion::all();

        return view('formateur.cours.create', compact('sites', 'filieres', 'promotions'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,mov,avi|max:40960',
            'sites' => 'required|array',
            'sites.*' => 'exists:sites,id',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
        ]);

        // Upload fichier
        $fichier = $request->file('fichier');
        $nomFichier = time() . '_' . $fichier->getClientOriginalName();
        $chemin = 'uploads/cours/';
        $fichier->move(public_path($chemin), $nomFichier);
        $fichierPath = $chemin . $nomFichier;

        // Boucle sur chaque combinaison site + filière
        foreach ($request->sites as $siteId) {
            foreach ($request->filieres as $filiereId) {
                $assignation = Assignation::where('formateur_id', Auth::id())
                    ->where('site_id', $siteId)
                    ->where('filiere_id', $filiereId)
                    ->first();

                if ($assignation) {
                    Cours::create([
                        'titre' => $request->titre,
                        'description' => $request->description,
                        'fichier_path' => $fichierPath,
                        'assignation_id' => $assignation->id,
                        'promotion_id' => $request->promotion_id,
                        'formateur_id' => Auth::id(),
                    ]);
                }
            }
        }


        return redirect()->route('formateur.cours.index')
            ->with('success', 'Cours ajouté avec succès.');
    }

public function edit(Cours $cour)
{
    $sites = Site::all();
    $filieres = Filiere::all();
    $promotions = Promotion::all();

    // récupérer tous les cours liés au même fichier
    $coursGroup = Cours::where('fichier_path', $cour->fichier_path)
        ->where('formateur_id', Auth::id())
        ->get();

    // extraire les sites et filières sélectionnés
    $selectedSites = $coursGroup->map(fn($c) => $c->assignation->site_id)->unique()->toArray();
    $selectedFilieres = $coursGroup->map(fn($c) => $c->assignation->filiere_id)->unique()->toArray();

    return view('formateur.cours.edit', compact('cour','sites','filieres','promotions','selectedSites','selectedFilieres'));
}

    public function update(Request $request, Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,mov,avi|max:40960',
            'sites' => 'required|array',
            'sites.*' => 'exists:sites,id',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
        ]);

        // Gestion du fichier
        $fichierPath = $cour->fichier_path;
        if ($request->hasFile('fichier')) {
            if ($fichierPath && file_exists(public_path($fichierPath))) {
                unlink(public_path($fichierPath));
            }
            $fichier = $request->file('fichier');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $chemin = 'uploads/cours/';
            $fichier->move(public_path($chemin), $nomFichier);
            $fichierPath = $chemin . $nomFichier;
        }

        // Supprimer toutes les anciennes entrées de ce fichier
       // Supprimer toutes les anciennes entrées de ce fichier
Cours::where('fichier_path', $cour->fichier_path)
    ->where('formateur_id', Auth::id())
    ->delete();

// Recréer pour chaque combinaison site + filière
foreach ($request->sites as $siteId) {
    foreach ($request->filieres as $filiereId) {
        $assignation = Assignation::where('formateur_id', Auth::id())
            ->where('site_id', $siteId)
            ->where('filiere_id', $filiereId)
            ->first();

        if ($assignation) {
            Cours::create([
                'titre' => $request->titre,
                'description' => $request->description,
                'fichier_path' => $fichierPath,
                'assignation_id' => $assignation->id,
                'promotion_id' => $request->promotion_id,
                'formateur_id' => Auth::id(),
            ]);
        }
    }
}


        return redirect()->route('formateur.cours.index')
            ->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        $coursASupprimer = Cours::where('fichier_path', $cour->fichier_path)
            ->where('formateur_id', Auth::id())
            ->get();

        $cheminFichier = public_path($cour->fichier_path);
        if (file_exists($cheminFichier)) {
            unlink($cheminFichier);
        }

        $coursASupprimer->each->delete();

        return redirect()->route('formateur.cours.index')->with('success', 'Cours et toutes ses versions supprimés avec succès.');
    }

    public function show(Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        return view('formateur.cours.show', compact('cour'));
    }
}
