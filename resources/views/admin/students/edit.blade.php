@extends('layouts.ad')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-dark-800">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>Modifier l'étudiant
                        </h2>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-2 mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.students.update', $student->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Nom et prénom</label>
                            <input type="text" name="nom_prenom" class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('nom_prenom', $student->nom_prenom) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Téléphone</label>
                            <input type="text" name="telephone" class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('telephone', $student->telephone) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Email</label>
                            <input type="email" name="email" class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('email', $student->email) }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Sexe</label>
                            <select name="sexe" class="form-select rounded-2 py-2 px-3 border-1" required>
                                @foreach ($sexes as $key => $label)
                                    <option value="{{ $key }}" {{ old('sexe', $student->sexe) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Date de naissance</label>
                            <input type="date" name="date_naissance"
                                   class="form-control rounded-2 py-2 px-3 border-1"
                                   value="{{ old('date_naissance', $student->date_naissance ? $student->date_naissance->format('Y-m-d') : '') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance"
                                   class="form-control rounded-2 py-2 px-3 border-1"
                                   value="{{ old('lieu_naissance', $student->lieu_naissance) }}">
                        </div>
                        

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Promotion</label>
                            <select name="promotion_id" class="form-select rounded-2 py-2 px-3 border-1" required>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ old('promotion_id', $student->promotion_id) == $promotion->id ? 'selected' : '' }}>
                                        {{ $promotion->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Filière</label>
                            <select name="filiere_id" class="form-select rounded-2 py-2 px-3 border-1" required>
                                @foreach ($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id', $student->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Photo actuelle</label>
                            <div class="d-flex align-items-center gap-3">
                                @if ($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" width="80" class="rounded-2 border">
                                @else
                                    <span class="text-muted">Aucune photo</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark-700 mb-2">Changer la photo</label>
                            <input type="file" name="photo" class="form-control rounded-2 py-2 px-3 border-1">
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-save me-2"></i> Mettre à jour
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
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c2c7;
        color: #842029;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    
    .btn-outline-dark {
        border-color: #dee2e6;
    }
    
    .btn-outline-dark:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush