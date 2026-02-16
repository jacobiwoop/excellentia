@extends('layouts.ad')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-gray-800">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>Ajouter un étudiant
                        </h2>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    @if ($errors->any())
                        <div class="alert alert-light-danger border border-danger rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Nom complet</label>
                            <input type="text" name="nom_prenom" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('nom_prenom') }}" 
                                   required
                                   autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('email') }}" 
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="text" name="telephone" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('telephone') }}" 
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Sexe</label>
                            <select name="sexe" class="form-select rounded-2 py-2 px-3 border-1" required>
                                <option value="">Sélectionner...</option>
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" name="date_naissance" 
                                   class="form-control rounded-2 py-2 px-3 border-1"
                                   value="{{ old('date_naissance') }}"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" 
                                   class="form-control rounded-2 py-2 px-3 border-1"
                                   value="{{ old('lieu_naissance') }}"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Filière</label>
                            <select name="filiere_id" class="form-select rounded-2 py-2 px-3 border-1" required>
                                <option value="">Sélectionner...</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Promotion</label>
                            <select name="promotion_id" class="form-select rounded-2 py-2 px-3 border-1" required>
                                <option value="">Sélectionner...</option>
                                @foreach($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ old('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                        {{ $promotion->nom }} ({{ \Carbon\Carbon::parse($promotion->date_debut)->format('M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-gray-700 mb-2">Photo (optionnelle)</label>
                            <input type="file" class="form-control rounded-2 py-2 px-3 border-1" name="photo" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-save me-2"></i> Enregistrer
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
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
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
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
    
    .alert-light-danger {
        background-color: rgba(220, 53, 69, 0.05);
        color: #dc3545;
    }
</style>
@endpush
