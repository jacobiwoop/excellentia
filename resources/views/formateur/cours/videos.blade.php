@extends('layouts.for')

@section('content')
<div class="container mt-4">

    {{-- Message de succès --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🎥 Vos Vidéos de Cours</h2>
        <a href="{{ route('formateur.cours.create', ['type' => 'video']) }}" class="btn btn-dark px-4">
            Ajouter une vidéo
        </a>
    </div>

    {{-- Si aucune vidéo --}}
    @if($cours->isEmpty())
    <div class="alert alert-info">
        Aucune vidéo trouvée pour le moment.
    </div>
    @else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($cours as $c)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">

                {{-- Lecteur Vidéo --}}
                <div class="ratio ratio-16x9">
                    <video controls class="card-img-top">
                        <source src="{{ asset($c->video_path) }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </div>

                <div class="card-body">
                    {{-- Titre --}}
                    <h5 class="card-title text-truncate" title="{{ $c->titre }}">{{ $c->titre }}</h5>

                    {{-- Description --}}
                    <p class="card-text text-muted small">{{ Str::limit($c->description, 80) }}</p>

                    {{-- Infos --}}
                    <ul class="list-unstyled small text-muted mb-3">
                        <li><strong>Promotion:</strong> {{ $c->promotion->nom }}</li>
                        <li><strong>Site:</strong> {{ $c->assignation->site->nom }}</li>
                        <li><strong>Filière:</strong> {{ $c->assignation->filiere->nom }}</li>
                    </ul>
                </div>

                {{-- Boutons --}}
                <div class="card-footer bg-white border-0 pt-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('formateur.cours.edit', ['cour' => $c->id]) }}"
                            class="btn btn-sm btn-outline-primary" title="Modifier">
                            <i class="far fa-edit"></i> Modifier
                        </a>

                        <form action="{{ route('formateur.cours.destroy', ['cour' => $c->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"
                                onclick="return confirm('Supprimer ce cours et la vidéo associée ?')">
                                <i class="far fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection