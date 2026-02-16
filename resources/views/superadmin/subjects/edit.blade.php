@extends('layouts.dash')

@section('content')
<div class="container">
    <h2>Modifier la Matière</h2>
    <form method="POST" action="{{ route('superadmin.subjects.update', $subject->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom">Nom de la matière :</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $subject->nom) }}" required>
        </div>

        <div class="mb-3">
            <label>Filières (plusieurs possibles) :</label>
            <div>
                <button type="button" id="toggleAll" class="btn btn-sm btn-secondary mb-2">Tout sélectionner</button>
            </div>
            <div>
                @foreach($filieres as $filiere)
                    <div class="form-check">
                        <input 
                            class="form-check-input filiere-checkbox" 
                            type="checkbox" 
                            name="filieres[]" 
                            value="{{ $filiere->id }}" 
                            id="filiere_{{ $filiere->id }}"
                            {{ (in_array($filiere->id, old('filieres', $subject->filieres->pluck('id')->toArray()))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filiere_{{ $filiere->id }}">
                            {{ $filiere->nom }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
