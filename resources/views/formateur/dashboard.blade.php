@extends('layouts.for')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de Bord</h1>
       
    </div>

    <!-- Cartes Statistiques -->
    <div class="row">
        <!-- Étudiants -->
        <div class="col-xl-6 col-md-6 mb-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Étudiants</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $nombreEtudiants }}</div>

                            </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formations -->
        <div class="col-xl-6 col-md-6 mb-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Matières</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $nombreMatieres }}</div>
                            </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

   
</div>
@endsection

@section('scripts')
<!-- Scripts pour les graphiques -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

@endsection

@section('styles')
<style>
    .card {
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    .activity-feed {
        padding: 0;
        list-style: none;
    }

    .feed-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .feed-item .date {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .feed-item .text {
        margin-top: 5px;
        font-size: 0.95rem;
    }

    .chart-bar {
        height: 300px;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .bg-primary {
        background-color: #4e73df !important;
    }

    .text-success {
        color: #1cc88a !important;
    }

    .bg-success {
        background-color: #1cc88a !important;
    }

    .text-info {
        color: #36b9cc !important;
    }

    .bg-info {
        background-color: #36b9cc !important;
    }

    .text-warning {
        color: #f6c23e !important;
    }

    .bg-warning {
        background-color: #f6c23e !important;
    }
</style>
@endsection