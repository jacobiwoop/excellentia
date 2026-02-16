@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-gray-800">
                            <i class="fas fa-user-plus me-2" style="color: #f08b04"></i>Nouvel administrateur
                        </h2>
                        <a href="{{ route('superadmin.admins.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="{{ route('superadmin.admins.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium text-gray-700 mb-2">Nom complet</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('name') }}" 
                                   required
                                   autofocus>
                            @error('name')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium text-gray-700 mb-2">Adresse email</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium text-gray-700 mb-2">Mot de passe</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" 
                                       class="form-control rounded-2 py-2 px-3 border-1" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0 rounded-end-2 toggle-password" 
                                        type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium text-gray-700 mb-2">Confirmation</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control rounded-2 py-2 px-3 border-1" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0 rounded-end-2 toggle-password" 
                                        type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="site_id" class="form-label fw-medium text-gray-700 mb-2">Site associé</label>
                            <select name="site_id" id="site_id" 
                                    class="form-select rounded-2 py-2 px-3 border-1" 
                                    required>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                        {{ $site->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-user-plus me-2"></i> Créer l'admin
                            </button>
                            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
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
    
   
    
    .toggle-password {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .toggle-password:hover {
        background-color: #e9ecef;
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

@endpush