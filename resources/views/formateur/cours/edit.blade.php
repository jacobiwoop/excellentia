@extends('layouts.for')

@section('content')
<div class="container mt-4">
    <h2>Modifier le cours</h2>

    <form action="{{ route('formateur.cours.update', ['cour' => $cour->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Titre --}}
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" name="titre" id="titre"
                value="{{ old('titre', $cour->titre ?? '') }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $cour->description ?? '') }}</textarea>
        </div>

        {{-- Sites --}}
        <div class="mb-3">
            <label for="sites" class="form-label">Sites</label>
            <select name="sites[]" id="sites" class="form-select" multiple required>
                @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ in_array($site->id, $selectedSites ?? []) ? 'selected' : '' }}>
                    {{ $site->nom }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs sites.</small>
        </div>

        {{-- Filières --}}
        <div class="mb-3">
            <label for="filieres" class="form-label">Filières</label>
            <select name="filieres[]" id="filieres" class="form-select" multiple required>
                @foreach($filieres as $filiere)
                <option value="{{ $filiere->id }}" {{ in_array($filiere->id, $selectedFilieres ?? []) ? 'selected' : '' }}>
                    {{ $filiere->nom }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs filières.</small>
        </div>

        {{-- Promotion --}}
        <div class="mb-3">
            <label for="promotion_id" class="form-label">Promotion</label>
            <select name="promotion_id" id="promotion_id" class="form-select" required>
                @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}" {{ $promotion->id == ($cour->promotion_id ?? null) ? 'selected' : '' }}>
                    {{ $promotion->nom }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Fichier --}}
        @if($type === 'file')
        <div class="mb-3">
            <label for="fichier" class="form-label">Fichier (laisser vide pour ne pas changer)</label>
            <input type="file" name="fichier" id="fichier" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,image/*">
            @if($cour->fichier_path ?? false)
            <p class="mt-2">Fichier actuel :
                <a href="{{ asset($cour->fichier_path) }}" target="_blank">
                    {{ basename($cour->fichier_path) }}
                </a>
            </p>
            @endif
        </div>
        @endif

        {{-- Vidéo --}}
        @if($type === 'video')
        <div class="mb-3">
            <label for="video" class="form-label">Vidéo (laisser vide pour ne pas changer)</label>
            <input type="file" name="video" id="video" class="form-control"
                accept="video/mp4,video/x-m4v,video/*">
            <small class="text-muted">Formats acceptés : MP4, MOV, AVI. Max 100 Mo.</small>
            @if($cour->video_path ?? false)
            <p class="mt-2">Vidéo actuelle :
                <a href="{{ asset($cour->video_path) }}" target="_blank">
                    {{ basename($cour->video_path) }}
                </a>
            </p>
            @endif
        </div>
        @endif

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ $type === 'video' ? route('formateur.videos.index') : route('formateur.cours.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection