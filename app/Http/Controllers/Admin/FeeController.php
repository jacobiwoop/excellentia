<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Filiere;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('filieres')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.fees.index', compact('fees'));
    }

    public function create()
    {
        // Crée automatiquement les 3 types de frais s'ils n'existent pas
        $this->ensureBasicFeesExist();

        $filieres = Filiere::all();
        return view('admin.fees.create', compact('filieres'));
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

            // Sync tous les montants par filière, quel que soit le type
            $syncData = [];
            foreach ($request->filieres as $filiere) {
                $syncData[$filiere['id']] = ['montant' => $filiere['montant']];
            }
            $fee->filieres()->sync($syncData);
        });

        return redirect()->route('admin.fees.index')->with('success', 'Frais créés avec succès.');
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
        return view('admin.fees.show', compact('fee'));
    }

    public function edit(string $id)
    {
        $fee = Fee::with('filieres')->findOrFail($id);
        $filieres = Filiere::all();

        return view('admin.fees.edit', compact('fee', 'filieres'));
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
            $fee->update([
                'nom' => $request->nom,
                'description' => $request->description,
            ]);

            $syncData = [];
            foreach ($request->filieres as $filiere) {
                $syncData[$filiere['id']] = ['montant' => $filiere['montant']];
            }
            $fee->filieres()->sync($syncData);
        });

        return redirect()->route('admin.fees.index')->with('success', 'Frais mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $fee = Fee::findOrFail($id);

        DB::transaction(function () use ($fee) {
            $fee->filieres()->detach();
            $fee->delete();
        });

        return redirect()->route('admin.fees.index')
            ->with('success', 'Frais supprimé avec succès');
    }

    public function applyDiscount(Request $request, $feeId, $studentId)
    {
        $request->validate([
            'montant_reduction' => 'required|numeric|min:0',
            'motif' => 'required|string|max:255',
        ]);

        $studentFee = StudentFee::where('fee_id', $feeId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $studentFee->update([
            'reduction' => $request->montant_reduction,
            'motif_reduction' => $request->motif,
        ]);

        return back()->with('success', 'Réduction appliquée avec succès.');
    }
}
