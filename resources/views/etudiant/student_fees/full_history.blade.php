@extends('layouts.stu')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-lg">
        <div class="card-header text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history mr-2"></i> Mon Historique
                </h5>
                <span class="badge bg-white text-primary">
                    {{ $payments->count() }} paiement(s)
                </span>
            </div>
        </div>
        
        <div class="card-body px-0">
            <!-- Statistiques résumées -->
            <div class="px-4 mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success h-100 py-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-coins fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-success mb-1">
                                            Total Payé
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($payments->sum('montant'), 0, ',', ' ') }} FCFA
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-info h-100 py-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-calendar-check fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-info mb-1">
                                            Premier Paiement
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            @if($payments->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($payments->last()->date_paiement)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-warning h-100 py-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning mb-1">
                                            Dernier Paiement
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            @if($payments->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($payments->first()->date_paiement)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des paiements -->
            <div class="table-responsive px-4">
                <table class="table table-hover">
                    <thead class="bg-light-primary">
                        <tr>
                            <th class="border-0">
                                <i class="fas fa-calendar-day mr-1"></i> Date
                            </th>
                            <th class="border-0">
                                <i class="fas fa-tag mr-1"></i> Type de Frais
                            </th>
                            <th class="border-0 text-right">
                                <i class="fas fa-money-bill-wave mr-1"></i> Montant
                            </th>
                            <th class="border-0">
                                <i class="fas fa-credit-card mr-1"></i> Mode
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="align-middle">
                                <span class="badge badge-light">
                                    {{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="align-middle font-weight-bold">
                                {{ $payment->studentFee->fee->nom }}
                            </td>
                            <td class="align-middle text-right text-success font-weight-bold">
                                +{{ number_format($payment->montant, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $payment->mode_paiement === 'espèce' ? 'success' : 'info' }}">
                                    {{ ucfirst($payment->mode_paiement) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-4"></i>
                                    <h3 class="text-muted">Aucun paiement enregistré</h3>
                                    <p class="text-muted">Vos paiements apparaîtront ici</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination et bouton de retour -->
            <div class="px-4 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('etudiant.student_fees.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    
                    @if($payments->isNotEmpty())
                    <div class="export-buttons">
                        <button class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
   
   
 
    .empty-state {
        opacity: 0.7;
        transition: opacity 0.3s;
    }
    .empty-state:hover {
        opacity: 1;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .table {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .table th {
        border-top: none;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
</style>
@endsection