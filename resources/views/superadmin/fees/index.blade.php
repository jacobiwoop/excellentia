@extends('layouts.dash')

@section('content')
@php
use App\Models\Filiere;
@endphp

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0"><i class="fas fa-receipt me-2"></i>Gestion des Frais (Super Admin)</h2>
            <a href="{{ route('superadmin.fees.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Configurer les frais
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Filières</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fees as $fee)
                        <tr>
                            <td>{{ $fee->nom }}</td>
                            <td>{{ ucfirst($fee->type) }}</td>
                            <td>
                                @if($fee->type === 'formation')
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-{{ $fee->id }}">
                                        Voir détails
                                    </button>
                                @else
                                    {{ number_format(optional($fee->filieres->first())->pivot->montant ?? 0, 0, ',', ' ') }} FCFA
                                @endif
                            </td>
                            <td>
                                @if($fee->type === 'formation')
                                    {{ $fee->filieres->count() }} filière(s)
                                @else
                                    Toutes filières
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('superadmin.fees.edit', $fee->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       data-bs-toggle="tooltip" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('superadmin.fees.destroy', $fee->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce frais ?')"
                                                data-bs-toggle="tooltip" title="Supprimer">
                                            <i class="fas fa-trash"></i>
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
    </div>
</div>

<!-- Modals pour les détails des frais de formation -->
@foreach($fees as $fee)
@if($fee->type === 'formation')
<div class="modal fade" id="modal-{{ $fee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-dark">
                <h5 class="modal-title fs-4 text-dark"><i class="fas fa-receipt me-2 text-white"></i>Détails des frais: {{ $fee->nom }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 fs-5">Filières</th>
                            <th class="py-3 fs-5 text-end">Montant (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fee->filieres as $filiere)
                        <tr>
                            <td class="align-middle fs-5">{{ $filiere->nom }}</td>
                            <td class="align-middle text-end">
                                <span class="fw-bold fs-5 text-primary">
                                    {{ number_format($filiere->pivot->montant, 0, ',', ' ') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<style>
    .modal-lg {
        max-width: 800px;
    }
    .modal-body table {
        font-size: 1.1rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>
@endsection