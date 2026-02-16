<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Payment;
use App\Models\Recette; // ✅ AJOUTER CETTE LIGNE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentFeeController extends Controller
{
    // ---------------- Liste des étudiants avec leurs frais ----------------
   public function index(Request $request)
{
    $user = auth()->user();

    // 1) Toutes les promotions
    $promotions = \App\Models\Promotion::all();

    // 2) Tous les types de frais : formation, inscription, soutenance
    $fees = Fee::with('filieres')
        ->whereIn('type', ['formation', 'inscription', 'soutenance'])
        ->orderBy('nom')
        ->get();

    // 3) Récup des filtres depuis la requête
    $promotionId = $request->input('promotion_id');
    
    // ✅ FILTRE STATUT : par défaut "en_cours"
    $studentStatus = $request->input('student_status', 'en_cours');

    // 4) Fee par défaut : premier fee "formation" si rien en GET
    $feeId = $request->input('fee_id') ?? optional($fees->firstWhere('type', 'formation'))->id;

    // 5) S'il n'existe aucun fee, on affiche un message
    if (!$feeId) {
        $students = collect();
        return view('admin.student_fees.index', compact('students', 'promotions', 'promotionId', 'fees', 'feeId', 'studentStatus'))
            ->with('error', 'Aucun frais n\'est configuré.');
    }

    // 6) Charger les étudiants selon le statut sélectionné
    $students = Student::where('site_id', $user->site_id)
        ->when($promotionId, fn ($query) => $query->where('promotion_id', $promotionId))
        // ✅ FILTRE PAR STATUT (en_cours OU termine+abandonne)
        ->when($studentStatus === 'en_cours', fn ($query) => $query->where('statut', 'en_cours'))
        ->when($studentStatus === 'ancien', fn ($query) => $query->whereIn('statut', ['termine', 'abandonne']))
        ->with([
            'filiere',
            'studentFees' => fn ($q) => $q->where('fee_id', $feeId)->with(['fee.filieres', 'payments']),
            'promotion',
        ])
        ->get()
        ->map(function ($student) use ($feeId) {

            $studentFee = $student->studentFees->first();

            if (!$studentFee) {
                // Création si inexistant
                $studentFee = StudentFee::firstOrCreate(
                    ['student_id' => $student->id, 'fee_id' => $feeId],
                    [
                        'montant_total' => $this->getFeeAmountForStudent($feeId, $student),
                        'montant_reduction' => 0,
                        'site_id' => $student->site_id,
                        'promotion_id' => $student->promotion_id,
                        'statut' => 'non_payé'
                    ]
                );
            } else {
                // Mise à jour du montant total existant
                $nouveauMontant = $this->getFeeAmountForStudent($feeId, $student);
                if ($studentFee->montant_total != $nouveauMontant) {
                    $studentFee->montant_total = $nouveauMontant;
                    $studentFee->save();
                    $studentFee->updateStatut();
                }
            }

            $student->setRelation('studentFees', collect([$studentFee]));
            return $student;
        });

    return view('admin.student_fees.index', compact('students', 'promotions', 'promotionId', 'fees', 'feeId', 'studentStatus'));
}

    private function getFeeAmountForStudent($feeId, $student)
    {
        $fee = Fee::with(['filieres' => fn($q) => $q->where('filiere_id', $student->filiere_id)])
            ->findOrFail($feeId);

        return $fee->filieres->first()?->pivot->montant ?? $fee->montant ?? 0;
    }

    // ---------------- Enregistrement paiement ----------------
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'student_ids' => 'required|array',
            'montants_payes' => 'required|array',
            'fee_id' => 'required|exists:fees,id',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|string|in:espèce,chèque,virement,mobile_money'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->student_ids as $index => $studentId) {
                $montantPaye = floatval($request->montants_payes[$index] ?? 0);
                if ($montantPaye <= 0) continue;

                $student = Student::where('id', $studentId)
                    ->where('site_id', $user->site_id)
                    ->firstOrFail();

                $studentFee = StudentFee::firstOrCreate(
                    ['student_id' => $studentId, 'fee_id' => $request->fee_id],
                    [
                        'montant_total' => $this->getFeeAmountForStudent($request->fee_id, $student),
                        'montant_reduction' => 0,
                        'site_id' => $user->site_id,
                        'promotion_id' => $student->promotion_id,
                        'statut' => 'non_payé'
                    ]
                );

                $resteAPayer = $studentFee->reste_a_payer;

                if ($montantPaye > $resteAPayer) {
                    throw new \Exception("Le montant payé dépasse le reste à payer pour " . $student->nom_prenom);
                }

                // ✅ Créer le paiement
                $studentFee->payments()->create([
                    'montant' => $montantPaye,
                    'date_paiement' => $request->date_paiement,
                    'mode_paiement' => $request->mode_paiement,
                    'user_id' => $user->id
                ]);

                // ✅✅ NOUVEAU : Créer automatiquement la recette
                Recette::create([
                    'motif' => 'Scolarité - ' . $student->nom_prenom . ' - ' . $studentFee->fee->nom,
                    'montant' => $montantPaye,
                    'date_recette' => $request->date_paiement,
                    'description' => 'Paiement automatique depuis la gestion des frais scolaires',
                    'justificatif' => null,
                    'site_id' => $user->site_id,
                    'student_fee_id' => $studentFee->id,
                    'created_by' => $user->id,
                ]);

                $studentFee->updateStatut();
            }

            DB::commit();
            return back()->with('success', 'Paiements enregistrés avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    // ---------------- Historique des paiements ----------------
    public function history($id)
    {
        $studentFee = StudentFee::with(['student', 'fee', 'payments'])->findOrFail($id);
        return view('admin.student_fees.history', compact('studentFee'));
    }

    // ---------------- Formulaire de réduction ----------------
    public function showReductionForm($id)
    {
        $studentFee = StudentFee::with(['student', 'payments'])
            ->whereHas('student', fn($q) => $q->where('site_id', auth()->user()->site_id))
            ->findOrFail($id);

        return view('admin.student_fees.reduction', compact('studentFee'));
    }

    public function applyReduction(Request $request, $id)
    {
        $request->validate([
            'montant_reduction' => 'required|numeric|min:0',
            'motif' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $studentFee = StudentFee::with('payments')
                ->whereHas('student', fn($q) => $q->where('site_id', auth()->user()->site_id))
                ->findOrFail($id);

            if ($request->montant_reduction > $studentFee->montant_total) {
                throw new \Exception("La réduction ne peut pas dépasser le montant total");
            }

            $nouveauReste = $studentFee->montant_total - $request->montant_reduction - $studentFee->total_paye;

            if ($nouveauReste < 0) {
                throw new \Exception("Cette réduction rendrait le total payé supérieur au montant dû");
            }

            $studentFee->update([
                'montant_reduction' => $request->montant_reduction,
                'reduction_motif' => $request->motif
            ]);

            $studentFee->updateStatut();

            DB::commit();
            return back()->with('success', 'Réduction appliquée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}