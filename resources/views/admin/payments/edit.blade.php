@extends('layouts.ad')

@section('content')
<div class="container mt-4">
    <h2>Modifier un paiement</h2>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Étudiant</label>
                        <input type="text" class="form-control" 
                               value="{{ $payment->studentFee->student->nom_prenom }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Type de frais</label>
                        <input type="text" class="form-control" 
                               value="{{ $payment->studentFee->fee->nom }}" readonly>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Montant (FCFA)</label>
                        <input type="number" name="montant" class="form-control" 
                               value="{{ old('montant', $payment->montant) }}" required min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label>Date de paiement</label>
                        <input type="date" name="date_paiement" class="form-control"
       value="{{ old('date_paiement', $payment->date_paiement ? \Carbon\Carbon::parse($payment->date_paiement)->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Mode de paiement</label>
                        <select name="mode_paiement" class="form-control" required>
                            <option value="espèce" {{ $payment->mode_paiement == 'espèce' ? 'selected' : '' }}>Espèce</option>
                            <option value="chèque" {{ $payment->mode_paiement == 'chèque' ? 'selected' : '' }}>Chèque</option>
                            <option value="virement" {{ $payment->mode_paiement == 'virement' ? 'selected' : '' }}>Virement</option>
                            <option value="mobile_money" {{ $payment->mode_paiement == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>Note</label>
                    <textarea name="note" class="form-control">{{ old('note', $payment->note) }}</textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.student_fees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection