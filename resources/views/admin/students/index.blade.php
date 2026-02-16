@extends('layouts.ad')

@section('content')
    <div class="container-fluid px-3 py-4" style="min-height: 100vh;">
        <!-- Header -->
        <div class="row mb-4 animate__animated animate__fadeInDown">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h2 class="h4 fw-bold text-gradient-primary mb-0">Gestion des Étudiants</h2>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm rounded-pill hover-scale">
                    <i class="fas fa-plus-circle me-1"></i>Ajouter
                </a>
            </div>
        </div>

        <!-- Notification Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__zoomIn mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Carte du tableau -->
        <div class="card border-0 rounded-3 animate__animated animate__fadeIn">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light-primary">
                            <tr>
                                <th class="py-2 px-2">Photo</th>
                                <th class="py-2 px-2">Nom</th>
                                <th class="py-2 px-2 d-none d-lg-table-cell">Email</th>
                                <th class="py-2 px-2 d-none d-md-table-cell">Tél</th>
                                <th class="py-2 px-2">Sexe</th>
                                <th class="py-2 px-2 d-none d-xl-table-cell">Matricule</th>
                                <th class="py-2 px-2 d-none d-sm-table-cell">Site</th>
                                <th class="py-2 px-2 d-none d-lg-table-cell">Filière</th>
                                <th class="py-2 px-2 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="py-2 px-2 align-middle">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" 
                                                 alt="{{ $student->nom_prenom }}"
                                                 class="rounded-circle shadow-sm" width="40" height="40"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2 px-2 align-middle fw-semibold">{{ $student->nom_prenom }}</td>
                                    <td class="py-2 px-2 align-middle d-none d-lg-table-cell text-truncate" style="max-width: 150px;">
                                        {{ $student->email }}
                                    </td>
                                    <td class="py-2 px-2 align-middle d-none d-md-table-cell">{{ $student->telephone }}</td>
                                    <td class="py-2 px-2 align-middle">
                                        <span class="badge rounded-pill bg-{{ $student->sexe === 'M' ? 'info' : 'pink' }} text-white">
                                            {{ $student->sexe }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-2 align-middle font-monospace d-none d-xl-table-cell">{{ $student->matricule }}</td>
                                    <td class="py-2 px-2 align-middle d-none d-sm-table-cell">
                                        <span class="badge bg-light text-dark">{{ $student->site->nom }}</span>
                                    </td>
                                    <td class="py-2 px-2 align-middle d-none d-lg-table-cell">
                                        <span class="badge bg-purple-light text-purple-dark">{{ $student->filiere->nom }}</span>
                                    </td>
                                    <td class="py-2 px-2 align-middle text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-pill hover-scale px-2"
                                                data-bs-toggle="tooltip" title="Voir détails">
                                                 <i class="fas fa-eye"></i>
                                             </a>
                                             
                                            <a href="{{ route('admin.students.edit', $student->id) }}"
                                               class="btn btn-sm btn-outline-warning rounded-pill hover-scale px-2"
                                               data-bs-toggle="tooltip" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" id="deleteForm{{ $student->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('deleteForm{{ $student->id }}')"
                                                        class="btn btn-sm btn-outline-danger rounded-pill hover-scale px-2"
                                                        data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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

    <!-- Modal de confirmation amélioré -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">

                
                <div class="modal-body py-4 px-4">
                    <div class="text-center">
                        <i class="fas fa-exclamation-circle text-danger mb-3" style="font-size: 2.5rem;"></i>
                        <p class="mb-0">Cette action supprimera définitivement l'étudiant.<br>Voulez-vous continuer ?</p>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill " data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="button" class="btn btn-danger rounded-pill " id="confirmDeleteBtn">
                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                    </button>
                </div>
            </div>
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
        
        /* Styles spécifiques au modal */
        #deleteModal .modal-content {
            box-shadow: 0 5px 20px rgba(255, 107, 61, 0.3);
            overflow: hidden;
        }
        
        #deleteModal .modal-header {
            padding: 1rem 1.5rem;
        }
        
        #deleteModal .modal-body {
            padding: 1.5rem;
        }
        
        #deleteModal .modal-footer {
            padding: 0 1.5rem 1.5rem;
        }
        
        #deleteModal .btn-close {
            opacity: 1;
            background-size: 0.8rem;
            padding: 0.5rem;
            margin: 0;
        }
        
        #deleteModal .btn-danger {
            background-color: #ff6b3d;
            border-color: #ff6b3d;
        }
        
        #deleteModal .btn-danger:hover {
            background-color: #e05a2e;
            border-color: #e05a2e;
        }

       
        /* Adaptations responsive */
        @media (min-width: 992px) {
            .card {
                max-height: calc(100vh - 200px);
                display: flex;
                flex-direction: column;
            }
            
            .table-responsive {
                overflow-y: auto;
                flex: 1;
            }
            
            thead {
                position: sticky;
                top: 0;
                z-index: 10;
            }
        }
        
        @media (max-width: 991.98px) {
            .container-fluid {
                overflow-y: auto;
                padding-bottom: 20px;
            }
            
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

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

   
@endpush