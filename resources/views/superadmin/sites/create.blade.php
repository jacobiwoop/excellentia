@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <h1>Créer un nouveau site</h1>

    <form action="{{ route('superadmin.sites.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Nom du site</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required class="form-control">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code unique</label>
            <input type="text" name="code" value="{{ old('code') }}" required class="form-control">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Créer</button>
        <a href="{{ route('superadmin.sites.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
