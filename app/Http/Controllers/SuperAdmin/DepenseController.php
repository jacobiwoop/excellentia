<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $mois = $request->input('mois');
        $annee = $request->input('annee', now()->year);
        $siteId = $request->input('site_id');

        $query = Depense::with(['site', 'createdBy']);

        // Filtre par année
        $query->whereYear('date_depense', $annee);

        // Filtre par mois si spécifié
        if ($mois) {
            $query->whereMonth('date_depense', $mois);
        }

        // Filtre par site si spécifié
        if ($siteId) {
            $query->where('site_id', $siteId);
        }

        $depenses = $query->orderBy('date_depense', 'desc')->paginate(25);

        // Calcul du total des dépenses
        $totalDepenses = $query->sum('montant');

        $sites = Site::all();

        return view('superadmin.depenses.index', compact(
            'depenses',
            'totalDepenses',
            'sites',
            'mois',
            'annee',
            'siteId'
        ));
    }

    public function create()
    {
        $sites = Site::all();
        return view('superadmin.depenses.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:500',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Upload du justificatif
        $justificatifPath = null;
        if ($request->hasFile('justificatif')) {
            $directory = $_SERVER['DOCUMENT_ROOT'] . '/depenses';

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = time() . '_' . $request->file('justificatif')->getClientOriginalName();
            $request->file('justificatif')->move($directory, $fileName);
            $justificatifPath = 'depenses/' . $fileName;
        }

        Depense::create([
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'site_id' => $request->site_id,
            'description' => $request->description,
            'justificatif' => $justificatifPath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('superadmin.depenses.index')
            ->with('success', 'Dépense ajoutée avec succès.');
    }

    public function edit($id)
    {
        $depense = Depense::findOrFail($id);
        $sites = Site::all();
        return view('superadmin.depenses.edit', compact('depense', 'sites'));
    }

    public function update(Request $request, $id)
    {
        $depense = Depense::findOrFail($id);

        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:500',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'site_id' => $request->site_id,
            'description' => $request->description,
        ];

        // Upload du nouveau justificatif
        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien justificatif
            if ($depense->justificatif && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $depense->justificatif)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $depense->justificatif);
            }

            $directory = $_SERVER['DOCUMENT_ROOT'] . '/depenses';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = time() . '_' . $request->file('justificatif')->getClientOriginalName();
            $request->file('justificatif')->move($directory, $fileName);
            $data['justificatif'] = 'depenses/' . $fileName;
        }

        $depense->update($data);

        return redirect()->route('superadmin.depenses.index')
            ->with('success', 'Dépense mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $depense = Depense::findOrFail($id);

        // Supprimer le justificatif s'il existe
        if ($depense->justificatif && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $depense->justificatif)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $depense->justificatif);
        }

        $depense->delete();

        return redirect()->route('superadmin.depenses.index')
            ->with('success', 'Dépense supprimée avec succès.');
    }

  public function download($id)  // ✅ Changé de downloadDepense à download
{
    $depense = \App\Models\Depense::findOrFail($id);

    if (!$depense->justificatif) {
        return back()->with('error', 'Aucun justificatif disponible.');
    }

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $depense->justificatif;

    if (!file_exists($filePath)) {
        return back()->with('error', 'Fichier introuvable.');
    }

    $fileName = basename($depense->justificatif);
    
    return response()->download($filePath, $fileName);
}
}