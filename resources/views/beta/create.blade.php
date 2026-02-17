@extends('layouts.for')

@section('content')
<div class="container mt-4">
    <h2>🎥 [BETA] Créer un Live Streaming</h2>

    <div class="alert alert-warning">
        Ceci est une version de démonstration isolée. Elle n'est pas visible des étudiants sur leur tableau de bord.
    </div>

    <form action="{{ route('beta.store') }}" method="POST">
        @csrf

        {{-- Titre --}}
        <div class="mb-3">
            <label for="titre" class="form-label">Titre de la séance</label>
            <input type="text" name="titre" id="titre" class="form-control" required placeholder="Ex: Cours de Mathématiques - Chapitre 3">
        </div>

        {{-- Promotion --}}
        <div class="mb-3">
            <label for="promotion_id" class="form-label">Promotion cible</label>
            <select name="promotion_id" id="promotion_id" class="form-select" required>
                @foreach($promotions as $promo)
                <option value="{{ $promo->id }}">{{ $promo->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- Date Début --}}
        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début</label>
            <input type="datetime-local" name="date_debut" id="date_debut" class="form-control" required
                value="{{ now()->format('Y-m-d\TH:i') }}">
        </div>

        <button type="submit" class="btn btn-primary">Créer et Lancer</button>
    </form>
</div>
@endsection