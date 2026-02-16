@extends('layouts.ad')

@section('content')
<div class="container">
    <h2>Appliquer une réduction pour {{ $studentFee->student->nom_prenom }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.student_fees.reduction.apply', $studentFee->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="montant_reduction">Montant de la réduction (FCFA)</label>
            <input type="number" name="montant_reduction" id="montant_reduction" class="form-control" min="0" max="{{ $studentFee->montant_total }}" value="{{ old('montant_reduction', $studentFee->montant_reduction) }}" required>
        </div>

        <div class="mb-3">
            <label for="motif">Motif</label>
            <input type="text" name="motif" id="motif" class="form-control" maxlength="255" value="{{ old('motif', $studentFee->reduction_motif) }}">
        </div>

        <button type="submit" class="btn btn-primary">Appliquer la réduction</button>
        <a href="{{ route('admin.student_fees.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
