@extends('layouts.ad')

@section('content')
<div class="container mt-4" style="max-width: 800px;">
    <div class="mb-4">
        <a href="{{ route('admin.depenses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>
                Ajouter une dépense
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.depenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Motif / Catégorie <span class="text-danger">*</span></label>
                    <input type="text" name="motif" class="form-control @error('motif') is-invalid @enderror" 
                           value="{{ old('motif') }}" 
                           placeholder="Ex: Fournitures de bureau, Loyer février, Entretien climatisation..."
                           required>
                    @error('motif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Précisez la nature de cette dépense</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                               value="{{ old('montant') }}" 
                               min="0" step="0.01" required>
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date de la dépense <span class="text-danger">*</span></label>
                        <input type="date" name="date_depense" class="form-control @error('date_depense') is-invalid @enderror" 
                               value="{{ old('date_depense', date('Y-m-d')) }}" required>
                        @error('date_depense')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Formateur (optionnel)</label>
                    <select name="formateur_id" class="form-select @error('formateur_id') is-invalid @enderror">
                        <option value="">-- Aucun (si ce n'est pas un salaire) --</option>
                        @foreach($formateurs as $formateur)
                            <option value="{{ $formateur->id }}" {{ old('formateur_id') == $formateur->id ? 'selected' : '' }}>
                                {{ $formateur->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('formateur_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Sélectionnez uniquement si c'est un salaire de formateur</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description (optionnel)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3" placeholder="Détails supplémentaires...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
    <label class="form-label fw-bold">Justificatif <span class="text-muted">(optionnel)</span></label>
    <input type="file" name="justificatif" class="form-control @error('justificatif') is-invalid @enderror" 
           accept=".pdf,.jpg,.jpeg,.png">
    @error('justificatif')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="alert alert-info mt-2 mb-0">
        <i class="fas fa-info-circle me-2"></i>
        Vous pouvez joindre un justificatif (facture, reçu, bon) si disponible.
        <br><small>Formats acceptés : PDF, JPG, PNG (Max 5 MB)</small>
    </div>
</div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.depenses.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection