@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold text-dark">Gestion des promotions</h2>
        <a href="{{ route('admingen.promotions.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Ajouter une promotion
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-dark fw-medium">Nom</th>
                        <th class="py-3 text-dark fw-medium">Date de début</th>
                        <th class="py-3 text-dark fw-medium">Date de fin</th>
                        <th class="pe-4 py-3 text-dark fw-medium text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($promotions as $promotion)
                        <tr class="border-bottom">
                            <td class="ps-4 py-3">
                                <span class="fw-medium">{{ $promotion->nom }}</span>
                            </td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($promotion->date_debut)->format('d/m/Y') }}
                            </td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($promotion->date_fin)->format('d/m/Y') }}
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admingen.promotions.edit', $promotion->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                       data-bs-toggle="tooltip" title="Modifier">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    
                                    <form action="{{ route('admingen.promotions.destroy', $promotion->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                data-bs-toggle="tooltip" title="Supprimer"
                                                onclick="return confirm('Confirmer la suppression ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune promotion enregistrée</h5>
                                <a href="{{ route('admingen.promotions.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i> Créer une promotion
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .alert {
        border-left: 4px solid;
    }
</style>
@endpush

@push('scripts')
<script>
    // Activer les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush