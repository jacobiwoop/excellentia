@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <h1>Modifier le site</h1>

    <form action="{{ route('superadmin.sites.update', $site->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom du site</label>
            <input type="text" name="nom" value="{{ old('nom', $site->nom) }}" required class="form-control">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code unique</label>
            <input type="text" name="code" value="{{ old('code', $site->code) }}" required class="form-control">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('superadmin.sites.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
