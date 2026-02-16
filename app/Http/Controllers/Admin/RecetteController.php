<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RecetteController extends Controller
{
    // Liste des recettes
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Filtres
        $mois = $request->input('mois');
        $annee = $request->input('annee', date('Y'));
        
        // Requête avec filtres
        $recettes = Recette::with(['site', 'createdBy', 'studentFee.student'])
            ->where('site_id', $user->site_id)
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_recette', $annee))
            ->orderBy('date_recette', 'desc')
            ->paginate(20);
        
        // Statistiques
        $totalRecettes = Recette::where('site_id', $user->site_id)
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_recette', $annee))
            ->sum('montant');
        
        $recettesScolarite = Recette::where('site_id', $user->site_id)
            ->whereNotNull('student_fee_id')
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_recette', $annee))
            ->sum('montant');
        
        $recettesAutres = Recette::where('site_id', $user->site_id)
            ->whereNull('student_fee_id')
            ->when($mois, fn($q) => $q->whereMonth('date_recette', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_recette', $annee))
            ->sum('montant');
        
        return view('admin.recettes.index', compact(
            'recettes', 
            'totalRecettes', 
            'recettesScolarite', 
            'recettesAutres',
            'mois',
            'annee'
        ));
    }
    
    // Formulaire d'ajout
    public function create()
    {
        return view('admin.recettes.create');
    }
    
    // Enregistrement
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_recette' => 'required|date',
            'description' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);
        
        $data = [
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_recette' => $request->date_recette,
            'description' => $request->description,
            'site_id' => $user->site_id,
            'student_fee_id' => null,
            'created_by' => $user->id,
        ];
        
        // ✅ Upload dans private_files
        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            
            // Créer le dossier s'il n'existe pas
            $directory = base_path('private_files/recettes');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            // Déplacer le fichier
            $file->move($directory, $filename);
            $data['justificatif'] = 'recettes/' . $filename;
        }
        
        Recette::create($data);
        
        return redirect()->route('admin.recettes.index')
            ->with('success', 'Recette ajoutée avec succès !');
    }
    
    // Formulaire de modification
    public function edit($id)
    {
        $user = auth()->user();
        
        $recette = Recette::where('site_id', $user->site_id)
            ->whereNull('student_fee_id')
            ->findOrFail($id);
        
        return view('admin.recettes.edit', compact('recette'));
    }
    
    // Mise à jour
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $recette = Recette::where('site_id', $user->site_id)
            ->whereNull('student_fee_id')
            ->findOrFail($id);
        
        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_recette' => 'required|date',
            'description' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $data = [
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_recette' => $request->date_recette,
            'description' => $request->description,
            'updated_by' => $user->id,
        ];
        
        // ✅ Upload du nouveau justificatif
        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien fichier
            if ($recette->justificatif) {
                $oldFile = base_path('private_files/' . $recette->justificatif);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
            
            $file = $request->file('justificatif');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            
            $directory = base_path('private_files/recettes');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            $file->move($directory, $filename);
            $data['justificatif'] = 'recettes/' . $filename;
        }
        
        $recette->update($data);
        
        return redirect()->route('admin.recettes.index')
            ->with('success', 'Recette modifiée avec succès !');
    }
    
    // Suppression
    public function destroy($id)
    {
        $user = auth()->user();
        
        $recette = Recette::where('site_id', $user->site_id)
            ->whereNull('student_fee_id')
            ->findOrFail($id);
        
        // Supprimer le fichier
        if ($recette->justificatif) {
            $filePath = base_path('private_files/' . $recette->justificatif);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        
        $recette->delete();
        
        return redirect()->route('admin.recettes.index')
            ->with('success', 'Recette supprimée avec succès !');
    }
    
    // ✅ NOUVEAU : Télécharger un justificatif
    public function downloadJustificatif($id)
    {
        $user = auth()->user();
        
        $recette = Recette::where('site_id', $user->site_id)->findOrFail($id);
        
        if (!$recette->justificatif) {
            abort(404, 'Aucun justificatif trouvé');
        }
        
        $filePath = base_path('private_files/' . $recette->justificatif);
        
        if (!File::exists($filePath)) {
            abort(404, 'Fichier introuvable');
        }
        
        return response()->download($filePath);
    }
}