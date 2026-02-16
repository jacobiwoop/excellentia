@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <h3 class="mb-0">
                <i class="fas fa-tasks me-2 text-dark"></i>Liste des Assignations
            </h3>
            <a href="{{ route('admingen.assignations.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-1"></i> Nouvelle assignation
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light-primary">
                        <tr>
                            <th class="py-3 px-4 text-nowrap">Site</th>
                            <th class="py-3 px-4 text-nowrap">Formateur</th>
                            <th class="py-3 px-4 text-nowrap">Filière</th>
                            <th class="py-3 px-4 text-nowrap">Matière</th>
                            <th class="py-3 px-4 text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignations as $key => $assignation)
                            <tr class="transition-all ">
                                <td class="py-3 px-4">{{ $assignation->site->nom }}</td>
                                <td class="py-3 px-4">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-user-tie me-1 text-dark"></i>
                                        {{ $assignation->formateur->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($assignation->filiere_nom === 'Toutes les filières')
                                        <span class="badge bg-success text-white">Toutes les filières</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $assignation->filiere_nom }}</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4">
                                    <span class="badge bg-light-blue text-white">
                                        {{ $assignation->subject->nom }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admingen.assignations.edit', $assignation->id) }}"
                                            class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1">
                                            <i class="fas fa-edit me-1"></i>
                                        </a>
                                        <form action="{{ route('admingen.assignations.destroy.multiple') }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <input type="hidden" name="site_id" value="{{ $assignation->site->id }}">
                                            <input type="hidden" name="formateur_id" value="{{ $assignation->formateur->id }}">
                                            <input type="hidden" name="subject_id" value="{{ $assignation->subject->id }}">

                                            <button onclick="return confirm('Confirmer la suppression ?')"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1">
                                                <i class="fas fa-trash-alt me-1"></i>
                                            </button>
                                        </form>



                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        Aucune assignation trouvée
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}

    </div>

    @push('styles')
        <style>
            .bg-light-primary {
                background-color: #e3f2fd;
            }

            .bg-light-blue {
                background-color: #e7f5ff;
            }

            .transition-all {
                transition: all 0.2s ease;
            }


            .btn-outline-warning {
                border-color: #ffc107;
                color: #ffc107;
            }

            .btn-outline-warning:hover {
                background-color: #ffc107;
                color: #000;
            }



            @media (max-width: 768px) {
                .table-responsive {
                    border: 0;
                }

                .table thead {
                    display: none;
                }

                .table tr {
                    display: block;
                    margin-bottom: 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 0.5rem;
                }

                .table td {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0.75rem;
                    border-bottom: 1px solid #f0f0f0;
                }

                .table td::before {
                    content: attr(data-label);
                    font-weight: 600;
                    margin-right: 1rem;
                }

                .table td:last-child {
                    border-bottom: 0;
                }

                .table td[data-label] {
                    text-align: right;
                    padding-left: 50%;
                    position: relative;
                }

                .table td[data-label]::before {
                    position: absolute;
                    left: 1rem;
                    width: calc(50% - 1rem);
                    padding-right: 1rem;
                    text-align: left;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Adaptation pour le responsive
            document.addEventListener('DOMContentLoaded', function () {
                // Ajout des data-labels pour le mobile
                const headers = ['#', 'Site', 'Formateur', 'Filière', 'Matière', 'Actions'];
                document.querySelectorAll('tbody tr td').forEach((td, index) => {
                    td.setAttribute('data-label', headers[index % headers.length]);
                });
            });
        </script>
    @endpush
@endsection