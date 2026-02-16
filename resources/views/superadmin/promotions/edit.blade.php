@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-gray-800">
                            <i class="fas fa-edit me-2 text-dark"></i>Modifier la promotion
                        </h2>
                        <a href="{{ route('superadmin.promotions.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="{{ route('superadmin.promotions.update', $promotion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-medium text-gray-700 mb-2">Nom de la promotion</label>
                            <input type="text" name="nom" id="nom" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ $promotion->nom }}"
                                   required
                                   autofocus>
                            @error('nom')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date_debut" class="form-label fw-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ $promotion->date_debut }}"
                                   required>
                            @error('date_debut')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date_fin" class="form-label fw-medium text-gray-700 mb-2">Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ $promotion->date_fin }}"
                                   required>
                            @error('date_fin')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-sync-alt me-2"></i> Mettre à jour
                            </button>
                            <a href="{{ route('superadmin.promotions.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
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
    
    .form-control, .form-select {
        border: 1px solid #e0e0e0;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
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
    // Scripts supplémentaires si nécessaire
</script>
@endpush