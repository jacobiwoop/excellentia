@extends('layouts.for')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 rounded-3 overflow-hidden shadow-sm">

        <!-- Header -->
        <div class="card-header bg-white py-4 px-5 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="m-0 text-dark fw-bold">
                        <i class="fas fa-clipboard-list text-primary me-3"></i>
                        Mes Matières Assignées
                    </h4>
                    <p class="mb-0 text-muted small mt-1">
                        Gérez vos matières et les notes des étudiants
                    </p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body p-0">

            {{-- Sécurité absolue --}}
            @if(!isset($assignations) || $assignations->isEmpty())
                <div class="text-center py-5 my-4">
                    <div class="avatar avatar-xl bg-soft-primary rounded-circle p-3 mb-3">
                        <i class="fas fa-book-open fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Aucune matière assignée</h5>
                    <p class="text-muted">
                        Vos matières apparaîtront ici lorsqu'elles vous seront assignées
                    </p>
                </div>
            @else

                <!-- Filtres -->
                <div class="p-4 border-bottom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="siteFilter">
                                <option value="">Tous les sites</option>
                                @foreach($assignations->unique('site_id') as $a)
                                    <option value="{{ $a->site_id }}">
                                        {{ $a->site->nom ?? 'Non spécifié' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select class="form-select" id="filiereFilter">
                                <option value="">Toutes les filières</option>
                                @foreach($assignations->unique('filiere_id') as $a)
                                    <option value="{{ $a->filiere_id }}">
                                        {{ $a->filiere->nom ?? 'Non spécifiée' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <input type="text" class="form-control"
                                   id="searchInput"
                                   placeholder="Rechercher une matière...">
                        </div>
                    </div>
                </div>

                <!-- Liste -->
                <div class="accordion" id="sitesAccordion">
                    @foreach($assignations->groupBy('site_id') as $siteId => $siteAssignations)
                        @php $site = $siteAssignations->first()->site @endphp

                        <div class="accordion-item border-0 site-section"
                             data-site-id="{{ $siteId }}">

                            <h2 class="accordion-header">
                                <button class="accordion-button bg-light"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $siteId }}"
                                        aria-expanded="true">
                                    <i class="fas fa-map-marker-alt text-info me-2"></i>
                                    <strong>{{ $site->nom ?? 'Site non spécifié' }}</strong>
                                </button>
                            </h2>

                            <div id="collapse{{ $siteId }}"
                                 class="accordion-collapse collapse show">
                                <div class="accordion-body p-0">

                                    @foreach($siteAssignations->groupBy('filiere_id') as $filiereId => $filiereAssignations)
                                        @php $filiere = $filiereAssignations->first()->filiere @endphp

                                        <div class="filiere-section"
                                             data-filiere-id="{{ $filiereId }}">

                                            <div class="px-4 py-3 bg-light">
                                                <h5 class="m-0">
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    {{ $filiere->nom ?? 'Filière non spécifiée' }}
                                                </h5>
                                            </div>

                                            <div class="list-group list-group-flush">
                                                @foreach($filiereAssignations as $assignation)
                                                    <a href="{{ route('formateur.grades.show', $assignation->id) }}"
                                                       class="list-group-item list-group-item-action border-0 py-3 px-4 assignment-item"
                                                       data-subject="{{ strtolower($assignation->subject->nom ?? '') }}">

                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="flex-grow-1">
                                                                <h5 class="mb-1 fw-semibold text-primary">
                                                                    {{ $assignation->subject->nom }}
                                                                </h5>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-users me-1"></i>
                                                                    {{
                                                                        $assignation->filiere->students
                                                                            ->where('site_id', $assignation->site_id)
                                                                            ->where('statut', 'en_cours')
                                                                            ->count()
                                                                    }} étudiants
                                                                </small>
                                                            </div>
                                                            <div class="icon-circle">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </div>
                                                        </div>

                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
