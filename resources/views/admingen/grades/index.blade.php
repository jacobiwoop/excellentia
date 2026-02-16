@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Filtres améliorés -->
    <div class="card  mb-4 border-0">
        <div class="card-body p-4">
            <h5 class="mb-3 text-secondary fw-semibold">
                <i class="fas fa-filter me-2"></i>Filtres des notes
            </h5>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Filière</label>
                    <select name="filiere_id" class="form-select form-select-sm">
                        <option value="">Toutes les filières</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Site</label>
                    <select name="site_id" class="form-select form-select-sm">
                        <option value="">Tous les sites</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Matière</label>
                    <select name="subject_id" class="form-select form-select-sm">
                        <option value="">Toutes les matières</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Trimestre</label>
                    <select name="term" class="form-select form-select-sm">
                        <option value="">Tous les trimestres</option>
                        <option value="1" {{ request('term') == 1 ? 'selected' : '' }}>Trimestre 1</option>
                        <option value="2" {{ request('term') == 2 ? 'selected' : '' }}>Trimestre 2</option>
                        <option value="3" {{ request('term') == 3 ? 'selected' : '' }}>Trimestre 3</option>
                    </select>
                </div>

                <div class="col-md-12 mt-2">
                    <button class="btn btn-sm btn-primary px-3">
                        <i class="fas fa-search me-1"></i> Afficher
                    </button>
                    <a href="{{ route('admingen.grades.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des notes -->
    <div class="card  border-0">
       
        
        <div class="card-body p-0">
            @if($grades->isEmpty())
            <div class="alert alert-info m-4">
                <i class="fas fa-info-circle me-2"></i>Aucune note enregistrée pour le moment.
            </div>
            @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-nowrap text-white">Étudiant</th>
                            <!--<th class="text-nowrap text-white">Filière</th>  -->  
                            <!--<th class="text-nowrap text-white">Site</th>  -->    
                            <th class="text-nowrap text-white">Matière</th>
                           
                            <th class="text-nowrap text-white text-center">Evaluation 1</th>
                            <th class="text-nowrap text-white text-center">Evaluation 2</th>
                            <th class="text-nowrap text-white text-center">Evaluation 3</th>
                            <th class="text-nowrap text-white text-center">Moy. Evaluation</th>
                            <th class="text-nowrap text-white text-center">Devoir</th>
                            <th class="text-nowrap text-white text-center">Moy. Finale</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                        <tr class="border-top border-light">
                            <!-- Étudiant -->
                            <td class="fw-medium">{{ $grade->student->nom_prenom ?? 'Inconnu' }}</td>

                            <!-- <td class="text-muted small">{{ $grade->student->filiere->nom ?? '-' }}</td> -->
                            

                            <!--  <td class="text-muted small">{{ $grade->student->site->nom ?? '-' }}</td>
 -->
                           
                            <!-- Matière -->
                            <td>
                                @if($grade->assignation && $grade->assignation->subject)
                                    <span class="fw-medium">{{ $grade->assignation->subject->nom }}</span>
                                @else
                                    <span class="text-danger small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Non assignée
                                    </span>
                                @endif
                            </td>

                           

                            <!-- Notes -->
                            <td class="text-center">{{ $grade->interro1 ?? '-' }}</td>
                            <td class="text-center">{{ $grade->interro2 ?? '-' }}</td>
                            <td class="text-center">{{ $grade->interro3 ?? '-' }}</td>
                            <td class="text-center fw-bold {{ $grade->moy_interro >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}
                            </td>
                            <td class="text-center">{{ $grade->devoir ?? '-' }}</td>
                            <td class="text-center fw-bold {{ $grade->moy_finale >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection