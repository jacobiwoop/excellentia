@extends('layouts.ad')

@section('content')
<div class="container mt-4">
    <h2>Modifier les frais</h2>
    <form action="{{ route('admin.fees.update', $fee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom du frais</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $fee->nom) }}" required>
        </div>

        <div class="mb-3">
            <label>Type de frais</label>
            <input type="text" class="form-control" value="{{ ucfirst($fee->type) }}" readonly>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Montants par filière (en FCFA)</h5>
            </div>
            <div class="card-body">
                @foreach($filieres as $filiere)
                @php
                    $pivot = $fee->filieres->firstWhere('id', $filiere->id);
                    $montant = isset($pivot->pivot->montant) ? (int)$pivot->pivot->montant : '';
                @endphp
                <div class="mb-3">
                    <label>{{ $filiere->nom }}</label>
                    <input type="number" 
                           name="filieres[{{ $filiere->id }}][montant]" 
                           class="form-control" 
                           value="{{ old('filieres.' . $filiere->id . '.montant', $montant) }}" 
                           min="0"
                           step="1"
                           required>
                    <input type="hidden" name="filieres[{{ $filiere->id }}][id]" value="{{ $filiere->id }}">
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.fees.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
