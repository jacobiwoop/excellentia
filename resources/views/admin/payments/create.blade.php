@extends('layouts.ad')

@section('content')
<div class="container">
    <h2>Ajouter un paiement</h2>

@if (!$studentFee)
    <div class="alert alert-warning">
        Aucun frais sélectionné. Veuillez ajouter des données ou passer un <code>?student_fee_id=ID</code> dans l'URL.
    </div>
@else
    <form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="student_fee_id">Frais étudiant</label>
            <select name="student_fee_id" class="form-control">
                @if ($studentFee)
                    <option value="{{ $studentFee->id }}">{{ $studentFee->student->name }} ({{ $studentFee->amount }} FCFA)</option>
                @else
                    <option disabled selected>-- Aucune donnée disponible --</option>
                @endif
            </select>
        </div>

        <div class="form-group">
            <label for="amount_paid">Montant payé</label>
            <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid') }}">
        </div>

        <div class="form-group">
            <label for="payment_date">Date du paiement</label>
            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date') }}">
        </div>

        <button type="submit" class="btn btn-primary" @if (!$studentFee) disabled @endif>Enregistrer</button>
    </form>
    @endif
</div>
@endsection
