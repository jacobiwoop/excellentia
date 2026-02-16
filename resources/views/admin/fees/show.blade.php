@extends('layouts.ad')

@section('content')
<div class="container mt-4">
    <h2>Détail du frais</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Nom :</strong> {{ $fee->name }}</li>
        <li class="list-group-item"><strong>Montant :</strong> {{ $fee->amount }} FCFA</li>
        <li class="list-group-item"><strong>Type :</strong> {{ $fee->type }}</li>
        <li class="list-group-item"><strong>Année scolaire :</strong> {{ $fee->school_year }}</li>
    </ul>
    <a href="{{ route('admin.fees.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
