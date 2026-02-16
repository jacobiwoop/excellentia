<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fee;


class PaymentController extends Controller
{
    // ---------------- Gestion des paiements d’un étudiant ----------------
      public function managePayments(StudentFee $studentFee)
    {
        if ($studentFee->student->site_id != auth()->user()->site_id) abort(403);

        // Charger les relations
        $studentFee->load(['payments', 'student', 'fee']);
        $backUrl = request()->query('back_url', url()->previous());

        // Charger tous les frais disponibles (formation, inscription, soutenance)
        $fees = Fee::whereIn('type', ['formation', 'inscription', 'soutenance'])
            ->orderBy('nom')
            ->get();

        // S'assurer que chaque paiement a un fee_id défini
        $payments = $studentFee->payments->sortBy('date_paiement')->map(function ($payment) use ($studentFee) {
            if (!$payment->fee_id) {
                // Si le paiement n'a pas de fee_id, on prend celui du StudentFee
                $payment->fee_id = $studentFee->fee_id;
            }
            return $payment;
        });

        return view('admin.student_fees.manage_payments', [
            'studentFee' => $studentFee,
            'payments' => $payments,
            'backUrl' => $backUrl,
            'fees' => $fees,
        ]);
    }

    // ---------------- Mise à jour des paiements ----------------
  public function updatePayments(Request $request, StudentFee $studentFee)
{
    // Vérification que l'utilisateur peut modifier ce StudentFee
    if ($studentFee->student->site_id != auth()->user()->site_id) abort(403);

    // Validation des champs
    $request->validate([
        'payments' => 'required|array',
        'payments.*.fee_id' => 'required|exists:fees,id', // validation du type de frais
        'payments.*.date_paiement' => 'required|date',
        'payments.*.montant' => 'required|numeric|min:0',
        'payments.*.mode_paiement' => 'required|in:espèce,chèque,virement,mobile_money',
        'payments.*.note' => 'nullable|string'
    ]);

    DB::transaction(function () use ($request, $studentFee) {
        foreach ($request->payments as $paymentId => $data) {
            $payment = Payment::where('id', $paymentId)
                ->firstOrFail();

            // Si le type de frais a changé
            if ($payment->studentFee->fee_id != $data['fee_id']) {
                // Chercher ou créer le StudentFee correspondant au nouveau type
                $newStudentFee = StudentFee::firstOrCreate(
                    [
                        'student_id' => $studentFee->student_id,
                        'fee_id' => $data['fee_id']
                    ],
                    [
                        'montant_total' => optional(Fee::find($data['fee_id']))->montant ?? 0,
                        'montant_reduction' => 0,
                        'promotion_id' => $studentFee->promotion_id,
                        'site_id' => $studentFee->site_id,
                        'statut' => 'non_payé',
                    ]
                );

                // Déplacer le paiement vers le nouveau StudentFee
                $payment->student_fee_id = $newStudentFee->id;
            }

            // Mettre à jour les autres champs du paiement
            $payment->update([
                'fee_id' => $data['fee_id'],
                'date_paiement' => $data['date_paiement'],
                'montant' => $data['montant'],
                'mode_paiement' => $data['mode_paiement'],
                'note' => $data['note'] ?? null,
            ]);

            // Mettre à jour le statut pour l'ancien et le nouveau StudentFee
            $payment->studentFee->updateStatut();
            if (isset($newStudentFee)) {
                $newStudentFee->updateStatut();
                unset($newStudentFee); // pour ne pas réutiliser dans la prochaine boucle
            }
        }
    });

    return redirect()->route('admin.student_fees.manage', $studentFee->id)
                     ->with('success', 'Paiements mis à jour avec succès !');
}


    // ---------------- Génération facture PDF ----------------
    public function generateInvoice($student_id)
    {
        $studentFees = StudentFee::with('student', 'payments')
            ->where('student_id', $student_id)
            ->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.payments.invoice', compact('studentFees'));

        return $pdf->download("facture_etudiant_{$student_id}.pdf");
    }
}
