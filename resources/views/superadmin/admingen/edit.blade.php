@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <h2>Modifier l'Admin Général</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.admin_gen.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">Nouveau mot de passe (laisser vide si inchangé)</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" >
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3 position-relative">
            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" >
                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirmation">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour l'Admin Général</button>
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
                if (type === 'password') {
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
