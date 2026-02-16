<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Recette;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class RecetteController extends Controller
{
    public function index(Request $request)
    {
        $mois = $request->input('mois');
        $annee = $request->input('annee', now()->year);
        $siteId = $request->input('site_id');

        $query = Recette::with(['site', 'createdBy']);

        // Filtre par année
        $query->whereYear('date_recette', $annee);

        // Filtre par mois si spécifié
        if ($mois) {
            $query->whereMonth('date_recette', $mois);
        }

        // Filtre par site si spécifié
        if ($siteId) {
            $query->where('site_id', $siteId);
        }

        $recettes = $query->orderBy('date_recette', 'desc')->paginate(25);

        // Calcul du total des recettes
        $totalRecettes = $query->sum('montant');

        // Recettes par type
        $recettesScolarite = Recette::whereYear('date_recette', $annee)
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->where('is_automatique', true)
            ->sum('montant');

        $recettesAutres = Recette::whereYear('date_recette', $annee)
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->where('is_automatique', false)
            ->sum('montant');

        $sites = Site::all();

        return view('superadmin.recettes.index', compact(
            'recettes',
            'totalRecettes',
            'recettesScolarite',
            'recettesAutres',
            'sites',
            'mois',
            'annee',
            'siteId'
        ));
    }

    public function create()
    {
        $sites = Site::all();
        return view('superadmin.recettes.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_recette' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:500',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Upload du justificatif
        $justificatifPath = null;
        if ($request->hasFile('justificatif')) {
            $directory = $_SERVER['DOCUMENT_ROOT'] . '/recettes';

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = time() . '_' . $request->file('justificatif')->getClientOriginalName();
            $request->file('justificatif')->move($directory, $fileName);
            $justificatifPath = 'recettes/' . $fileName;
        }

        Recette::create([
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_recette' => $request->date_recette,
            'site_id' => $request->site_id,
            'description' => $request->description,
            'justificatif' => $justificatifPath,
            'is_automatique' => false,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('superadmin.recettes.index')
            ->with('success', 'Recette ajoutée avec succès.');
    }

    public function edit($id)
    {
        $recette = Recette::findOrFail($id);

        // Empêcher la modification des recettes automatiques
        if ($recette->is_automatique) {
            return back()->with('error', 'Les recettes automatiques ne peuvent pas être modifiées.');
        }

        $sites = Site::all();
        return view('superadmin.recettes.edit', compact('recette', 'sites'));
    }

    public function update(Request $request, $id)
    {
        $recette = Recette::findOrFail($id);

        // Empêcher la modification des recettes automatiques
        if ($recette->is_automatique) {
            return back()->with('error', 'Les recettes automatiques ne peuvent pas être modifiées.');
        }

        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_recette' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:500',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_recette' => $request->date_recette,
            'site_id' => $request->site_id,
            'description' => $request->description,
        ];

        // Upload du nouveau justificatif
        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien justificatif
            if ($recette->justificatif && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $recette->justificatif)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $recette->justificatif);
            }

            $directory = $_SERVER['DOCUMENT_ROOT'] . '/recettes';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = time() . '_' . $request->file('justificatif')->getClientOriginalName();
            $request->file('justificatif')->move($directory, $fileName);
            $data['justificatif'] = 'recettes/' . $fileName;
        }

        $recette->update($data);

        return redirect()->route('superadmin.recettes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $recette = Recette::findOrFail($id);

        // Empêcher la suppression des recettes automatiques
        if ($recette->is_automatique) {
            return back()->with('error', 'Les recettes automatiques ne peuvent pas être supprimées.');
        }

        // Supprimer le justificatif s'il existe
        if ($recette->justificatif && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $recette->justificatif)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $recette->justificatif);
        }

        $recette->delete();

        return redirect()->route('superadmin.recettes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }

  public function download($id)  // ✅ Changé de downloadRecette à download
{
    $recette = \App\Models\Recette::findOrFail($id);

    if (!$recette->justificatif) {
        return back()->with('error', 'Aucun justificatif disponible.');
    }

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $recette->justificatif;

    if (!file_exists($filePath)) {
        return back()->with('error', 'Fichier introuvable.');
    }

    $fileName = basename($recette->justificatif);
    
    return response()->download($filePath, $fileName);
}
}