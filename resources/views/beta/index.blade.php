@extends(Auth::guard('web')->check() ? 'layouts.for' : 'layouts.stu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">🎥 Cours en Direct (Beta)</h1>
            @if(isset($canCreate) && $canCreate)
            <a href="{{ route('beta.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Créer un nouveau live
            </a>
            @endif
        </div>
    </div>

    @if($lives->count() > 0)
    <div class="row">
        @foreach($lives as $live)
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span class="badge bg-danger">EN DIRECT</span>
                    <small>{{ $live->meeting_id }}</small>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $live->titre }}</h5>
                    <p class="card-text">
                        <strong>Promo :</strong> {{ $live->promotion->nom_promotion ?? 'Toutes' }}<br>
                        <strong>Démarré le :</strong> {{ $live->date_debut }}
                    </p>

                    @if(isset($canCreate) && $canCreate && $live->formateur_id === Auth::id())
                    <a href="{{ route('beta.host', $live->id) }}" class="btn btn-warning w-100">
                        <i class="fas fa-satellite-dish"></i> Reprendre le live (Hôte)
                    </a>
                    @else
                    {{-- Bouton Rejoindre pour Étudiants ou autres formateurs --}}
                    @php
                    $joinRoute = Auth::guard('web')->check() ? route('beta.join', $live->id) : route('etudiant.lives.join', $live->id);
                    @endphp
                    <a href="{{ $joinRoute }}" class="btn btn-success w-100">
                        <i class="fas fa-sign-in-alt"></i> Rejoindre
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <h3 class="text-muted">Aucun cours en direct pour le moment. 😴</h3>
        @if(isset($canCreate) && $canCreate)
        <p>Soyez le premier à lancer un live !</p>
        @endif
    </div>
    @endif
</div>
@endsection