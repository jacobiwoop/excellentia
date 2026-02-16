@extends('layouts.dash') {{-- adapte ici si ton layout s'appelle autrement --}}

@section('content')
<div class="container mt-4">
    <h2>Modifier mon profil</h2>
    <p class="text-muted">Rôle : <strong>{{ auth()->user()->role }}</strong></p>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('superadmin.profil.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="form-group mt-3 position-relative">
            <label for="password">Nouveau mot de passe (laisser vide si inchangé)</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        
        <div class="form-group mt-3 position-relative">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        

        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
    </form>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function togglePasswordVisibility(toggleBtnId, inputId) {
            const toggleBtn = document.getElementById(toggleBtnId);
            const input = document.getElementById(inputId);
    
            toggleBtn.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
    
                // Change l'icône
                const icon = toggleBtn.querySelector('i');
                if(type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }
    
        togglePasswordVisibility('togglePassword', 'password');
        togglePasswordVisibility('togglePasswordConfirmation', 'password_confirmation');
    });
</script>
    
