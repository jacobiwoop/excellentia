@extends('layouts.dash')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0"><i class="fas fa-edit me-2"></i>Modifier les frais</h2>
            <a href="{{ route('superadmin.fees.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('superadmin.fees.update', $fee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nom du frais</label>
                        <input type="text" name="nom" class="form-control" 
                               value="{{ old('nom', $fee->nom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type de frais</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ ucfirst($fee->type) }}" readonly>
                        <small class="text-muted">Le type ne peut pas être modifié</small>
                    </div>
                </div>

                @if($fee->type === 'formation')
                <div class="card mb-4 border-primary">
                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="60%">Filière</th>
                                        <th width="40%">Montant (FCFA)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filieres as $filiere)
                                    @php
                                        $pivot = $fee->filieres->firstWhere('id', $filiere->id);
                                        $montant = $pivot->pivot->montant ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $filiere->nom }}</td>
                                        <td>
    <input type="number" 
           name="filieres[{{ $filiere->id }}][montant]" 
           class="form-control" 
           value="{{ old('filieres.' . $filiere->id . '.montant', intval($montant)) }}" 
           min="0"
           step="1000"
           required>
    <input type="hidden" name="filieres[{{ $filiere->id }}][id]" value="{{ $filiere->id }}">
</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Montant unique</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $montant = optional($fee->filieres->first())->pivot->montant ?? 0;
                        @endphp
                        <div class="mb-3">
                            <label>Montant pour toutes les filières</label>
                            <input type="number" 
                                   name="filieres[{{ $filieres->first()->id }}][montant]" 
                                   class="form-control" 
                                   value="{{ old('filieres.' . $filieres->first()->id . '.montant', $montant) }}" 
                                   min="0"
                                   step="1000"
                                   required>
                            <input type="hidden" name="filieres[{{ $filieres->first()->id }}][id]" value="{{ $filieres->first()->id }}">
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('superadmin.fees.index') }}" class="btn btn-light">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        padding: 1rem 1.5rem;
    }
    table td {
        vertical-align: middle;
    }
    input[type="number"] {
        text-align: right;
    }
</style>
@endsection