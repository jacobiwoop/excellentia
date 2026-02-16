@extends('layouts.ad')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-history mr-2 text-primary"></i> Historique des paiements
            </h5>
            <p class="text-dark mb-0 mt-2">
                <span class="font-weight-bold">{{ $studentFee->student->nom_prenom }}</span> - 
                <span class="text-muted">{{ $studentFee->fee->nom ?? 'Inconnu' }}</span>
            </p>
        </div>
        
        <div class="card-body px-0">
            <!-- Résumé financier -->
            <div class="px-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between bg-light rounded p-3">
                    <div class="mb-2 mr-3">
                        <span class="text-dark d-block small font-weight-bold">Montant total</span>
                        <span class="h5 font-weight-bold">{{ number_format($studentFee->montant_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="mb-2 mr-3">
                        <span class="text-dark d-block small font-weight-bold">Réduction</span>
                        <span class="h5 font-weight-bold text-warning">{{ number_format($studentFee->montant_reduction, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="mb-2 mr-3">
                        <span class="text-dark d-block small font-weight-bold">Payé</span>
                        <span class="h5 font-weight-bold text-success">{{ number_format($studentFee->payments->sum('montant'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-dark d-block small font-weight-bold">Reste</span>
                        <span class="h5 font-weight-bold {{ ($studentFee->montant_total - $studentFee->montant_reduction - $studentFee->payments->sum('montant')) > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($studentFee->montant_total - $studentFee->montant_reduction - $studentFee->payments->sum('montant'), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>

            <!-- Historique des paiements -->
            <div class="border-top pt-3">
                <h6 class="px-4 font-weight-bold text-dark mb-3">Détail des transactions</h6>
                
                <div class="px-4">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="small text-uppercase text-muted">
                                    <th class="border-0 pl-0">Date</th>
                                    <th class="border-0 text-right">Montant</th>
                                    <th class="border-0 text-right pr-0">Reste</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $montantNormal = $studentFee->fee->filieres->find($studentFee->student->filiere_id)?->pivot->montant ?? 0;
                                    $reduction = $studentFee->montant_reduction ?? 0;
                                    $reste = $montantNormal - $reduction;
                                    $paiementsCumul = 0;
                                @endphp

                                @forelse($studentFee->payments as $payment)
                                    @php
                                        $paiementsCumul += $payment->montant;
                                        $resteApresPaiement = $reste - $paiementsCumul;
                                    @endphp
                                    <tr class="border-top">
                                        <td class="pl-0">{{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y') }}</td>
                                        <td class="text-right font-weight-bold text-success">+{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right font-weight-bold pr-0 {{ $resteApresPaiement > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format(max($resteApresPaiement, 0), 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-top">
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="fas fa-money-bill-wave fa-2x mb-3 d-block" style="opacity: 0.5"></i>
                                            <span class="font-weight-normal">Aucun paiement enregistré</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

      <!-- Dans la section des boutons -->
<div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-2"></i> Retour
    </a>
    
    @if($studentFee->payments->count() > 0)
        <a href="{{ route('admin.student_fees.manage', $studentFee->id) }}" 
           class="btn btn-primary">
           <i class="fas fa-edit mr-2"></i> Gérer les paiements
        </a>
    @endif
</div>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
    }
    .table {
        font-size: 15px;
    }
    .table th {
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .table td {
        padding: 12px 8px;
        vertical-align: middle;
    }
    .table tr:not(:first-child) {
        border-top: 1px solid #ececec;
    }
    .h5 {
        font-size: 1.1rem;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .text-warning {
        color: #fd7e14 !important;
    }
</style>
@endsection