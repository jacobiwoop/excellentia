@extends('layouts.dash')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Simple et Élégant -->
    <div class="dashboard-header p-4 bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 fw-bold" style="color: #2d3436;">
                    <i class="fas fa-chart-line me-2" style="color: #ff9320;"></i>
                    Tableau de Bord
                </h1>
                <p class="text-muted mb-0">Aperçu global de votre plateforme</p>
            </div>
            <div class="date-display">
                <i class="fas fa-calendar-alt me-2" style="color: #ff9320;"></i>
                <span>
                    @php
                        \Carbon\Carbon::setLocale('fr');
                        echo now()->translatedFormat('l j F Y');
                    @endphp
                </span>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-4">
        <!-- Statistiques Principales -->
        <div class="row g-3 mb-4">
            <!-- Carte Étudiants -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="stat-icon stat-icon-orange">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>12%</span>
                            </div>
                        </div>
                        
                        <h3 class="stat-number">{{ number_format($studentCount, 0, ',', ' ') }}</h3>
                        <p class="stat-label">Étudiants Inscrits</p>
                        
                        <!-- Statuts compacts -->
                        <div class="stat-status">
                            <div class="status-item">
                                <span class="status-dot status-dot-success"></span>
                                <span class="status-text">En cours</span>
                                <strong class="status-value">{{ $studentsEnCours }}</strong>
                            </div>
                            <div class="status-item">
                                <span class="status-dot status-dot-danger"></span>
                                <span class="status-text">Abandonnés</span>
                                <strong class="status-value">{{ $studentsAbandonnes }}</strong>
                            </div>
                            <div class="status-item">
                                <span class="status-dot status-dot-info"></span>
                                <span class="status-text">Terminés</span>
                                <strong class="status-value">{{ $studentsTermines }}</strong>
                            </div>
                        </div>
                        
                        <a href="{{ route('superadmin.students.index') }}" class="stat-link">
                            Voir tous les étudiants <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte Formateurs -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="stat-icon stat-icon-pink">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-badge">
                                <i class="fas fa-check-circle"></i> Actifs
                            </div>
                        </div>
                        
                        <h3 class="stat-number">{{ $formateurCount }}</h3>
                        <p class="stat-label">Formateurs</p>
                        
                        <div class="stat-mini-info">
    @php
        $akpakpaFormateurs = \App\Models\Assignation::where('site_id', 1)->distinct('formateur_id')->count('formateur_id');
        $calaviFormateurs = \App\Models\Assignation::where('site_id', 2)->distinct('formateur_id')->count('formateur_id');
    @endphp
    <div class="stat-mini-grid">
        <div class="mini-stat">
            <span class="mini-label">Akpakpa</span>
            <strong class="mini-value">{{ $akpakpaFormateurs }}</strong>
        </div>
        <div class="mini-stat">
            <span class="mini-label">Calavi</span>
            <strong class="mini-value">{{ $calaviFormateurs }}</strong>
        </div>
    </div>
</div>
                        
                        <a href="{{ route('superadmin.formateurs.index') }}" class="stat-link">
                            Gérer les formateurs <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte Paiements -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="stat-icon stat-icon-dark">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>28%</span>
                            </div>
                        </div>
                        
                        <h3 class="stat-number">14</h3>
                        <p class="stat-label">Paiements ce mois</p>
                        
                        <div class="stat-mini-grid">
                            <div class="mini-stat">
                                <span class="mini-label">En attente</span>
                                <strong class="mini-value">5</strong>
                            </div>
                            <div class="mini-stat">
                                <span class="mini-label">Validés</span>
                                <strong class="mini-value">9</strong>
                            </div>
                        </div>
                        
                        <a href="#" class="stat-link">
                            Voir les rapports <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte Administrateurs -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="stat-icon stat-icon-gradient">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-badge-success">
                                <i class="fas fa-check"></i> Actifs
                            </div>
                        </div>
                        
                        <h3 class="stat-number">{{ $adminCount }}</h3>
                        <p class="stat-label">Administrateurs</p>
                        
                        <div class="admin-avatars">
                            <img src="https://ui-avatars.com/api/?name=Admin+1&background=da107b&color=fff&size=32" alt="Admin 1">
                            <img src="https://ui-avatars.com/api/?name=Admin+2&background=ff9320&color=fff&size=32" alt="Admin 2">
                            <img src="https://ui-avatars.com/api/?name=Admin+3&background=333&color=fff&size=32" alt="Admin 3">
                            @if($adminCount > 3)
                                <span class="avatar-more">+{{ $adminCount - 3 }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('superadmin.admins.index') }}" class="stat-link">
                            Gérer les comptes <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Sites Simple -->
        <div class="section-header mb-3">
            <h2 class="section-title">
                <i class="fas fa-map-marker-alt me-2"></i>
                Nos Sites
            </h2>
        </div>

        <div class="row g-3">
            @foreach($sites as $site)
                @php
                    $colors = [
                        'Akpakpa' => ['primary' => '#da107b', 'light' => '#fff0f6'],
                        'Calavi' => ['primary' => '#ff9320', 'light' => '#fff8f0'],
                    ];
                    $color = $colors[$site->nom] ?? ['primary' => '#333', 'light' => '#f5f5f5'];
                    
                    // Compter les formateurs pour ce site
                    $formateursSite = \App\Models\Assignation::where('site_id', $site->id)
                        ->distinct('formateur_id')
                        ->count('formateur_id');
                @endphp
                
                <div class="col-lg-4 col-md-6">
                    <div class="site-card">
                        <div class="site-header" style="border-left: 4px solid {{ $color['primary'] }};">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="site-name" style="color: {{ $color['primary'] }};">
                                    <i class="fas fa-building me-2"></i>{{ $site->nom }}
                                </h3>
                                <span class="site-badge" style="background-color: {{ $color['light'] }}; color: {{ $color['primary'] }};">
                                    Site
                                </span>
                            </div>
                        </div>
                        
                        <div class="site-body">
                            <div class="site-stat-row">
                                <div class="site-stat-item">
                                    <i class="fas fa-users" style="color: {{ $color['primary'] }};"></i>
                                    <span class="site-stat-text">Étudiants</span>
                                    <strong class="site-stat-number">{{ number_format($site->students_count, 0, ',', ' ') }}</strong>
                                </div>
                            </div>
                            
                            <div class="site-stat-row">
                                <div class="site-stat-item">
                                    <i class="fas fa-chalkboard-teacher" style="color: {{ $color['primary'] }};"></i>
                                    <span class="site-stat-text">Formateurs</span>
                                    <strong class="site-stat-number">{{ $formateursSite }}</strong>
                                </div>
                            </div>
                            
                            <a href="{{ route('superadmin.sites.show', $site->id) }}" 
                               class="btn-site" 
                               style="background-color: #da107b;">
                                Voir les détails <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


<style>
/* Variables */
:root {
    --color-orange: #ff9320;
    --color-pink: #da107b;
    --color-dark: #333;
    --shadow-card: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Header */
.dashboard-header {
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.date-display {
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    color: #495057;
    font-weight: 500;
    border: 1px solid #e9ecef;
}

/* Cartes Statistiques */
.stat-card {
    background: white;
    border-radius: 10px;
    box-shadow: var(--shadow-card);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
    border-color: #dee2e6;
}

.stat-card-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Icônes */
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.stat-icon-orange {
    background: linear-gradient(135deg, #ff9320 0%, #ffb366 100%);
}

.stat-icon-pink {
    background: linear-gradient(135deg, #da107b 0%, #ff6b9d 100%);
}

.stat-icon-dark {
    background: linear-gradient(135deg, #333 0%, #555 100%);
}

.stat-icon-gradient {
    background: linear-gradient(135deg, #da107b 0%, #ff9320 100%);
}

/* Trends et Badges */
.stat-trend {
    background: #d4edda;
    color: #28a745;
    padding: 0.3rem 0.6rem;
    border-radius: 5px;
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.stat-badge {
    background: #f8f9fa;
    color: #495057;
    padding: 0.3rem 0.6rem;
    border-radius: 5px;
    font-size: 0.7rem;
    font-weight: 500;
    border: 1px solid #e9ecef;
}

.stat-badge-success {
    background: #d4edda;
    color: #28a745;
    padding: 0.3rem 0.6rem;
    border-radius: 5px;
    font-size: 0.7rem;
    font-weight: 500;
    border: 1px solid #c3e6cb;
}

/* Nombres */
.stat-number {
    font-size: 1.85rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0.5rem 0 0.25rem 0;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
}

/* Statuts Compacts */
.stat-status {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 0.6rem;
    margin-bottom: 0.75rem;
    border: 1px solid #e9ecef;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0;
}

.status-item:not(:last-child) {
    border-bottom: 1px dashed #dee2e6;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-dot-success { background: #28a745; }
.status-dot-danger { background: #dc3545; }
.status-dot-info { background: #007bff; }

.status-text {
    flex: 1;
    font-size: 0.75rem;
    color: #6c757d;
}

.status-value {
    font-size: 0.85rem;
    color: #2d3436;
}

/* Mini Stats */
.stat-mini-info {
    margin-bottom: 0.75rem;
}

.progress-bar-simple {
    height: 5px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.4rem;
}

.progress-fill-simple {
    height: 100%;
    border-radius: 10px;
    transition: width 0.6s ease;
}

.stat-mini-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.mini-stat {
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 6px;
    text-align: center;
    border: 1px solid #e9ecef;
}

.mini-label {
    display: block;
    font-size: 0.7rem;
    color: #6c757d;
    margin-bottom: 0.2rem;
}

.mini-value {
    font-size: 1rem;
    font-weight: 700;
    color: #2d3436;
}

/* Avatars */
.admin-avatars {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
}

.admin-avatars img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.avatar-more {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 600;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Liens - TOUS AU MÊME NIVEAU */
.stat-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #495057;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-top: auto;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.stat-link:hover {
    color: #ff9320;
}

/* Section Header */
.section-header {
    padding: 0 0.5rem;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0;
}

/* Cartes Sites */
.site-card {
    background: white;
    border-radius: 10px;
    box-shadow: var(--shadow-card);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: hidden;
}

.site-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
    border-color: #dee2e6;
}

.site-header {
    padding: 1.25rem;
    background: #fafafa;
    border-bottom: 1px solid #e9ecef;
}

.site-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
}

.site-badge {
    padding: 0.25rem 0.7rem;
    border-radius: 5px;
    font-size: 0.7rem;
    font-weight: 500;
}

.site-body {
    padding: 1.25rem;
}

.site-stat-row {
    padding: 0.7rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 0.6rem;
    border: 1px solid #e9ecef;
}

.site-stat-item {
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.site-stat-item i {
    font-size: 1rem;
}

.site-stat-text {
    flex: 1;
    color: #6c757d;
    font-size: 0.85rem;
}

.site-stat-number {
    font-size: 1rem;
    color: #2d3436;
}

.btn-site {
    display: block;
    width: 100%;
    padding: 0.7rem;
    border: none;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
}

.btn-site:hover {
    color: white;
    opacity: 0.9;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        padding: 1rem !important;
    }
    
    .date-display {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
    
    .stat-number {
        font-size: 1.6rem;
    }
    
    .stat-icon {
        width: 42px;
        height: 42px;
        font-size: 1.1rem;
    }
}
</style>
