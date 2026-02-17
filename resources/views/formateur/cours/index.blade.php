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
        <h2 class="mb-0">📚 Vos cours enregistrés</h2>
        <a href="{{ route('formateur.cours.create', ['type' => 'file']) }}" class="btn btn-dark px-4">
            Ajouter un fichier
        </a>
    </div>

    {{-- Si aucun cours --}}
    @if($cours->isEmpty())
    <div class="alert alert-info">
        Aucun cours enregistré pour le moment.
    </div>
    @else
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        @foreach ($cours->groupBy('fichier_path') as $fichier => $coursGroup)
        @php
        $c = $coursGroup->first(); // on prend le premier juste pour titre/description
        $extension = strtolower(pathinfo($c->fichier_path, PATHINFO_EXTENSION));
        @endphp

        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    {{-- Titre --}}
                    <h5 class="card-title text-truncate">{{ $c->titre }}</h5>

                    {{-- Description --}}
                    <p class="card-text text-muted small">{{ Str::limit($c->description, 80) }}</p>

                    {{-- Liste des sites/filières/promo --}}
                    @php
                    $totalFilieres = \App\Models\Filiere::count(); // nombre total de filières
                    $coursFiliereCount = $coursGroup->pluck('assignation.filiere_id')->unique()->count();
                    @endphp

                    <ul class="small text-muted mb-2">
                        @if($coursFiliereCount === $totalFilieres)
                        <li>
                            <strong>Site :</strong> Multiple |
                            <strong>Filière :</strong> Toutes les filières |
                            <strong>Promotion :</strong> {{ $coursGroup->first()->promotion->nom ?? '-' }}
                        </li>
                        @else
                        @foreach ($coursGroup as $cg)
                        <li>
                            <strong>Site :</strong> {{ $cg->assignation->site->nom ?? '-' }} |
                            <strong>Filière :</strong> {{ $cg->assignation->filiere->nom ?? '-' }} |
                            <strong>Promotion :</strong> {{ $cg->promotion->nom ?? '-' }}
                        </li>
                        @endforeach
                        @endif
                    </ul>

                </div>

                {{-- Boutons --}}
                <div class="card-footer bg-white border-0 pt-0">
                    <div class="d-flex justify-content-between">
                        {{-- Voir/Télécharger --}}
                        @if($extension === 'pdf' || in_array($extension, ['jpg','jpeg','png','gif','mp4','mov','avi']))
                        <a href="{{ asset($c->fichier_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="far fa-eye"></i> Voir
                        </a>
                        @else
                        <a href="{{ asset($c->fichier_path) }}" download class="btn btn-sm btn-outline-secondary">
                            <i class="far fa-download"></i> Télécharger
                        </a>
                        @endif



                        {{-- Bouton Modifier --}}
                        <a href="{{ route('formateur.cours.edit', ['cour' => $c->id]) }}"
                            class="btn btn-sm btn-outline-primary" title="Modifier">
                            <i class="far fa-edit"></i>
                        </a>

                        {{-- Bouton Supprimer --}}
                        <form action="{{ route('formateur.cours.destroy', ['cour' => $c->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"
                                onclick="return confirm('Supprimer ce cours pour toutes les filières associées ?')">
                                <i class="far fa-trash-alt"></i>
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