@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header brut -->
    <div class="dashboard-header p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 fw-bold text-gradient">Tableau de Bord</h1>
                <p class="text-muted mb-0">Aperçu global de votre plateforme</p>
            </div>
            <div class="date-display bg-light-primary p-2 rounded">
                <i class="fas fa-calendar-alt me-2" style="color: orange;"></i>
                
                <span id="current-date">
                    @php
                        \Carbon\Carbon::setLocale('Fr');
                        echo now()->translatedFormat('j F Y');
                    @endphp
                </span>
            </div>
        </div>
    </div>

    <!-- Cartes brutes -->
    <div class="container-fluid px-4">
        <!-- Section Statistiques - Version élégante -->
        <div class="row g-4 mb-4">
            <!-- Carte Étudiants -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 h-100" style="background-color: #fff5f7; border-left: 4px solid #ff9320;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase mb-2" style="color: #ff9320;">Étudiants</h6>
                                <h2 class="mb-0 fw-bold" style="color: #333;">{{ number_format($studentCount, 0, ',', ' ') }}</h2>
                                <div class="mt-3">
                                  
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-circle" style="color: #ff9320;">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed rgba(0,0,0,0.1);">
                        <a href="{{ route('admingen.students.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none" style="color: #333;">
                            <span>Voir détails</span>
                            <i class="fas fa-chevron-right" style="color: #ff9320;"></i>
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- Carte Formations -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background-color: #fff; border-left: 4px solid #da107b;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase mb-2" style="color: #da107b;">Formateurs</h6>
                                <h2 class="mb-0 fw-bold" style="color: #333;">{{ $formateurCount }}</h2>
                                <div class="mt-3">
                                  
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-circle" style="color: #da107b;">
                                <i class="fas fa-graduation-cap fa-lg"></i>
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed rgba(0,0,0,0.1);">
                        <a href="{{ route('admingen.formateurs.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none" style="color: #333;">
                            <span>Gérer les Formateurs</span>
                            <i class="fas fa-chevron-right" style="color: #da107b;"></i>
                        </a>
                    </div>
                </div>
            </div>
    
    
            <!-- Carte Administrateurs -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background-color: #fff5f7; border-left: 4px solid #da107b;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase mb-2" style="color: #da107b;">Administrateurs</h6>
                                <h2 class="mb-0 fw-bold" style="color: #333;">{{ $adminCount }}</h2>
                            </div>
                            <div class="bg-white p-3 rounded-circle" style="color: #da107b;">
                                <i class="fas fa-user-shield fa-lg"></i>
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed rgba(0,0,0,0.1);">
                        <a href="{{ route('admingen.admins.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none" style="color: #333;">
                            <span>Gérer les comptes</span>
                            <i class="fas fa-chevron-right" style="color: #da107b;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   <!-- Section Sites - Version élégante -->
<div class="mb-4">
    <div class="p-3 mb-3">
        <h2 class="h4 fw-bold mb-0 d-flex align-items-center">
            <i class="fas fa-map-marker-alt me-2" style="color: #ff9320;"></i>
            <span style="color: #333;">NOS SITES</span>
        </h2>
    </div>

    <div class="row g-4">
        @foreach($sites as $site)
            <div class="col-md-4">
                <div class="card border-0 h-100" style="border-top: 4px solid {{ $site->nom == 'Akpakpa' ? '#da107b' : ($site->nom == 'Calavi' ? '#ff9320' : '#333') }};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="h5 fw-bold mb-0" style="color: {{ $site->nom == 'Akpakpa' ? '#da107b' : ($site->nom == 'Calavi' ? '#ff9320' : '#333') }};">{{ $site->nom }}</h3>
                            <span class="badge rounded-pill" style="background-color: rgba(0, 0, 0, 0.05); color: {{ $site->nom == 'Akpakpa' ? '#da107b' : ($site->nom == 'Calavi' ? '#ff9320' : '#333') }};">
                                <i class="fas fa-building me-1"></i> Site
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Étudiants</span>
                                <strong style="color: #333;">{{ $site->students_count }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Formateurs</span>
                                <strong style="color: #333;">{{ $site->formateurs_count }}</strong>
                            </div>
                        </div>
    
                        <a href="{{ route('admingen.sites.show', $site->id) }}" class="btn btn-sm w-100 mt-2" style="background-color: #da107b; color: white;">
                            Voir détails <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
</div>

</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    .card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    
    .progress {
        background-color: #f0f0f0;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 5px 10px;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .rounded-circle {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .badge {
        padding: 5px 10px;
        font-weight: 500;
    }
    
    h6 {
        letter-spacing: 1px;
        font-size: 0.75rem;
    }
    
    h2 {
        font-size: 2rem;
    }
    .progress {
        width: 80px;
        background-color: #e9ecef;
    }
    .progress-bar {
        background-color: #495057;
    }
    table {
        font-size: 14px;
    }
    table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }
</style>
@endpush