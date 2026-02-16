@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des administrateurs</h1>
        <a href="{{ route('admingen.admins.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Ajouter un admin
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        @if($admins->count())
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-dark">Utilisateur</th>
                        <th class="py-3 text-uppercase text-dark">Email</th>
                        <th class="pe-4 py-3 text-uppercase text-dark text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach ($admins as $admin)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-dark bg-opacity-10 text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="mb-0 text-dark">{{ $admin->name }}</p>
                                    <p class="mb-0 text-muted small">Admin depuis {{ $admin->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-dark">{{ $admin->email }}</span>
                        </td>
                       
                        <td class="pe-4 py-3 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admingen.admins.edit', $admin->id) }}" 
                                   class="btn btn-sm btn-outline-secondary rounded-end-0 border-end-0 px-3"
                                   data-bs-toggle="tooltip" title="Éditer">
                                    <i class="far fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admingen.admins.destroy', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger rounded-start-0 px-3"
                                            data-bs-toggle="tooltip" title="Supprimer"
                                            onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <div class="py-4">
                <i class="fas fa-user-tie fa-4x text-gray-400 mb-4"></i>
                <h5 class="text-gray-500 mb-3">Aucun administrateur enregistré</h5>
                <p class="text-muted mb-4">Commencez par ajouter un nouvel administrateur</p>
                <a href="{{ route('admingen.admins.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>Ajouter un admin
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar {
        font-weight: 600;
    }
    
    .table {
        --bs-table-hover-bg: rgba(13, 110, 253, 0.03);
    }
    
    .card {
        box-shadow: 0 0.15rem 1rem rgba(0, 0, 0, 0.03);
    }
    
    .btn-group {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
    
    .badge.bg-light {
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tooltips initialization
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover focus'
            })
        })
    })
</script>
@endpush