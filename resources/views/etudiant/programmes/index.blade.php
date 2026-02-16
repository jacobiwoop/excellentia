@extends('layouts.stu')

@section('content')
<div class="container-fluid px-0 px-md-3 mt-3 mt-md-5">
    <!-- Wrapper pour l'export PDF -->
    <div id="pdf-content">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <!-- En-tête avec nom de la filière -->
            <div class="card-header text-white py-2 py-md-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h4 class="mb-1 mb-md-0 h5 h4-md font-weight-bold">
                        <i class="fas fa-calendar-alt mr-2"></i>Emploi du Temps - {{ $filiere->nom }}
                    </h4>
                    <button class="btn btn-light btn-sm rounded-pill px-2 px-md-3 no-print mt-2 mt-md-0" id="export-pdf">
                        <i class="fas fa-file-pdf mr-1"></i> <span class="d-none d-md-inline">Exporter PDF</span>
                    </button>
                </div>
            </div>

            <!-- Corps du tableau -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light-success">
                            <tr>
                                <th class="py-2 py-md-3 px-2 px-md-3">Date</th>
                                <th class="py-2 py-md-3 px-2 px-md-3">Horaire</th>
                                <th class="py-2 py-md-3 px-2 px-md-3">Matière</th>
                                <th class="py-2 py-md-3 px-2 px-md-3">Formateur</th>
                            </tr>
                        </thead>
                       <tbody>
@forelse($programmes as $programme)
<tr>
    <td class="py-2 py-md-3 px-2 px-md-3 align-middle">
        <div class="d-flex align-items-center">
            <div>
                @php \Carbon\Carbon::setLocale('fr'); @endphp
                <div class="font-weight-bold">{{ $programme->date_seance->format('d/m/Y') }}</div>
                <small class="text-muted">{{ $programme->date_seance->isoFormat('dddd') }}</small>
            </div>
        </div>
    </td>

    <td class="py-2 py-md-3 px-2 px-md-3 align-middle">
        <span class="badge bg-info text-white py-1 px-2">
            {{ $programme->heure_debut->format('H:i') }} - {{ $programme->heure_fin->format('H:i') }}
        </span>
    </td>

    <td class="py-2 py-md-3 px-2 px-md-3 align-middle">
        {{ $programme->titre_custom ?? optional($programme->subject)->nom ?? '-' }}
    </td>

  

    <td class="py-2 py-md-3 px-2 px-md-3 align-middle">
        {{ optional($programme->formateur)->name ?? '-' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="py-4 text-center text-muted">
        <i class="far fa-calendar-times fa-2x mb-2"></i>
        <h5 class="h6">Aucun cours programmé</h5>
        <p class="small">Votre emploi du temps apparaîtra ici</p>
    </td>
</tr>
@endforelse
</tbody>

                    </table>
                </div>

                <!-- Pagination -->
                @if($programmes->hasPages())
                <div class="card-footer bg-white py-2 no-print">
                    {{ $programmes->onEachSide(1)->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Styles pour l'impression */
    @media print {
        body * {
            visibility: hidden;
        }
        #pdf-content, #pdf-content * {
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

    /* Styles spécifiques pour mobile */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .card-header h4 {
            font-size: 1.1rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Bouton flottant pour mobile */
        #export-pdf {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50% !important;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            background-color: #f08b04;
            color: white;
            border: none;
        }
        
        #export-pdf i {
            margin-right: 0 !important;
            font-size: 1.2rem;
        }
        
        #export-pdf span {
            display: none;
        }
        
        /* Ajustement de la pagination */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-item {
            margin: 0.2rem;
        }
    }

    /* Styles pour desktop */
    @media (min-width: 769px) {
        .card-header {
            padding: 1rem;
        }
        
        #export-pdf {
            background-color: white;
            color: #f08b04;
        }
        
        #export-pdf:hover {
            background-color: #f0f0f0;
        }
    }
    td.matiere-cell {
    white-space: normal !important;
    word-break: break-word !important;
    max-width: 300px; /* ajuste si nécessaire */
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script pour l'export PDF (à implémenter selon votre solution)
        document.getElementById('export-pdf').addEventListener('click', function() {
            // Votre code d'export PDF ici
            window.print(); // Solution temporaire
        });
    });
</script>
@endpush
@endsection