@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 fw-semibold text-dark-800">
                            <i class="fas fa-user-edit me-2 text-dark"></i>Modifier l'administrateur
                        </h2>
                        <a href="{{ route('admingen.admins.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="{{ route('admingen.admins.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium text-dark-700 mb-2">Nom complet</label>
                            <input type="text" name="name" class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium text-dark-700 mb-2">Adresse email</label>
                            <input type="email" name="email" class="form-control rounded-2 py-2 px-3 border-1" 
                                   value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium text-dark-700 mb-2">Nouveau mot de passe (optionnel)</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" 
                                       class="form-control rounded-2 py-2 px-3 border-1">
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
                            <label for="password_confirmation" class="form-label fw-medium text-dark-700 mb-2">Confirmation</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control rounded-2 py-2 px-3 border-1">
                                <button class="btn btn-outline-secondary border-start-0 rounded-end-2 toggle-password" 
                                        type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="site_id" class="form-label fw-medium text-dark-700 mb-2">Site associé</label>
                            <select name="site_id" id="site_id" class="form-select rounded-2 py-2 px-3 border-1" required>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ $admin->site_id == $site->id ? 'selected' : '' }}>
                                        {{ $site->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                <i class="fas fa-save me-2"></i> Enregistrer
                            </button>
                            <a href="{{ route('admingen.admins.index') }}" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
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
    
    .toggle-password {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .toggle-password:hover {
        background-color: #e9ecef;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')

@endpush