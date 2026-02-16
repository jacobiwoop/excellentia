@extends('layouts.ad')

@section('content')
<div class="container mt-4">
    <h2>Configurer les frais</h2>
    <form action="{{ route('admin.fees.store') }}" method="POST">
        @csrf
       
        <div class="mb-3">
            <label>Type de frais</label>
            <select name="type" id="fee-type" class="form-control" required>
                <option value="formation">Frais de Formation</option>
                <option value="inscription">Frais d'Inscription</option>
                <option value="soutenance">Frais de Soutenance</option>
                <option value="autre">Autre Frais</option>
            </select>
        </div>

        <div id="filieres-fields">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Montants par filière</h5>
                </div>
                <div class="card-body">
                    @foreach($filieres as $filiere)
                    <div class="mb-3">
                        <label>{{ $filiere->nom }}</label>
                        <input type="number" 
                               name="filieres[{{ $filiere->id }}][montant]" 
                               class="form-control" 
                               placeholder="Montant en FCFA"
                               min="0"
                               step="1"
                               required>
                        <input type="hidden" name="filieres[{{ $filiere->id }}][id]" value="{{ $filiere->id }}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.fees.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
