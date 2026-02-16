@extends('layouts.ad')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Détails du frais - {{ $studentFee->student->nom_prenom }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            Informations sur le frais
        </div>
        <div class="card-body">
            <p><strong>Type de frais :</strong> {{ $studentFee->fee->nom }}</p>
            <p><strong>Montant total :</strong> {{ number_format($studentFee->montant_total, 0, ',', ' ') }} FCFA</p>
            <p><strong>Réduction :</strong> {{ number_format($studentFee->montant_reduction, 0, ',', ' ') }} FCFA</p>
            <p><strong>Montant payé :</strong> {{ number_format($studentFee->montant_paye, 0, ',', ' ') }} FCFA</p>
            <p><strong>Montant restant :</strong> {{ number_format($studentFee->reste, 0, ',', ' ') }} FCFA</p>
            <p><strong>Statut :</strong>
                @if($studentFee->statut === 'payé')
                    <span class="badge bg-success">Payé</span>
                @elseif($studentFee->statut === 'partiellement_payé')
                    <span class="badge bg-warning text-dark">Partiellement payé</span>
                @else
                    <span class="badge bg-danger">Non payé</span>
                @endif
            </p>
        </div>
    </div>

    <h3>Paiements associés</h3>
    @if ($studentFee->payments->isEmpty())
        <p>Aucun paiement enregistré.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant (FCFA)</th>
                    <th>Mode de paiement</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentFee->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y') }}</td>
                        <td>{{ number_format($payment->montant, 0, ',', ' ') }}</td>
                        <td>{{ ucfirst($payment->mode_paiement) }}</td>
                        <td>{{ $payment->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
<h3>Ajouter un paiement</h3>
<form action="{{ route('admin.payments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="student_fee_id" value="{{ $studentFee->id }}">

    <div class="mb-3">
        <label for="montant" class="form-label">Montant payé (FCFA)</label>
        <input type="number" name="montant" id="montant" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
        <label for="mode_paiement" class="form-label">Mode de paiement</label>
        <select name="mode_paiement" id="mode_paiement" class="form-control" required>
            <option value="espèce">Espèce</option>
            <option value="mobile money">Mobile Money</option>
            <option value="virement">Virement</option>
            <!-- Ajouter d'autres modes si besoin -->
        </select>
    </div>

    <div class="mb-3">
        <label for="date_paiement" class="form-label">Date du paiement</label>
        <input type="date" name="date_paiement" id="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="mb-3">
        <label for="note" class="form-label">Note (optionnel)</label>
        <textarea name="note" id="note" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Ajouter le paiement</button>
</form>


    
    <a href="{{ route('admin.student_fees.index') }}" class="btn btn-secondary mt-4">Retour à la liste</a>
</div>
@endsection
