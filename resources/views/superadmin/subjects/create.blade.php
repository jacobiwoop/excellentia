@extends('layouts.dash')

@section('content')
<div class="container">
    <h2>Ajouter une Matière</h2>
    <form method="POST" action="{{ route('superadmin.subjects.store') }}">
        @csrf

        <div class="mb-3">
            <label for="nom">Nom de la matière :</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
        </div>

        <div class="mb-3">
            <label>Filières (plusieurs possibles) :</label>
            <div>
                <button type="button" id="toggleAll" class="btn btn-sm btn-secondary mb-2">Tout sélectionner</button>
            </div>
            <div>
                @foreach($filieres as $filiere)
                    <div class="form-check">
                        <input class="form-check-input filiere-checkbox" type="checkbox" name="filieres[]" value="{{ $filiere->id }}" 
                            id="filiere_{{ $filiere->id }}"
                            {{ (is_array(old('filieres')) && in_array($filiere->id, old('filieres'))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filiere_{{ $filiere->id }}">
                            {{ $filiere->nom }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleAll');
        const checkboxes = document.querySelectorAll('.filiere-checkbox');
        let allSelected = false;

        toggleBtn.addEventListener('click', function() {
            allSelected = !allSelected;
            checkboxes.forEach(cb => cb.checked = allSelected);
            toggleBtn.textContent = allSelected ? 'Tout désélectionner' : 'Tout sélectionner';
        });
    });
</script>
@endsection
