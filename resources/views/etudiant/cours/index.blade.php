@extends('layouts.stu')

@section('content')
<div class="container py-5">
    @if($cours->isEmpty())
        <div class="alert alert-light border text-center py-4">
            <i class="far fa-folder-open fa-2x mb-3 text-muted"></i>
            <p class="h5 text-muted">Aucun cours disponible actuellement</p>
        </div>
    @else
        <div class="row">
            @foreach($cours as $cour)
                <div class="col-md-6 col-lg-3 mb-4 ui">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $cour->created_at->locale('fr')->isoFormat('D MMM') }}
                                </small>
                            </div>

                            <h5 class="card-title font-weight-bold text-dark mb-3">
                                {{ Str::limit($cour->titre, 50) }}
                            </h5>

                            <p class="card-text text-muted mb-4">
                                {{ Str::limit($cour->description, 100) }}
                            </p>

                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm mr-3">
                                    <div class="avatar-title bg-light rounded-circle text-primary">
                                        <i class="fas fa-user-tie text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 font-weight-bold">{{ $cour->formateur->name ?? 'Formateur' }}</p>
                                    <small class="text-muted">Formateur</small>
                                </div>
                            </div>

                            {{-- Affichage filière --}}
                            @if($cour->assignation && $cour->assignation->filiere)
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $cour->assignation->filiere->nom }}
                                </p>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                            @if($cour->fichier_path)
                                <a href="{{ route('etudiant.cours.viewer', $cour->id) }}"
                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="far fa-eye mr-2"></i> Lire
                                </a>
                            @else
                                <span class="text-muted small">
                                    <i class="far fa-times-circle mr-1"></i> Aucun fichier joint
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $cours->links() }}
        </div>
    @endif
</div>
@endsection
