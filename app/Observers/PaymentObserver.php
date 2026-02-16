<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Recette;

class PaymentObserver
{
    /**
     * Déclenché quand un paiement est créé
     */
    public function created(Payment $payment): void
    {
        // Charge la relation studentFee pour avoir accès au student et site_id
        $payment->load('studentFee.student');

        // Vérifie qu'une recette identique n'existe pas déjà
        $exists = Recette::where('student_fee_id', $payment->student_fee_id)
            ->where('montant', $payment->montant)
            ->where('date_recette', $payment->date_paiement)
            ->where('is_automatique', true)
            ->exists();

        if (!$exists) {
            Recette::create([
                'motif' => 'Paiement scolarité - ' . ($payment->studentFee->student->nom_prenom ?? 'Étudiant'),
                'montant' => $payment->montant,
                'date_recette' => $payment->date_paiement,
                'site_id' => $payment->studentFee->site_id,
                'student_fee_id' => $payment->student_fee_id,
                'is_automatique' => true,
                'created_by' => $payment->created_by ?? auth()->id() ?? 1,
            ]);
        }
    }

    /**
     * Déclenché quand un paiement est modifié
     */
    public function updated(Payment $payment): void
    {
        // Trouve la recette automatique liée à ce paiement
        $recette = Recette::where('student_fee_id', $payment->student_fee_id)
            ->where('is_automatique', true)
            ->where('created_at', $payment->created_at) // Pour identifier la bonne recette
            ->first();

        if ($recette) {
            $payment->load('studentFee.student');
            
            $recette->update([
                'motif' => 'Paiement scolarité - ' . ($payment->studentFee->student->nom_prenom ?? 'Étudiant'),
                'montant' => $payment->montant,
                'date_recette' => $payment->date_paiement,
            ]);
        }
    }

    /**
     * Déclenché quand un paiement est supprimé
     */
    public function deleted(Payment $payment): void
    {
        // Supprime la recette automatique correspondante
        Recette::where('student_fee_id', $payment->student_fee_id)
            ->where('is_automatique', true)
            ->where('montant', $payment->montant)
            ->where('date_recette', $payment->date_paiement)
            ->delete();
    }
}