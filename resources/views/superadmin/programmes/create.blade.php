@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0 fw-semibold text-gray-800">
                        <i class="fas fa-calendar-plus me-2 text-success"></i>Nouveau programme
                    </h2>
                    <a href="{{ route('superadmin.programmes.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('superadmin.programmes.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Type de programmation</label>
                            <select name="recurrence" class="form-select" id="recurrenceSelect">
                                <option value="ponctuel">Date unique</option>
                                <option value="hebdomadaire">Toutes les semaines</option>
                                <option value="mensuel">Tous les mois</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Site</label>
                            <select name="site_id" class="form-select" required>
                                <option value="">-- Sélectionner un site --</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Filières concernées</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllFilieres">
                                <label class="form-check-label fw-bold" for="selectAllFilieres">
                                    Toutes les filières
                                </label>
                            </div>
                            @foreach($filieres as $filiere)
                                <div class="form-check ms-3">
                                    <input class="form-check-input filiere-checkbox" type="checkbox" name="filiere_ids[]" value="{{ $filiere->id }}" id="filiere_{{ $filiere->id }}">
                                    <label class="form-check-label" for="filiere_{{ $filiere->id }}">
                                        {{ $filiere->nom }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Date</label>
                                <input type="date" name="date_seance" class="form-control" required>
                            </div>
                            <div class="col-md-6" id="endRecurrenceContainer" style="display:none;">
                                <label>Jusqu'au</label>
                                <input type="date" name="date_fin_recurrence" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Heure début</label>
                                <input type="time" name="heure_debut" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Heure fin</label>
                                <input type="time" name="heure_fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Intitulé du cours</label>
                            <div class="input-group">
                                <select name="subject_id" class="form-select" id="subjectSelect">
                                    <option value="">-- Choisir une matière --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nom }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-text">OU</span>
                                <input type="text" name="titre_custom" id="customTitle" class="form-control" placeholder="Intitulé personnalisé">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Formateur</label>
                            <select name="formateur_id" class="form-select" required>
                                <option value="">-- Sélectionner un formateur --</option>
                                @foreach($formateurs as $formateur)
                                    <option value="{{ $formateur->id }}">{{ $formateur->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Description (optionnel)</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAllFilieres');
        const checkboxes = document.querySelectorAll('.filiere-checkbox');

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    });
</script>
@endsection
