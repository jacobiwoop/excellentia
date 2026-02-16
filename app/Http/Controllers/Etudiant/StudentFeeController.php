<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentFeeController extends Controller
{

    public function index()
{
    $student = auth()->guard('student')->user();

    if (!$student) {
        return redirect()->route('etudiant.student_fees.missing-profile')
            ->with('error', 'Veuillez vous connecter');
    }

    // 1️⃣ Récupérer tous les StudentFee avec leurs paiements
    $studentFees = StudentFee::with(['fee', 'payments'])
        ->where('student_id', $student->id)
        ->get();

    // 2️⃣ Préparer les frais principaux (inscription, formation, soutenance)
    $filiereFees = $student->filiere->fees ?? collect();
    $types = ['inscription', 'formation', 'soutenance'];
    $feesData = collect();

    foreach ($types as $type) {
        $fee = $filiereFees->firstWhere('type', $type);
        $studentFee = $studentFees->firstWhere('fee.type', $type);

        $montantStandard = $fee ? $fee->pivot->montant : 0;
        $montantTotal = $studentFee ? max(0, $studentFee->montant_total - $studentFee->montant_reduction) : $montantStandard;
        $montantPaye = $studentFee ? $studentFee->payments->sum('montant') : 0;
        $reste = max(0, $montantTotal - $montantPaye);

        $feesData->push((object)[
            'type' => $type,
            'nom' => $fee->nom ?? ucfirst($type),
            'montant_standard' => $montantStandard,
            'montant_total' => $montantTotal,
            'montant_paye' => $montantPaye,
            'reste' => $reste,
            'student_fee_id' => $studentFee ? $studentFee->id : null
        ]);
    }

    // 3️⃣ Transformer tous les StudentFee en paiements individuels
    $allPayments = $studentFees->flatMap(function($sf){
        return $sf->payments->map(function($p) use ($sf){
            $p->studentFee = $sf; // pour garder la référence au StudentFee
            return $p;
        });
    })->sortByDesc('date_paiement'); // trier du plus récent au plus ancien

    // 4️⃣ Envoyer à la vue
    return view('etudiant.student_fees.index', [
        'student' => $student,
        'studentFees' => $studentFees,
        'feesData' => $feesData,
        'paymentsList' => $allPayments, // tous les paiements individuels
        'totalExpected' => $feesData->sum('montant_total'),
        'totalPaid' => $feesData->sum('montant_paye'),
        'totalRemaining' => $feesData->sum('reste'),
        'totalNormal' => $feesData->sum('montant_standard'),
        'lastUpdated' => now()->format('d/m/Y à H:i'),
    ]);
}


   public function showReceiptHTML($id)
{
    $payment = Payment::with([
        'studentFee.student.filiere',
        'studentFee.fee',
    ])->findOrFail($id);

    $student = auth()->guard('student')->user();
    if (!$student || $payment->studentFee->student_id !== $student->id) {
        abort(403, 'Accès refusé');
    }

    return view('etudiant.student_fees.receipt', [
        'payment' => $payment,
        'student' => $payment->studentFee->student,
        'receiptNumber' => 'RECU-' . Str::upper(Str::random(8)),
        'date' => now()->format('d/m/Y'),
    ]);
}


public function fullHistory()
{
    $student = auth()->guard('student')->user();
    
    $allPayments = Payment::whereHas('studentFee', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })
        ->with(['studentFee.fee', 'studentFee.student'])
        ->orderBy('date_paiement', 'desc')
        ->get();

    return view('etudiant.student_fees.full_history', [
        'payments' => $allPayments,
        'student' => $student
    ]);
}
}
