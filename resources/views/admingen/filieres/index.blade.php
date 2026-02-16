@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-semibold text-gray-800">
            <i class="fas fa-list-alt me-2"></i>Liste des filières
        </h2>
        <a href="{{ route('admingen.filieres.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>Ajouter
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Nom</th>
                            <th>Code</th>
                            <th class="pe-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filieres as $filiere)
                        <tr>
                            <td class="ps-3">{{ $filiere->nom }}</td>
                            <td>{{ $filiere->code }}</td>
                            <td class="pe-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admingen.filieres.edit', $filiere) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                       <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admingen.filieres.destroy', $filiere) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Confirmer la suppression ?')">
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.375rem;
        border: 1px solid rgba(0, 0, 0, 0.075);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    
    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table tbody tr:last-child {
        border-bottom: none;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
</style>
@endpush