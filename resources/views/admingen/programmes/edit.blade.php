@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-edit mr-2"></i>Modifier le Programme
                    </h5>
                    <a href="{{ route('admingen.programmes.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admingen.programmes.update', $programme->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date du cours</label>
                                    <input type="date" name="date_seance" class="form-control" value="{{ old('date_seance', $programme->date_seance->format('Y-m-d')) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Site</label>
                                    <select name="site_id" class="form-select" required>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ $programme->site_id == $site->id ? 'selected' : '' }}>{{ $site->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label">Heure début</label>
                                        <input type="time" name="heure_debut" class="form-control" value="{{ old('heure_debut', $programme->heure_debut->format('H:i')) }}" required>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Heure fin</label>
                                        <input type="time" name="heure_fin" class="form-control" value="{{ old('heure_fin', $programme->heure_fin->format('H:i')) }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filières concernées</label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllFilieresEdit">
                                            <label class="form-check-label fw-bold" for="selectAllFilieresEdit">
                                                Toutes les filières
                                            </label>
                                        </div>
                                        @foreach($filieres as $filiere)
                                            <div class="form-check ms-3">
                                                <input class="form-check-input filiere-checkbox-edit" type="checkbox" name="filiere_ids[]" value="{{ $filiere->id }}" id="filiere_{{ $filiere->id }}" 
                                                    {{ $programme->filieres->contains($filiere->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="filiere_{{ $filiere->id }}">
                                                    {{ $filiere->nom }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Matière</label>
                                    <div class="input-group">
                                        <select name="subject_id" class="form-select" id="subjectSelect">
                                            <option value="">-- Choisir une matière --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $programme->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->nom }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-text">OU</span>
                                        <input type="text" name="titre_custom" id="customTitle" class="form-control" placeholder="Intitulé personnalisé" value="{{ old('titre_custom', $programme->titre_custom) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Formateur</label>
                                    <select name="formateur_id" class="form-select" required>
                                        @foreach($formateurs as $formateur)
                                            <option value="{{ $formateur->id }}" {{ $programme->formateur_id == $formateur->id ? 'selected' : '' }}>{{ $formateur->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description (optionnel)</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description', $programme->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Enregistrer
                            </button>
                            <a href="{{ route('admingen.programmes.index') }}" class="btn btn-secondary px-4">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAllFilieresEdit');
        const checkboxes = document.querySelectorAll('.filiere-checkbox-edit');

        // Quand on clique sur "Toutes les filières"
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });

        // Fonction qui vérifie si toutes les cases sont cochées
        function updateSelectAllStatus() {
            const allChecked = [...checkboxes].every(cb => cb.checked);
            selectAll.checked = allChecked;
        }

        // Quand une case individuelle change, on met à jour le statut de "Toutes les filières"
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectAllStatus);
        });

        // Initialiser l'état de la case "Toutes les filières"
        updateSelectAllStatus();

        // Gérer conflit matière / intitulé personnalisé
        const subjectSelect = document.getElementById('subjectSelect');
        const customTitle = document.getElementById('customTitle');

        subjectSelect.addEventListener('change', function () {
            if (this.value) customTitle.value = '';
        });

        customTitle.addEventListener('input', function () {
            if (this.value) subjectSelect.value = '';
        });

        @if($programme->titre_custom)
            subjectSelect.value = '';
        @endif
    });
</script>


@endsection
