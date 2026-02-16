@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Header et Filtres combinés -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h2 class="h4 fw-bold mb-0">Liste des étudiants</h2>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <div class="filter-container p-2 rounded-3 d-flex align-items-center gap-2">
                <!-- Filtre compact -->
                <form action="{{ route('admingen.students.index') }}" method="GET"
                    class="d-flex align-items-center gap-2 flex-grow-1 mt-3">
                    <div class="position-relative flex-grow-1">
                        <select name="promotion_id" class="form-select form-select-sm border-0 bg-light">
                            <option value="">Toutes promotions</option>
                            @foreach($promotions as $promotion)
                            <option value="{{ $promotion->id }}" {{ request('promotion_id')==$promotion->id ? 'selected'
                                : '' }}>
                                {{ $promotion->nom }} ({{ \Carbon\Carbon::parse($promotion->date_debut)->format('Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="position-relative flex-grow-1">
                        <select name="filiere_id" class="form-select form-select-sm border-0 bg-light">
                            <option value="">Toutes filières</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ request('filiere_id')==$filiere->id ? 'selected' : ''
                                }}>
                                {{ $filiere->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-info rounded-circle hover-scale" title="Filtrer">
                        <i class="fas fa-search"></i>
                    </button>

                    @if(request()->hasAny(['promotion_id', 'filiere_id']))
                    <a href="{{ route('admingen.students.index') }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle hover-scale" title="Réinitialiser">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    @endif
                </form>


            </div>
        </div>
    </div>

    <!-- Notification Flash -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Carte du tableau -->
    <div class="card border-0 rounded-3 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-light-primary">
                        <tr>
                            <th class="py-2 px-3">Photo</th>
                            <th class="py-2 px-2">Nom</th>
                            <th class="py-2 px-2">Matricule</th>
                            <th class="py-2 px-2">Sexe</th>
                            <!--   <th class="py-2 px-2">Email</th> -->
                            <th class="py-2 px-2">Téléphone</th>
                            <th class="py-2 px-2 ">Site</th>
                            <th class="py-2 px-2">Filière</th>
                            <!--   <th class="py-2 px-2">Promotion</th> -->
                            <th class="py-2 px-2 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="py-2 px-2 align-middle">
                                @if($student->photo)
                                <img src="{{ asset($student->photo) }}" alt="{{ $student->nom_prenom }}" width="50"
                                    height="50" class="rounded-circle shadow-sm" style="object-fit: cover;">

                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td class="py-2 px-2 align-middle fw-semibold">{{ $student->nom_prenom }}</td>
                            <td class="py-2 px-2 align-middle font-monospace">{{ $student->matricule }}</td>
                            <td class="py-2 px-2 align-middle">
                                <span
                                    class="badge rounded-pill bg-{{ $student->sexe === 'M' ? 'warning' : 'pink' }} text-white">
                                    {{ $student->sexe }}
                                </span>
                            </td>
                            <!--   <td class="py-2 px-2 align-middle text-truncate" style="max-width: 150px;">
                                        {{ $student->email }}
                                    </td> -->
                            <td class="py-2 px-2 align-middle">{{ $student->telephone }}</td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $student->site->nom }}</span>
                            </td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge bg-purple-light text-purple-dark">{{ $student->filiere->nom }}</span>
                            </td>
                            <!--      <td class="py-2 px-2 align-middle">
                                        <span class="badge bg-light text-dark">{{ $student->promotion->nom }}</span>
                                    </td> -->
                            <td class="py-2 px-2 align-middle text-end">
                                <div class="d-flex justify-content-end gap-1 align-items-center">
                                    <!-- Voir -->
                                    <a href="{{ route('admingen.students.show', $student->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-pill d-flex align-items-center justify-content-center"
                                        data-bs-toggle="" title="Voir détails">
                                        Détails
                                    </a>


                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
        <div class="card-footer bg-transparent py-2">
            <div class="d-flex justify-content-center">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styles de base */
    .bg-light-primary {
        background-color: rgba(63, 81, 181, 0.08) !important;
    }

    .bg-pink {
        background-color: #e91e63 !important;
    }

    .bg-purple-light {
        background-color: rgba(156, 39, 176, 0.1) !important;
    }

    .text-purple-dark {
        color: #7b1fa2 !important;
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    .btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
    }

    /* Styles pour les filtres */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    /* Indicateur de filtres actifs */
    .filter-indicator {
        position: relative;
    }

    .filter-indicator::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background-color: #0d6efd;
        border-radius: 50%;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }

    /* Styles pour les filtres */
    .select2-container--default .select2-selection--single {
        border: 1px solid #e0e6ed;
        border-radius: 0.5rem !important;
        height: 38px;
        padding: 5px 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        color: #495057;
    }

    .select2-dropdown {
        border: 1px solid #e0e6ed;
        border-radius: 0.5rem;
    }

    /* Style pour le bouton de réinitialisation */
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    /* Adaptations responsive */
    @media (max-width: 991.98px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Optimisation mobile */
        .btn {
            padding: 0.25rem 0.5rem !important;
        }

        .badge {
            font-size: 0.75rem !important;
        }
    }
</style>
@endpush
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialiser Select2
        $('.select2').select2({
            placeholder: "Sélectionnez une option",
            allowClear: true,
            width: '100%'
        });

        // Réinitialiser les filtres
        $('.btn-reset').click(function () {
            $('.select2').val(null).trigger('change');
        });
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script>
    // Activer les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>