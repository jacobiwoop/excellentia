@extends('layouts.for')

@section('content')
    <div class="container-fluid px-0 px-md-3">
        <!-- Wrapper pour l'export PDF -->
        <div id="pdf-content">
            <div class="card border-0 rounded-3 overflow-hidden">
                <!-- En-tête -->
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <h4 class="mb-2 mb-md-0 font-weight-bold">
                            Emploi du Temps
                        </h4>
                        <button class="btn btn-light btn-sm rounded-pill px-3 no-print" id="export-pdf">
                            <i class="fas fa-file-pdf mr-1"></i> <span class="d-none d-md-inline">Exporter PDF</span>
                        </button>
                    </div>
                </div>

                <!-- Corps du tableau -->
                <div class="card-body p-0">
                    <div class="table-responsive-lg">
                        <table class="table mb-0">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th class="py-3 px-4 text-nowrap">Date</th>
                                    <th class="py-3 px-4 text-nowrap">Créneau horaire</th>
                                    <th class="py-3 px-4">Matière</th>
                                    <th class="py-3 px-4">Filière</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programmes as $programme)
                                    <tr class="transition-all hover:bg-gray-50">
                                        <td class="py-3 px-4 align-middle font-weight-600 text-nowrap">
                                            <div class="d-flex flex-column">
                                                @php
                                                    \Carbon\Carbon::setLocale('fr');
                                                @endphp
                                                <span>{{ $programme->date_seance->format('d/m/Y') }}</span>
                                                <small class="text-info font-weight-bold text-uppercase">
                                                    {{ $programme->date_seance->isoFormat('dddd') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-nowrap">
                                            <span class="badge badge-primary py-2 px-3 rounded-pill">
                                                {{ $programme->heure_debut->format('H:i') }} -
                                                {{ $programme->heure_fin->format('H:i') }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0 font-weight-600">
                                                        {{ $programme->titre_custom ?? optional($programme->subject)->nom ?? 'Non spécifié' }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            @php
                                                $toutes = $programme->filieres->firstWhere('nom', 'Toutes les filières');
                                            @endphp

                                            @if($toutes)
                                                <span class="badge badge-warning">Toutes les filières</span>
                                            @else
                                                @foreach($programme->filieres as $filiere)
                                                    <span class="badge badge-secondary py-2 px-3 mb-1">{{ $filiere->nom }}</span>
                                                @endforeach
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Aucun cours programmé</h5>
                                                <p class="text-muted mb-0">Votre emploi du temps apparaîtra ici</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($programmes->hasPages())
                        <div class="card-footer bg-white border-top-0 py-3 no-print">
                            <div class="d-flex justify-content-center">
                                {{ $programmes->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-gradient-primary {
                background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            }

            .transition-all {
                transition: all 0.2s ease;
            }

            .hover\:bg-gray-50:hover {
                background-color: #f9fafb;
            }

            .font-weight-600 {
                font-weight: 600;
            }

            .font-weight-800 {
                font-weight: 800;
            }

            .rounded-3 {
                border-radius: 0.75rem !important;
            }

            /* Styles pour mobile */
            @media (max-width: 768px) {
                #export-pdf {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    width: 50px;
                    height: 50px;
                    border-radius: 50% !important;
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                }

                #export-pdf i {
                    margin-right: 0 !important;
                }
            }

            /* Styles pour l'impression */
            @media print {
                body * {
                    visibility: hidden;
                }

                #pdf-content,
                #pdf-content * {
                    visibility: visible;
                }

                #pdf-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 0;
                    margin: 0;
                }

                .no-print {
                    display: none !important;
                }

                .table {
                    width: 100% !important;
                    font-size: 12px !important;
                }

                .badge {
                    border: 1px solid #000 !important;
                }
            }
        </style>
    @endpush

    @push('scripts')

    @endpush
@endsection