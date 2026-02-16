@extends('layouts.dash')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header avec filtre date -->
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h3 class="mb-3 mb-md-0">
                    <i class="fas fa-calendar-check text-dark me-2"></i>Synthèse des Présences
                </h3>
                <form method="GET" class="date-filter">
                    <div class="input-group">
                        <input type="date" name="date" value="{{ $selectedDate }}" class="form-control date-picker"
                            max="{{ now()->format('Y-m-d') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> <span class="d-none d-sm-inline"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Détails par filière -->
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th data-label="Filière">Filière</th>
                                <th data-label="Formateurs">Formateurs</th>
                                <th data-label="Détails">Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($filieres as $filiere)
                                <tr>
                                    <td data-label="Filière">
                                        <strong>{{ $filiere['nom'] }}</strong><br>
                                        <small class="text-muted">{{ $filiere['present'] }} présent(s)</small>
                                    </td>
                                   
                                   
                                    <td data-label="Formateurs">
                                        <div class="formateurs-list">
                                            {{ Str::limit($filiere['formateurs'], 30) }}
                                        </div>
                                    </td>
                                    <td data-label="Détails">
                                        <button class="btn btn-sm bg-dark text-white rounded-pill px-3 shadow-sm d-flex align-items-center"
                                        data-bs-toggle="modal"
                                        data-bs-target="#studentsModal"
                                        data-filiere="{{ $filiere['nom'] }}"
                                        data-students="{{ json_encode($filiere['students']) }}">
                                    <span class="d-none d-md-inline">Voir</span>
                                </button>
                                
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal des étudiants -->
    <div class="modal fade" id="studentsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header  text-white">
                    <h5 class="modal-title" id="modalTitle">Liste des étudiants - <span id="filiereTitle"></span></h5>
                    <button type="button" class="btn btn-sm btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center"
            data-bs-dismiss="modal" aria-label="Fermer"
            style="width: 32px; height: 32px;">
        <i class="fas fa-times text-dark"></i>
    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table  align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Étudiant</th>
                                    <th>Matricule</th>
                                    <th class="text-center">Statut</th>
                                    <th class="pe-4">Enregistré par</th>
                                </tr>
                            </thead>
                            <tbody id="modalStudentsBody" class="border-top-0">
                                <!-- Les étudiants seront insérés ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #eef2ff;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --warning-color: #f8961e;
        --danger-color: #f94144;
        --light-bg: #f8f9fa;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        background-color: #f5f7fb;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Header et filtre date */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: var(--transition);
    }
    .card-header {
        background: white !important;
        border-bottom: none;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0 !important;
    }

    .date-filter .input-group {
        width: 100%;
        min-width: 280px;
    }

    .date-filter button {
        border-radius: 0 8px 8px 0 !important;
        padding: 0.6rem 1.25rem;
        transition: var(--transition);
        font-weight: 500;
    }

    /* Tableau principal */
    .table {
        margin-bottom: 0;
        --bs-table-hover-bg: rgba(67, 97, 238, 0.03);
    }

    .table thead th {
        background-color: var(--primary-light);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-top: none;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    }

    .table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-top: 1px solid rgba(0, 0, 0, 0.03);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

  
    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.9em;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

 
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }


    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 0;
        max-height: 65vh;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem;
    }

    /* Avatar dans le modal */
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        font-weight: 600;
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-radius: 50%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-header {
            padding: 1.25rem;
        }

        .date-filter {
            width: 100%;
        }

        .table thead {
            display: none;
        }

        .table,
        .table tbody,
        .table tr,
        .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            position: relative;
        }

        .table td {
            padding: 0.75rem 0.75rem 0.75rem 50%;
            text-align: right;
            position: relative;
            border: none;
        }

        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.8rem;
        }

        .table td:first-child {
            padding-top: 1.5rem;
        }

        .table td:last-child {
            padding-bottom: 1.5rem;
            text-align: center;
        }

        .progress-wrapper {
            margin-top: 1rem;
        }

        .modal-dialog {
            margin: 0.5rem;
            width: calc(100% - 1rem);
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table tbody tr {
        animation: fadeIn 0.4s ease-out forwards;
        opacity: 0;
    }

    .table tbody tr:nth-child(1) {
        animation-delay: 0.1s;
    }

    .table tbody tr:nth-child(2) {
        animation-delay: 0.2s;
    }

    .table tbody tr:nth-child(3) {
        animation-delay: 0.3s;
    }

    /* ... etc */
</style>