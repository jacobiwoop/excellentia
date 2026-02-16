@extends('layouts.ad')

@section('content')
<div class="container mt-4" style="max-width: 800px;">
    <div class="mb-4">
        <a href="{{ route('admin.recettes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background-color: #ff9320; color: white;">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>
                Modifier la recette
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.recettes.update', $recette->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Motif / Catégorie <span class="text-danger">*</span></label>
                    <input type="text" name="motif" class="form-control @error('motif') is-invalid @enderror" 
                           value="{{ old('motif', $recette->motif) }}" required>
                    @error('motif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                               value="{{ old('montant', $recette->montant) }}" 
                               min="0" step="0.01" required>
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date de la recette <span class="text-danger">*</span></label>
                        <input type="date" name="date_recette" class="form-control @error('date_recette') is-invalid @enderror" 
                               value="{{ old('date_recette', $recette->date_recette->format('Y-m-d')) }}" required>
                        @error('date_recette')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description (optionnel)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3">{{ old('description', $recette->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Justificatif</label>
                    
                    @if($recette->justificatif)
    <div class="alert alert-info d-flex align-items-center mb-2">
        <i class="fas fa-file-alt me-2"></i>
        <div class="flex-grow-1">
            Fichier actuel : <strong>{{ basename($recette->justificatif) }}</strong>
        </div>
        <a href="{{ route('admin.recettes.download', $recette->id) }}" class="btn btn-sm btn-dark">
            <i class="fas fa-download"></i> Télécharger
        </a>
    </div>
@endif
                    
                    <input type="file" name="justificatif" class="form-control @error('justificatif') is-invalid @enderror" 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('justificatif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Laisser vide pour conserver le fichier actuel</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Mettre à jour
                    </button>
                    <a href="{{ route('admin.recettes.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection