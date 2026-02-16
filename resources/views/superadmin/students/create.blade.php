@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Carte avec ombre plus douce et espacement accru -->
            <div class="card border-1 overflow-hidden">
                <!-- En-tête avec couleur d'accent -->
                <div class="card-header bg-white py-4 border-0">
                    <div class="text-center">
                        <h2 class="h3 fw-semibold mb-0" style="color: #e40c7c;">
                            <i class="fas fa-user-plus me-2"></i>Nouvel Étudiant
                        </h2>
                        <p class="text-dark mt-2 mb-0">Remplissez les informations de l'étudiant</p>
                    </div>
                </div>
                
                <div class="card-body px-4 px-md-5 py-4">
                    @if ($errors->any())
                        <div class="alert alert-light-danger border border-danger rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <strong>Veuillez corriger les erreurs :</strong>
                                    <ul class="mb-0 ps-3 mt-1">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                        
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="nom_prenom" 
                                           name="nom_prenom" value="{{ old('nom_prenom') }}" placeholder=" " required>
                                    <label for="nom_prenom">Nom complet</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control rounded-3" id="email" 
                                           name="email" value="{{ old('email') }}" placeholder=" " required>
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="telephone" 
                                           name="telephone" value="{{ old('telephone') }}" placeholder=" " required>
                                    <label for="telephone">Téléphone</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="sexe" id="sexe" class="form-select rounded-3" required>
                                        <option value=""></option>
                                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                    <label for="sexe">Sexe</label>
                                </div>
                            </div>

                            <!-- Section Scolarité -->
                            <!-- Date de naissance -->
<div class="col-md-6">
    <div class="form-floating">
        <input type="date" class="form-control rounded-3" id="date_naissance" 
               name="date_naissance" value="{{ old('date_naissance') }}" placeholder=" " >
        <label for="date_naissance">Date de naissance</label>
    </div>
</div>

<!-- Lieu de naissance -->
<div class="col-md-6">
    <div class="form-floating">
        <input type="text" class="form-control rounded-3" id="lieu_naissance" 
               name="lieu_naissance" value="{{ old('lieu_naissance') }}" placeholder=" " >
        <label for="lieu_naissance">Lieu de naissance</label>
    </div>
</div>

                           

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="filiere_id" id="filiere_id" class="form-select rounded-3" required>
                                        <option value=""></option>
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                                {{ $filiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="filiere_id">Filière</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="site_id" id="site_id" class="form-select rounded-3" required>
                                        <option value=""></option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                                {{ $site->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="site_id">Site</label>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="promotion_id" id="promotion_id" class="form-select rounded-3" required>
                                        <option value=""></option>
                                        @foreach($promotions as $promotion)
                                            <option value="{{ $promotion->id }}" {{ old('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                                {{ $promotion->nom }} ({{ \Carbon\Carbon::parse($promotion->date_debut)->format('M Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="promotion_id">Promotion</label>
                                </div>
                            </div>

                            <!-- Section Photo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                  
                                    <input type="file" class="form-control rounded-3" id="photo" name="photo" accept="image/*">
                                    <small class="text-dark">Formats acceptés: JPG, PNG (max 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary px-4 py-3 rounded-3 fw-semibold">
                                <i class="fas fa-save me-2"></i>Enregistrer l'étudiant
                            </button>
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
    :root {
        --primary-color: #3f80ff;
        --primary-hover: #2a6df5;
    }
    
    body {
        background-color: #ffffff;
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
    }
    
    .card-header {
        padding-top: 2rem;
        padding-bottom: 1rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #e0e6ed;
        border-radius: 0.5rem !important;
        padding: 1rem;
        height: auto;
        transition: all 0.3s ease;
    }
    
    .form-floating>label {
        padding: 0.8rem 1rem;
        color: #6c757d;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(63, 128, 255, 0.15);
    }
    
    .form-control:not(:placeholder-shown)~label,
    .form-control:focus~label,
    .form-select~label {
        transform: scale(0.85) translateY(-0.8rem) translateX(0.15rem);
        background: white;
        padding: 0 0.5rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(63, 128, 255, 0.25);
    }
    
    .alert-light-danger {
        background-color: rgba(220, 53, 69, 0.05);
        border-left: 4px solid #dc3545 !important;
    }
    
    /* Espacement amélioré */
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }
    
    /* Animation subtile */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .card {
        animation: fadeIn 0.4s ease-out;
    }
</style>
@endpush