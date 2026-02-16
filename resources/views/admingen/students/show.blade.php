@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h3 class=" fw-bold text-dark mb-0">
                <i class="fas fa-user-graduate me-2" style="color: #f08b04"></i>Profil Étudiant
            </h3>
            <a href="{{ route('admingen.students.index') }}" class="btn btn-light border">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="card border-0 overflow-hidden">
            <div class="row g-0">
                <!-- Photo carrée grande avec cadre -->
                <div class="col-lg-4 bg-light p-4 d-flex align-items-center justify-content-center">
                    @if($student->photo)
                      <img src="{{ asset($student->photo) }}" class="img-fluid"
     style="width: 100%; max-width: 400px; height: auto; aspect-ratio: 1/1; object-fit: cover;">

                    @else
                        <div class=" bg-white d-flex flex-column align-items-center justify-content-center p-4 text-center"
                            style="width: 100%; max-width: 300px; height: 300px; border: 1px dashed #ddd;">
                            <i class="fas fa-user fa-4x text-secondary mb-3"></i>
                            <span class="text-dark">Photo non disponible</span>
                        </div>
                    @endif
                </div>

                <!-- Informations organisées en sections claires -->
                <div class="col-lg-8">
                    <div class="p-4 p-md-5">
                        <!-- En-tête avec nom -->
                        <div class="mb-4 pb-2 border-bottom">
                            <h2 class="h3 fw-bold text-dark">{{ $student->nom_prenom }}</h2>
                            <div class="badge text-white fs-6 " style="font-weight: bold; background: #da107b;">
                                {{ $student->matricule }}
                            </div>
                        </div>

                        <!-- Grille d'informations -->
                        <div class="row">
                            <!-- Colonne 1 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-envelope me-2 text-secondary"></i>
                                        {{ $student->email }}
                                    </p>
                                </div>
                        
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-phone me-2 text-secondary"></i>
                                        {{ $student->telephone }}
                                    </p>
                                </div>
                        
                                <div class="mb-3">
                                    <p class="mb-0 fs-5">
                                        <i class="fas fa-venus-mars me-2 text-secondary"></i>
                                        {{ $student->sexe }}
                                    </p>
                                </div>
                              
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-birthday-cake me-2 text-secondary"></i>
                                        {{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}  à  {{ $student->lieu_naissance }}
                                    </p>
                                </div>
                            </div>
                        
                            <!-- Colonne 2 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
                                        {{ $student->site->nom }}
                                    </p>
                                </div>
                        
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-graduation-cap me-2 text-secondary"></i>
                                        {{ $student->filiere->nom }}
                                    </p>
                                </div>
                        
                                <div class="mb-3">
                                    <p class="mb-3 fs-5">
                                        <i class="fas fa-calendar-alt me-2 text-secondary"></i>
                                        {{ $student->promotion->nom }} 
                                        ({{ \Carbon\Carbon::parse($student->promotion->date_debut)->translatedFormat('d F Y') }} 
                                        - 
                                        {{ \Carbon\Carbon::parse($student->promotion->date_fin)->translatedFormat('d F Y') }})
                                    </p>
                                </div>
                        
                                  <!-- Statut avec bouton de modification -->
<div class="mb-3">
    <p class="mb-3 fs-5">
        <i class="fas fa-info-circle me-2 text-secondary"></i>
        Statut :
        <span class="badge 
            @if($student->statut === 'en_cours') bg-warning 
            @elseif($student->statut === 'termine') bg-success 
            @elseif($student->statut === 'abandonne') bg-danger 
            @endif
            text-white">
            {{ ucfirst(str_replace('_', ' ', $student->statut)) }}
        </span>
    </p>

    <!-- Formulaire de changement de statut -->
<form action="{{ route('admingen.students.updateStatut', $student->id) }}" method="POST" class="d-flex flex-wrap gap-2">
        @csrf
        @method('PUT')

        <button name="statut" value="en_cours" class="btn btn-sm btn-outline-warning me-2" {{ $student->statut === 'en_cours' ? 'disabled' : '' }}>
            En cours
        </button>

        <button name="statut" value="termine" class="btn btn-sm btn-outline-success me-2" {{ $student->statut === 'termine' ? 'disabled' : '' }}>
            Terminé
        </button>

        <button name="statut" value="abandonne" class="btn btn-sm btn-outline-danger" {{ $student->statut === 'abandonne' ? 'disabled' : '' }}>
            Abandonné
        </button>
    </form>
</div>
                        
                               
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-clipboard-list me-2" style="color: #f08b04"></i>Notes et Bulletin
                </h4>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-dark" id="showGradesBtn">
                        <i class="fas fa-list"></i> Détail des notes
                    </button>
                    <button class="btn btn-sm btn-outline-dark" id="showBulletinBtn">
                        <i class="fas fa-file-alt"></i> Bulletin
                    </button>
                </div>
            </div>
        
            <!-- Conteneur des notes (visible par défaut) -->
            <div id="gradesSection">
                <!-- Navigation par trimestres -->
                <ul class="nav nav-tabs mb-4 grade-tabs" id="gradesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="trim1-tab" data-bs-toggle="tab" data-bs-target="#trim1"
                            type="button" role="tab" aria-controls="trim1" aria-selected="true">
                            <i class="fas fa-calendar-alt me-1"></i> Trimestre 1
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trim2-tab" data-bs-toggle="tab" data-bs-target="#trim2" type="button"
                            role="tab" aria-controls="trim2" aria-selected="false">
                            <i class="fas fa-calendar-alt me-1"></i> Trimestre 2
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trim3-tab" data-bs-toggle="tab" data-bs-target="#trim3" type="button"
                            role="tab" aria-controls="trim3" aria-selected="false">
                            <i class="fas fa-calendar-alt me-1"></i> Trimestre 3
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="annual-tab" data-bs-toggle="tab" data-bs-target="#annual" type="button"
                            role="tab" aria-controls="annual" aria-selected="false">
                            <i class="fas fa-chart-bar me-1"></i> Annuelle
                        </button>
                    </li>
                </ul>
        
              <!-- Contenu des onglets -->
                <div class="tab-content" id="gradesTabContent">
                    <!-- Trimestre 1 -->
                    <div class="tab-pane fade show active" id="trim1" role="tabpanel" aria-labelledby="trim1-tab">
                        <div class="grade-container">
                            <div class="grade-header">
                                <i class="fas fa-clipboard-check grade-icon"></i>
                                <h4>Notes du Trimestre 1</h4>
                            </div>
        
                            @php
                                $trim1Grades = $grades->where('term', 1);
                            @endphp
        
                            @if($trim1Grades->isEmpty())
                                <div class="grade-empty">
                                    <i class="far fa-folder-open me-2"></i>
                                    <p>Aucune note disponible pour ce trimestre.</p>
                                </div>
                            @else
                                <div class="table-responsive grade-table-container">
                                    <table class="table grade-table">
                                        <thead>
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
                                                <td class="subject-name">{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                                <td>{{ $grade->interro1 ?? '-' }}</td>
                                                <td>{{ $grade->interro2 ?? '-' }}</td>
                                                <td>{{ $grade->interro3 ?? '-' }}</td>
                                                <td>{{ $grade->devoir ?? '-' }}</td>
                                                <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                                <td class="final-grade">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
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
                        <div class="grade-container">
                            <div class="grade-header">
                                <i class="fas fa-clipboard-check grade-icon"></i>
                                <h4>Notes du Trimestre 2</h4>
                            </div>
        
                            @php
                                $trim2Grades = $grades->where('term', 2);
                            @endphp
        
                            @if($trim2Grades->isEmpty())
                                <div class="grade-empty">
                                    <i class="far fa-folder-open me-2"></i>
                                    <p>Aucune note disponible pour ce trimestre.</p>
                                </div>
                            @else
                                <div class="table-responsive grade-table-container">
                                    <table class="table grade-table">
                                        <thead>
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
                                                <td class="subject-name">{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                                <td>{{ $grade->interro1 ?? '-' }}</td>
                                                <td>{{ $grade->interro2 ?? '-' }}</td>
                                                <td>{{ $grade->interro3 ?? '-' }}</td>
                                                <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                                <td>{{ $grade->devoir ?? '-' }}</td>
                                                <td class="final-grade">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
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
                        <div class="grade-container">
                            <div class="grade-header">
                                <i class="fas fa-clipboard-check grade-icon"></i>
                                <h4>Notes du Trimestre 3</h4>
                            </div>
        
                            @php
                                $trim3Grades = $grades->where('term', 3);
                            @endphp
        
                            @if($trim3Grades->isEmpty())
                                <div class="grade-empty">
                                    <i class="far fa-folder-open me-2"></i>
                                    <p>Aucune note disponible pour ce trimestre.</p>
                                </div>
                            @else
                                <div class="table-responsive grade-table-container">
                                    <table class="table table-hover grade-table">
                                        <thead>
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
                                                <td class="subject-name">{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                                <td>{{ $grade->interro1 ?? '-' }}</td>
                                                <td>{{ $grade->interro2 ?? '-' }}</td>
                                                <td>{{ $grade->interro3 ?? '-' }}</td>
                                                <td>{{ $grade->devoir ?? '-' }}</td>
                                                <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                                <td class="final-grade">{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
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
                        <div class="annual-summary">
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
                                    <div class="card term-card">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Moyennes par Trimestre</h5>
                                            <ul class="term-list">
                                                <li class="term-item">
                                                    Trimestre 1
                                                    <span class="term-badge">{{ $moyTrim1 ? number_format($moyTrim1, 2) : '-' }}/20</span>
                                                </li>
                                                <li class="term-item">
                                                    Trimestre 2
                                                    <span class="term-badge">{{ $moyTrim2 ? number_format($moyTrim2, 2) : '-' }}/20</span>
                                                </li>
                                                <li class="term-item">
                                                    Trimestre 3
                                                    <span class="term-badge">{{ $moyTrim3 ? number_format($moyTrim3, 2) : '-' }}/20</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card annual-card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><i class="fas fa-trophy me-2"></i>Moyenne Générale</h5>
                                            <div class="annual-grade">
                                                {{ $moyAnnuelle ? number_format($moyAnnuelle, 2) : 'N/A' }}<small>/20</small>
                                            </div>
                                            <p class="annual-description">Moyenne des trois trimestres</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Conteneur du bulletin (caché par défaut) -->
            <div id="bulletinSection" style="display: none;">
                <div class="bulletin-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Bulletin Scolaire - Trimestre {{ $currentTerm ?? 1 }}</h5>
                        <div class="btn-group">
                            @foreach([1, 2, 3] as $term)
                                <button class="btn btn-sm {{ ($currentTerm ?? 1) == $term ? 'btn-dark' : 'btn-outline-dark' }} change-term" data-term="{{ $term }}">
                                    T{{ $term }}
                                </button>
                            @endforeach
                            <button onclick="window.print()" class="btn btn-dark btn-sm">
                                <i class="fas fa-file-pdf me-1"></i> Exporter
                            </button>
                        </div>
                    </div>
        
                    <div class="bulletin-content">
                        <div class="bulletin-header">
                            <h1 class="text-white">BULLETIN SCOLAIRE</h1>
                            <h2 class="text-white">ÉCOLE ET COLLÈGE DE VILLE</h2>
                        </div>
        
                        <div class="bulletin-info">
                            <div><strong>Nom de l'élève :</strong> {{ $student->nom_prenom }}</div>
                            <div><strong>Classe :</strong> {{ $student->filiere->nom }}</div>
                            <div><strong>Année scolaire :</strong> {{ date('Y') }}-{{ date('Y')+1 }}</div>
                            <div><strong>Trimestre :</strong> {{ $currentTerm ?? 1 }}</div>
                        </div>
        
                        <div class="bulletin-separator"></div>
        
                        @php
                            $bulletinGrades = $grades->where('term', $currentTerm ?? 1);
                        @endphp
        
                        @if($bulletinGrades->isNotEmpty())
                            <div class="bulletin-grades">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>MATIÈRE</th>
                                            <th>MOY. INTERRO</th>
                                            <th>MOY. DEVOIR</th>
                                            <th>MOY. FINALE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bulletinGrades as $grade)
                                        <tr>
                                            <td>{{ $grade->assignation->subject->nom ?? 'Matière inconnue' }}</td>
                                            <td>{{ $grade->moy_interro ? number_format($grade->moy_interro, 2) : '-' }}</td>
                                            <td>{{ $grade->moy_devoir ? number_format($grade->moy_devoir, 2) : '-' }}</td>
                                            <td>{{ $grade->moy_finale ? number_format($grade->moy_finale, 2) : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
        
                            @php
                                $moyenne = $bulletinGrades->avg('moy_finale');
                                if ($moyenne >= 16) $mention = 'Très bien';
                                elseif ($moyenne >= 14) $mention = 'Bien';
                                elseif ($moyenne >= 12) $mention = 'Assez bien';
                                elseif ($moyenne >= 10) $mention = 'Passable';
                                else $mention = 'Échec';
                            @endphp
        
                            <div class="bulletin-footer">
                                <div><strong>Moyenne Générale :</strong> {{ $moyenne ? number_format($moyenne, 2) : '-' }}</div>
                                <div><strong>Mention :</strong> {{ $mention }}</div>
                            </div>
        
                            <div class="bulletin-notation">
                                <h4>SYSTÈME DE NOTATION :</h4>
                                <ul>
                                    <li>&ge; 16 Très bien</li>
                                    <li>&ge; 14 Bien</li>
                                    <li>&ge; 12 Assez bien</li>
                                    <li>&ge; 10 Passable</li>
                                    <li>&lt; 10 Échec</li>
                                </ul>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Aucune donnée disponible pour le bulletin de ce trimestre.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Style général */
        .grade-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            border-radius: 0.25rem 0.25rem 0 0;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }
        
        .grade-tabs .nav-link.active {
            color: #fff;
            background-color: #f08b04;
            border-color: #f08b04;
        }
        
        .grade-tabs .nav-link:hover:not(.active) {
            color: #f08b04;
            border-color: #f08b04;
        }
        
        /* Conteneur des notes */
        .grade-container {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            border-left: 4px solid #f08b04;
        }
        
        .grade-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        
        .grade-icon {
            font-size: 1.5rem;
            color: #f08b04;
            margin-right: 1rem;
        }
        
        .grade-empty {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
        
        /* Tableau des notes */
        .grade-table {
            margin-bottom: 0;
        }
        
        .grade-table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .subject-name {
            font-weight: 500;
            color: #212529;
        }
        
        .final-grade {
            font-weight: 600;
            color: #f08b04;
        }
        
        /* Récapitulatif annuel */
        .annual-summary {
            border-radius: 0.5rem;
            padding: 1.5rem;
            border-left: 4px solid #f08b04;
        }
        
        .term-list {
            list-style: none;
            padding: 0;
        }
        
        .term-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
        }
        
        .term-badge {
            background-color: #f08b04;
            color: white;
            padding: 0.35rem 0.75rem;
            font-weight: 500;
        }
        
        .annual-card {
            border: none;
            background: linear-gradient(135deg, rgba(167, 40, 131, 0.1) 0%, rgba(240, 139, 4, 0.1) 100%);
        }
        
        .annual-grade {
            font-size: 3rem;
            font-weight: 700;
            color: #da107b;
            margin: 1rem 0;
        }
        
        .annual-grade small {
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .annual-description {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
    
        /* Bulletin Scolaire */
        .bulletin-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            overflow: hidden;
        }
    
        .bulletin-content {
            padding: 1.5rem;
        }
    
        .bulletin-header {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }
    
        .bulletin-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
    
        .bulletin-header h2 {
            margin: 0.5rem 0 0;
            font-size: 1rem;
            font-weight: 400;
        }
    
        .bulletin-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
    
        .bulletin-info div {
            flex: 1 1 45%;
            min-width: 200px;
            font-size: 0.95rem;
        }
    
        .bulletin-separator {
            border-top: 2px solid #dee2e6;
            margin: 1.5rem 0;
        }
    
        .bulletin-grades {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
    
        .bulletin-grades table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
    
        .bulletin-grades th {
            background-color: #343a40;
            color: white;
            padding: 0.75rem;
            text-align: center;
            font-weight: 500;
        }
    
        .bulletin-grades td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            text-align: center;
        }
    
        .bulletin-grades tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
    
        .bulletin-footer {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            font-weight: 500;
            border-top: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }
    
        .bulletin-notation h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
    
        .bulletin-notation ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }
    
        .bulletin-notation li {
            padding: 0.25rem 0;
        }
    
        /* Responsive */
        @media (max-width: 768px) {
            .grade-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 5px;
            }
            
            .grade-tabs .nav-item {
                flex: 0 0 auto;
            }
            
            .grade-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
            
            /* Conteneur principal */
            .grade-container, .annual-summary {
                padding: 1rem;
            }
            
            /* En-têtes */
            .grade-header, .annual-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .grade-header h4, .annual-header h4 {
                margin-top: 0.5rem;
                font-size: 1.25rem;
            }
            
            /* Tableaux */
            .grade-table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-right: -1rem;
                margin-left: -1rem;
                width: calc(100% + 2rem);
            }
            
            .grade-table {
                min-width: 700px;
            }
            
            .grade-table th, .grade-table td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
            
            /* Bulletin */
            .bulletin-content {
                padding: 1rem;
            }
            
            .bulletin-header {
                padding: 1rem;
                margin: -1rem -1rem 1rem -1rem;
            }
            
            .bulletin-header h1 {
                font-size: 1.5rem;
            }
            
            .bulletin-header h2 {
                font-size: 0.9rem;
            }
            
            .bulletin-info {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .bulletin-info div {
                flex: 1 1 100%;
                min-width: auto;
            }
            
            .bulletin-grades th, 
            .bulletin-grades td {
                padding: 0.5rem;
            }
            
            .bulletin-footer {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    
        @media (max-width: 576px) {
            .grade-tabs .nav-link {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }
            
            .grade-header h4, .annual-header h4 {
                font-size: 1.1rem;
            }
            
            .grade-icon, .annual-icon {
                font-size: 1.2rem;
            }
            
            .annual-grade {
                font-size: 2rem;
            }
        }
    
        @media print {
            .bulletin-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .bulletin-header {
                background-color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bulletin-grades th {
                background-color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            #gradesSection, .no-print {
                display: none !important;
            }
            
            #bulletinSection {
                display: block !important;
            }
        }
    </style>
    
@endsection