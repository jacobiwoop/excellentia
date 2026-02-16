@extends('layouts.ad')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Paiements de {{ $student->nom_prenom }}</h1>

    @if($payments->isEmpty())
        <p>Aucun paiement enregistré pour cet étudiant.</p>
    @else
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant (FCFA)</th>
                    <th>Mode de paiement</th>
                    <th>Reçu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y') }}</td>
                    <td>{{ number_format($payment->montant, 0, ',', ' ') }}</td>
                    <td>{{ ucfirst($payment->mode_paiement) }}</td>
                    <td>
                        @if($payment->recu)
                            <a href="{{ asset('storage/' . $payment->recu) }}" target="_blank" class="btn btn-sm btn-outline-primary">Voir</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <!-- Ici tu peux ajouter bouton suppression si tu veux -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('admin.student_fees.show', $student->id) }}" class="btn btn-secondary mt-3">Retour aux frais</a>
</div>
@endsection
