@extends('layouts.for')

@section('content')
<div class="container mt-4">

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Erreur !</strong> Veuillez corriger les champs suivants :
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h2>{{ $type === 'video' ? 'Ajouter une Vidéo' : 'Ajouter un Document' }}</h2>

    <form action="{{ route('formateur.cours.store', ['type' => $type]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Titre --}}
        <div class="mb-3">
            <label for="titre" class="form-label">Titre du cours</label>
            <input type="text" name="titre" id="titre" class="form-control"
                value="{{ old('titre') }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
        </div>

        {{-- Sites --}}
        <div class="mb-3">
            <label for="sites" class="form-label">Sites</label>
            <select name="sites[]" id="sites" class="form-select" multiple required size="5">
                @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ collect(old('sites'))->contains($site->id) || $sites->count() == 1  ? 'selected' : '' }}>
                    {{ $site->nom }}
                </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs sites.</small>
        </div>

        {{-- Filières --}}
        <div class="mb-3">
            <label for="filieres" class="form-label">Filières</label>
            <select name="filieres[]" id="filieres" class="form-select" multiple required size="5">
                @foreach($filieres as $filiere)
                <option value="{{ $filiere->id }}" {{ collect(old('filieres'))->contains($filiere->id) ? 'selected' : '' }}>
                    {{ $filiere->nom }}
                </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs filières.</small>
        </div>

        {{-- Promotion --}}
        <div class="mb-3">
            <label for="promotion_id" class="form-label">Promotion</label>
            <select name="promotion_id" id="promotion_id" class="form-select" required>
                <option value="">-- Choisir une promotion --</option>
                @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}" {{ old('promotion_id') == $promotion->id ? 'selected' : '' }}>
                    {{ $promotion->nom }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Fichier --}}
        @if($type === 'file')
        <div class="mb-3">
            <label for="fichier" class="form-label">Fichier Document</label>
            <input type="file" name="fichier" id="fichier" class="form-control"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,image/*" required>
        </div>
        @endif

        {{-- Vidéo --}}
        @if($type === 'video')
        <div class="mb-3">
            <label for="video" class="form-label">Fichier Vidéo</label>
            <input type="file" name="video" id="video" class="form-control"
                accept="video/mp4,video/x-m4v,video/*" required>
            <small class="text-muted">Formats acceptés : MP4, MOV, AVI. Max 100 Mo.</small>
        </div>
        @endif

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ $type === 'video' ? route('formateur.videos.index') : route('formateur.cours.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection