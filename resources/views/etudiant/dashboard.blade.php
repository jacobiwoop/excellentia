@extends('layouts.stu')

@section('content')
<style>
    /* Styles PC (votre version originale inchangée) */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f5;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        padding: 2rem;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .mb-5 {
        margin-bottom: 3rem;
    }

    .fw-bold {
        font-weight: bold;
    }

    .text-dark {
        color: #212529;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .bg-primary {
        background-color: #f08b04;
    }

    .bg-opacity-10 {
        opacity: 0.1;
    }

    .text-white {
        color: white;
    }

    .fs-6 {
        font-size: 1rem;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.35rem;
    }

    .border-0 {
        border: 0;
    }

    .overflow-hidden {
        overflow: hidden;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .g-0 {
        margin-right: 0;
        margin-left: 0;
    }

    .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .bg-light {
        background-color: #f8f9fa;
    }

    .p-4 {
        padding: 1.5rem;
    }

    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-content-center {
        justify-content: center;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    /* Nouveaux styles pour les alertes */
    .alert-custom {
        background-color: #f8f9fa;
        border-left: 4px solid #f08b04;
        border-radius: 0;
        border-top: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }

    .alert-custom h4 {
        color: #f08b04;
    }

    .alert-custom i {
        color: #f08b04;
    }

    .alert-success-custom {
        background-color: #f8f9fa;
        border-left: 4px solid #28a745;
        border-radius: 0;
        border-top: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }

    .alert-success-custom h4 {
        color: #28a745;
    }

    .alert-success-custom i {
        color: #28a745;
    }

    /* Styles Mobile uniquement */
    @media (max-width: 767.98px) {
        .container {
            padding: 1rem;
        }

        .d-flex.justify-content-between.align-items-center.mb-5 {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .card.border-0.overflow-hidden .row.g-0 {
            flex-direction: column;
        }

        .col-lg-4, .col-lg-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .bg-light.p-4 {
            padding: 1rem;
        }

        .p-4.p-md-5 {
            padding: 1.5rem;
        }

        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.5rem;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        .table-responsive {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto;
        }

        .annual-summary .row {
            flex-direction: column;
            gap: 1rem;
        }

        .fs-5 {
            font-size: 1rem !important;
        }
        
        .h3 {
            font-size: 1.5rem;
        }
        
        .h4 {
            font-size: 1.3rem;
        }
        
        .display-4 {
            font-size: 2rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .alert h4 {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container py-5">
    <!-- Section Profil Étudiant -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-user-graduate me-2" style="color: #f08b04"></i>Mon Profil
        </h3>
        <div class="badge text-white fs-6" style="font-weight: bold; background: #da107b">
            Connecté en tant qu'Étudiant
        </div>
    </div>

    <div class="card border-0 overflow-hidden">
        <div class="row g-0">
            <!-- Photo de profil -->
            <div class="col-lg-4 bg-light p-4 d-flex align-items-center justify-content-center">
                @if(auth()->guard('student')->user()->photo)
                    <img src="{{ asset('storage/' . auth()->guard('student')->user()->photo) }}" 
                         class="img-fluid" 
                         style="width: 100%; max-width: 400px; height: auto; aspect-ratio: 1/1; object-fit: cover;">
                @else
                    <div class="bg-white d-flex flex-column align-items-center justify-content-center p-4 text-center" 
                         style="width: 100%; max-width: 300px; height: 300px; border: 1px dashed #ddd;">
                        <i class="fas fa-user fa-4x text-secondary mb-3"></i>
                        <span class="text-dark">Photo non disponible</span>
                    </div>
                @endif
            </div>
            
            <!-- Informations -->
            <div class="col-lg-8">
                <div class="p-4 p-md-5">
                    <!-- En-tête -->
                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="h3 fw-bold text-dark">{{ auth()->guard('student')->user()->nom_prenom }}</h2>
                        <div class="badge text-white fs-6" style="font-weight: bold; background: #da107b">
                            {{ auth()->guard('student')->user()->matricule }}
                        </div>
                    </div>
                    
                    <!-- Grille d'informations -->
                    <div class="row">
                        <!-- Colonne 1 -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="mb-3 fs-5">
                                    <i class="fas fa-envelope me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->email ?? 'Non renseigné' }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-3 fs-5">
                                    <i class="fas fa-phone me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->telephone }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-0 fs-5">
                                    <i class="fas fa-venus-mars me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->sexe == 'M' ? 'Masculin' : 'Féminin' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Colonne 2 -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="mb-3 fs-5">
                                    <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->site->nom }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-3 fs-5">
                                    <i class="fas fa-graduation-cap me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->filiere->nom }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-0 fs-5">
                                    <i class="fas fa-calendar-alt me-2 text-secondary"></i>
                                    {{ auth()->guard('student')->user()->promotion->nom }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Notes -->
    <div class="mt-5">
        <h4 class="fw-bold text-dark mb-4">
            <i class="fas fa-clipboard-list me-2" style="color: #f08b04"></i>Mes Notes
        </h4>
    
        <!-- Navigation par trimestres -->
        <ul class="nav nav-tabs mb-4" id="gradesTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="trim1-tab" data-bs-toggle="tab" data-bs-target="#trim1" type="button" role="tab" aria-controls="trim1" aria-selected="true">
                    Trimestre 1
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="trim2-tab" data-bs-toggle="tab" data-bs-target="#trim2" type="button" role="tab" aria-controls="trim2" aria-selected="false">
                    Trimestre 2
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="trim3-tab" data-bs-toggle="tab" data-bs-target="#trim3" type="button" role="tab" aria-controls="trim3" aria-selected="false">
                    Trimestre 3
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="annual-tab" data-bs-toggle="tab" data-bs-target="#annual" type="button" role="tab" aria-controls="annual" aria-selected="false">
                    Moyenne Annuelle
                </button>
            </li>
        </ul>
    
        <!-- Contenu des onglets -->
        <div class="tab-content" id="gradesTabContent">
            <!-- Trimestre 1 -->
            <div class="tab-pane fade show active" id="trim1" role="tabpanel" aria-labelledby="trim1-tab">
                <div class="alert alert-custom">
                    <i class="fas fa-info-circle me-2"></i>
                    <h4>Notes du Trimestre 1</h4>
                    
                    @php
                        $trim1Grades = $grades->where('term', 1);
                    @endphp
    
                    @if($trim1Grades->isEmpty())
                        <p class="mb-0">Aucune note disponible pour ce trimestre.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matière</th>
                                        <th>Evaluation 1</th>
                                        <th>Evaluation 2</th>
                                        <th>Evaluation 3</th>
                                        <th>Moy. Evaluation</th>
                                        <th>Devoir</th>
                                        <th>Moy. Trimestre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trim1Grades as $grade)
                                    <tr>
                                        <td>{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                        <td>{{ $grade->interro1 ?? '-' }}</td>
                                        <td>{{ $grade->interro2 ?? '-' }}</td>
                                        <td>{{ $grade->interro3 ?? '-' }}</td>
                                        <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                        <td>{{ $grade->devoir ?? '-' }}</td>
                                        <td class="fw-bold">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
    
            <!-- Trimestre 2 -->
            <div class="tab-pane fade" id="trim2" role="tabpanel" aria-labelledby="trim2-tab">
                <div class="alert alert-custom">
                    <i class="fas fa-info-circle me-2"></i>
                    <h4>Notes du Trimestre 2</h4>
                    
                    @php
                        $trim2Grades = $grades->where('term', 2);
                    @endphp
    
                    @if($trim2Grades->isEmpty())
                        <p class="mb-0">Aucune note disponible pour ce trimestre.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matière</th>
                                        <th>Evaluation 1</th>
                                        <th>Evaluation 2</th>
                                        <th>Evaluation 3</th>
                                        <th>Moy. Evaluation</th>
                                        <th>Devoir</th>
                                        <th>Moy. Trimestre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trim2Grades as $grade)
                                    <tr>
                                        <td>{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                        <td>{{ $grade->interro1 ?? '-' }}</td>
                                        <td>{{ $grade->interro2 ?? '-' }}</td>
                                        <td>{{ $grade->interro3 ?? '-' }}</td>
                                        <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                        <td>{{ $grade->devoir ?? '-' }}</td>
                                        <td class="fw-bold">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
    
            <!-- Trimestre 3 -->
            <div class="tab-pane fade" id="trim3" role="tabpanel" aria-labelledby="trim3-tab">
                <div class="alert alert-custom">
                    <i class="fas fa-info-circle me-2"></i>
                    <h4>Notes du Trimestre 3</h4>
                    
                    @php
                        $trim3Grades = $grades->where('term', 3);
                    @endphp
    
                    @if($trim3Grades->isEmpty())
                        <p class="mb-0">Aucune note disponible pour ce trimestre.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matière</th>
                                        <th>Evaluation 1</th>
                                        <th>Evaluation 2</th>
                                        <th>Evaluation 3</th>
                                        <th>Moy. Evaluation</th>
                                        <th>Devoir</th>
                                        <th>Moy. Trimestre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trim3Grades as $grade)
                                    <tr>
                                        <td>{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                        <td>{{ $grade->interro1 ?? '-' }}</td>
                                        <td>{{ $grade->interro2 ?? '-' }}</td>
                                        <td>{{ $grade->interro3 ?? '-' }}</td>
                                        <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                        <td>{{ $grade->devoir ?? '-' }}</td>
                                        <td class="fw-bold">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
    
            <!-- Moyenne Annuelle -->
            <div class="tab-pane fade" id="annual" role="tabpanel" aria-labelledby="annual-tab">
                <div class="alert alert-success-custom">
                    <i class="fas fa-chart-line me-2"></i>
                    <h4>Récapitulatif Annuel</h4>
                    
                    @php
                        // Calcul des moyennes par trimestre
                        $moyTrim1 = $trim1Grades->avg('moy_finale');
                        $moyTrim2 = $trim2Grades->avg('moy_finale');
                        $moyTrim3 = $trim3Grades->avg('moy_finale');
                        
                        // Moyenne annuelle
                        $validTerms = collect([$moyTrim1, $moyTrim2, $moyTrim3])->filter();
                        $moyAnnuelle = $validTerms->isNotEmpty() ? $validTerms->avg() : null;
                    @endphp
    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Moyennes par Trimestre</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Trimestre 1
                                            <span class="badge bg-primary rounded-pill">{{ $moyTrim1 ? number_format($moyTrim1, 2) : '-' }}/20</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Trimestre 2
                                            <span class="badge bg-primary rounded-pill">{{ $moyTrim2 ? number_format($moyTrim2, 2) : '-' }}/20</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Trimestre 3
                                            <span class="badge bg-primary rounded-pill">{{ $moyTrim3 ? number_format($moyTrim3, 2) : '-' }}/20</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Moyenne Générale</h5>
                                    <div class="display-4 fw-bold text-success">
                                        {{ $moyAnnuelle ? number_format($moyAnnuelle, 2) : 'N/A' }}/20
                                    </div>
                                    <p class="text-muted mt-2">Moyenne des trois trimestres</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script pour améliorer l'expérience mobile
    document.addEventListener('DOMContentLoaded', function() {
        // Rend les onglets scrollables sur mobile
        const tabs = document.querySelector('.nav-tabs');
        if (tabs && window.innerWidth < 768) {
            tabs.scrollLeft = 0;
        }
    });
</script>
@endpush

@endsection