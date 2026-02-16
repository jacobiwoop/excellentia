@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h2 class="h4 fw-bold mb-0">Liste des formateurs</h2>
            <a href="{{ route('admingen.formateurs.create') }}" class="btn btn-primary btn-sm rounded-pill hover-scale">
                <i class="fas fa-plus-circle me-1"></i> Ajouter un formateur
            </a>
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
                <table class="table  mb-0">
                    <thead class="bg-light-primary">
                        <tr>
                            <th class="py-2 px-3">Nom</th>
                            <th class="py-2 px-2">Email</th>
                            <th class="py-2 px-2 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formateurs as $formateur)
                        <tr>
                            <td class="py-2 px-3 align-middle fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user-tie text-muted"></i>
                                    </div>
                                    <span>{{ $formateur->name }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-2 align-middle text-truncate" style="max-width: 200px;">
                                {{ $formateur->email }}
                            </td>
                         
                            <td class="py-2 px-2 align-middle text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admingen.formateurs.edit', $formateur->id) }}"
                                       class="btn btn-sm btn-outline-warning rounded-pill hover-scale px-2"
                                       data-bs-toggle="tooltip" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admingen.formateurs.destroy', $formateur->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger rounded-pill hover-scale px-2"
                                                data-bs-toggle="tooltip" title="Supprimer"
                                                onclick="return confirm('Voulez-vous vraiment supprimer ce formateur ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($formateurs->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i>Aucun formateur enregistré
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
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
        
        .hover-scale {
            transition: transform 0.2s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .btn-outline-warning {
            color: #ff9800;
            border-color: #ff9800;
        }
        
        .btn-outline-warning:hover {
            background-color: #ff9800;
            color: white;
        }
        
        .btn-outline-danger {
            color: #f44336;
            border-color: #f44336;
        }
        
        .btn-outline-danger:hover {
            background-color: #f44336;
            color: white;
        }
        
        /* Adaptations responsive */
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .btn {
                padding: 0.25rem 0.5rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script>
        // Activer les tooltips Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush