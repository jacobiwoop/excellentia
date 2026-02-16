@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-gray-800">
                            <i class="fas fa-edit me-2 text-dark"></i>Modifier l'assignation
                        </h2>
                        <a href="{{ route('admingen.assignations.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="{{ route('admingen.assignations.update', $assignation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="site_id" class="form-label fw-medium text-gray-700 mb-2">
                                    <i class="fas fa-building me-1 text-muted"></i> Site
                                </label>
                                <select name="site_id" id="site_id" 
                                        class="form-select rounded-2 py-2 px-3 border-1" 
                                        required>
                                    <option value="">-- Choisir un site --</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ $assignation->site_id == $site->id ? 'selected' : '' }}>
                                            {{ $site->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Formateur -->
                            <div class="col-md-6">
                                <label for="formateur_id" class="form-label fw-medium text-gray-700 mb-2">
                                    <i class="fas fa-chalkboard-teacher me-1 text-muted"></i> Formateur
                                </label>
                                <select name="formateur_id" id="formateur_id" 
                                        class="form-select rounded-2 py-2 px-3 border-1" 
                                        required>
                                    <option value="">-- Choisir un formateur --</option>
                                    @foreach($formateurs as $formateur)
                                        <option value="{{ $formateur->id }}" {{ $assignation->formateur_id == $formateur->id ? 'selected' : '' }}>
                                            {{ $formateur->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filières multiples -->
                            <div class="col-md-12">
                                <label class="form-label fw-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap me-1 text-muted"></i> Filières
                                </label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="selectAllFilieres">
                                    <label class="form-check-label fw-bold" for="selectAllFilieres">
                                        Toutes les filières
                                    </label>
                                </div>

                                <div class="row">
                                    @foreach($filieres as $filiere)
                                        <div class="col-md-6">
                                            <div class="form-check ms-2">
                                                <input class="form-check-input filiere-checkbox" 
                                                       type="checkbox" 
                                                       name="filiere_ids[]" 
                                                       value="{{ $filiere->id }}" 
                                                       id="filiere_{{ $filiere->id }}"
                                                       {{ in_array($filiere->id, $filiereIds ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="filiere_{{ $filiere->id }}">
                                                    {{ $filiere->nom }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Matière -->
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label fw-medium text-gray-700 mb-2">
                                    <i class="fas fa-book me-1 text-muted"></i> Matière
                                </label>
                                <select name="subject_id" id="subject_id" 
                                        class="form-select rounded-2 py-2 px-3 border-1" 
                                        required>
                                    <option value="">-- Choisir une matière --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $assignation->subject_id == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-4 mt-2">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-save me-2"></i> Mettre à jour
                            </button>
                            <a href="{{ route('admingen.assignations.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-times me-2"></i> Annuler
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
    
    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
        transition: all 0.2s ease;
    }
    
    .btn-warning:hover {
        background-color: #ffca2c;
        border-color: #ffc720;
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
    
    .text-muted {
        opacity: 0.7;
    }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllFilieres');
    const checkboxes = document.querySelectorAll('.filiere-checkbox');

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function updateSelectAll() {
        const allChecked = [...checkboxes].every(cb => cb.checked);
        selectAll.checked = allChecked;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectAll);
    });

    updateSelectAll();
});
</script>

@push('scripts')
<script>
    // Script pour améliorer l'UX
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des sélecteurs
        document.querySelectorAll('.form-select').forEach(select => {
            select.addEventListener('focus', function() {
                this.parentNode.querySelector('label').style.color = '#ffc107';
            });
            
            select.addEventListener('blur', function() {
                this.parentNode.querySelector('label').style.color = '';
            });
        });
    });
</script>
@endpush
