@extends('layouts.dash')

@section('content')
<div class="container">
    <h2>Créer un nouveau formateur</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('superadmin.formateurs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>


        <!-- l'admin choisi le site du formateur  -->
        <div class="mb-3">
            <label for="site_id" class="form-label">Site (École)</label>
            <select name="site_id" id="site_id" class="form-control" required>
                <option value="">Sélectionner un site</option>
                @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
            <label class="form-check-label" for="showPassword">Afficher les mots de passe</label>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('superadmin.formateurs.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

{{-- Script pour afficher / masquer les mots de passe --}}
<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        confirm.type = type;
    }
</script>
@endsection