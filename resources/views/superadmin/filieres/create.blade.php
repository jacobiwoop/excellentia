@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-gray-800">
                            <i class="fas fa-plus-circle me-2 text-dark"></i>Ajouter une filière
                        </h2>
                        <a href="{{ route('superadmin.filieres.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="{{ route('superadmin.filieres.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-medium text-gray-700 mb-2">Nom de la filière</label>
                            <input type="text" name="nom" id="nom" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   required
                                   autofocus>
                            @error('nom')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="code" class="form-label fw-medium text-gray-700 mb-2">Code de la filière</label>
                            <input type="text" name="code" id="code" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   required>
                            @error('code')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-save me-2"></i> Enregistrer
                            </button>
                            <a href="{{ route('superadmin.filieres.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    
    .form-control {
        border: 1px solid #e0e0e0;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
    }
    
    .btn-success {
        background-color: #198754;
        border-color: #198754;
        transition: all 0.2s ease;
    }
    
    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    // Script pour mettre automatiquement en majuscule le code de la filière
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush