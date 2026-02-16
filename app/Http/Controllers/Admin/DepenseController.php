<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DepenseController extends Controller
{
    // Liste des dépenses
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Filtres
        $mois = $request->input('mois');
        $annee = $request->input('annee', date('Y'));
        
        // Requête avec filtres
        $depenses = Depense::with(['site', 'createdBy', 'formateur'])
            ->where('site_id', $user->site_id)
            ->when($mois, fn($q) => $q->whereMonth('date_depense', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_depense', $annee))
            ->orderBy('date_depense', 'desc')
            ->paginate(20);
        
        // Statistiques
        $totalDepenses = Depense::where('site_id', $user->site_id)
            ->when($mois, fn($q) => $q->whereMonth('date_depense', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_depense', $annee))
            ->sum('montant');
        
        $depensesSalaires = Depense::where('site_id', $user->site_id)
            ->whereNotNull('formateur_id')
            ->when($mois, fn($q) => $q->whereMonth('date_depense', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_depense', $annee))
            ->sum('montant');
        
        $depensesAutres = Depense::where('site_id', $user->site_id)
            ->whereNull('formateur_id')
            ->when($mois, fn($q) => $q->whereMonth('date_depense', $mois))
            ->when($annee, fn($q) => $q->whereYear('date_depense', $annee))
            ->sum('montant');
        
        return view('admin.depenses.index', compact(
            'depenses', 
            'totalDepenses', 
            'depensesSalaires', 
            'depensesAutres',
            'mois',
            'annee'
        ));
    }
    
    // Formulaire d'ajout
    public function create()
    {
        $user = auth()->user();
        
        // Liste des formateurs du site
        $formateurs = User::where('role', 'formateur')
            ->orderBy('name')
            ->get();
        
        return view('admin.depenses.create', compact('formateurs'));
    }
    
    // Enregistrement
    // Dans la méthode store()
public function store(Request $request)
{
    $user = auth()->user();
    
    $request->validate([
        'motif' => 'required|string|max:255',
        'montant' => 'required|numeric|min:0',
        'date_depense' => 'required|date',
        'description' => 'nullable|string',
        'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // ✅ OPTIONNEL
        'formateur_id' => 'nullable|exists:users,id',
    ]);
    
    $data = [
        'motif' => $request->motif,
        'montant' => $request->montant,
        'date_depense' => $request->date_depense,
        'description' => $request->description,
        'site_id' => $user->site_id,
        'formateur_id' => $request->formateur_id,
        'created_by' => $user->id,
    ];
    
    // ✅ Upload OPTIONNEL
    if ($request->hasFile('justificatif')) {
        $file = $request->file('justificatif');
        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
        
        $directory = base_path('private_files/depenses');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        $file->move($directory, $filename);
        $data['justificatif'] = 'depenses/' . $filename;
    }
    
    Depense::create($data);
    
    return redirect()->route('admin.depenses.index')
        ->with('success', 'Dépense enregistrée avec succès !');
}
    
    // Formulaire de modification
    public function edit($id)
    {
        $user = auth()->user();
        
        $depense = Depense::where('site_id', $user->site_id)->findOrFail($id);
        
        $formateurs = User::where('role', 'formateur')
            ->orderBy('name')
            ->get();
        
        return view('admin.depenses.edit', compact('depense', 'formateurs'));
    }
    
    // Mise à jour
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $depense = Depense::where('site_id', $user->site_id)->findOrFail($id);
        
        $request->validate([
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'description' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'formateur_id' => 'nullable|exists:users,id',
        ]);
        
        $data = [
            'motif' => $request->motif,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'description' => $request->description,
            'formateur_id' => $request->formateur_id,
            'updated_by' => $user->id,
        ];
        
        // ✅ Upload du nouveau justificatif
        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien fichier
            if ($depense->justificatif) {
                $oldFile = base_path('private_files/' . $depense->justificatif);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
            
            $file = $request->file('justificatif');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            
            $directory = base_path('private_files/depenses');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            $file->move($directory, $filename);
            $data['justificatif'] = 'depenses/' . $filename;
        }
        
        $depense->update($data);
        
        return redirect()->route('admin.depenses.index')
            ->with('success', 'Dépense modifiée avec succès !');
    }
    
    // Suppression
    public function destroy($id)
    {
        $user = auth()->user();
        
        $depense = Depense::where('site_id', $user->site_id)->findOrFail($id);
        
        // Supprimer le fichier
        if ($depense->justificatif) {
            $filePath = base_path('private_files/' . $depense->justificatif);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        
        $depense->delete();
        
        return redirect()->route('admin.depenses.index')
            ->with('success', 'Dépense supprimée avec succès !');
    }
    
    // ✅ Télécharger un justificatif
  public function downloadJustificatif($id)
{
    $user = auth()->user();
    
    $depense = Depense::where('site_id', $user->site_id)->findOrFail($id);
    
    // ✅ Vérification stricte
    if (!$depense->justificatif) {
        return redirect()->back()->with('error', 'Aucun justificatif disponible pour cette dépense.');
    }
    
    $filePath = base_path('private_files/' . $depense->justificatif);
    
    if (!File::exists($filePath)) {
        return redirect()->back()->with('error', 'Le fichier justificatif est introuvable.');
    }
    
    return response()->download($filePath);
}
}