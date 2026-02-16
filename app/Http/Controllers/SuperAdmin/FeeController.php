<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\StudentFee; 
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('filieres')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.fees.index', compact('fees'));
    }

    public function create()
    {
        $this->ensureBasicFeesExist();
        $filieres = Filiere::all();
        return view('superadmin.fees.create', compact('filieres'));
    }

    protected function ensureBasicFeesExist()
    {
        $basicFees = [
            ['type' => 'formation', 'nom' => 'Frais de Formation'],
            ['type' => 'inscription', 'nom' => 'Frais d\'Inscription'],
            ['type' => 'soutenance', 'nom' => 'Frais de Soutenance']
        ];

        foreach ($basicFees as $fee) {
            Fee::firstOrCreate(
                ['type' => $fee['type']],
                ['nom' => $fee['nom']]
            );
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:formation,inscription,soutenance,autre',
            'filieres' => 'required|array',
            'filieres.*.montant' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $fee = Fee::updateOrCreate(
                ['type' => $request->type],
                ['nom' => $this->getFeeName($request->type), 'description' => $request->description]
            );

            $syncData = [];
            foreach ($request->filieres as $filiere) {
                $syncData[$filiere['id']] = ['montant' => $filiere['montant']];
            }
            $fee->filieres()->sync($syncData);
        });

        return redirect()->route('superadmin.fees.index')->with('success', 'Frais créés avec succès.');
    }

    protected function getFeeName($type)
    {
        return match($type) {
            'formation' => 'Frais de Formation',
            'inscription' => 'Frais d\'Inscription',
            'soutenance' => 'Frais de Soutenance',
            default => 'Autre Frais'
        };
    }

    public function show(string $id)
    {
        $fee = Fee::with(['filieres', 'studentFees.student'])->findOrFail($id);
        return view('superadmin.fees.show', compact('fee'));
    }

    public function edit(string $id)
    {
        $fee = Fee::with('filieres')->findOrFail($id);
        $filieres = Filiere::all();

        return view('superadmin.fees.edit', compact('fee', 'filieres'));
    }

    public function update(Request $request, string $id)
    {
        $fee = Fee::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'filieres' => 'required|array',
            'filieres.*.montant' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $fee) {
            // Mise à jour de l'entité Fee
            $fee->update([
                'nom' => $request->nom,
                'description' => $request->description,
            ]);

            $syncData = [];
            foreach ($request->filieres as $filiere) {
                $montant = $filiere['montant'];
                $filiereId = $filiere['id'];

                $syncData[$filiereId] = ['montant' => $montant];

                // ⭐ LOGIQUE CRITIQUE DE MISE À JOUR CASCADES (CORRIGÉE)
                // 1. Récupérer les modèles StudentFee concernés
                $studentFeesToUpdate = StudentFee::where('fee_id', $fee->id)
                    ->whereHas('student', function ($query) use ($filiereId) {
                        $query->where('filiere_id', $filiereId);
                    })
                    ->get(); // On utilise get() pour pouvoir boucler sur les modèles

                // 2. Boucler et mettre à jour individuellement
                foreach ($studentFeesToUpdate as $studentFee) {
                    $studentFee->montant_total = $montant;
                    
                    // La colonne `montant_total` est mise à jour avec le nouveau prix
                    $studentFee->save(); 

                    // 3. ⭐ TRÈS IMPORTANT : Recalculer le statut après la modification du montant total
                    // Ceci est nécessaire pour que le statut reflète la nouvelle dette (Payé, Partiel, Non Payé)
                    $studentFee->updateStatut();
                }
            }
            // Mise à jour de la table pivot fee_filiere (le montant de référence)
            $fee->filieres()->sync($syncData);
        });

        return redirect()->route('superadmin.fees.index')->with('success', 'Frais mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $fee = Fee::findOrFail($id);

        DB::transaction(function () use ($fee) {
            $fee->filieres()->detach();
            $fee->delete();
        });

        return redirect()->route('superadmin.fees.index')
            ->with('success', 'Frais supprimé avec succès');
    }
}