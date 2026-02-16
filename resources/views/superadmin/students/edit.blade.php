@extends('layouts.dash')

@section('content')
<div class="container">
    <h2>Modifier un étudiant</h2>

    <form action="{{ route('superadmin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom et prénom</label>
            <input type="text" name="nom_prenom" value="{{ old('nom_prenom', $student->nom_prenom) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" value="{{ old('telephone', $student->telephone) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Sexe</label>
            <select name="sexe" class="form-control" required>
                @foreach ($sexes as $key => $label)
                    <option value="{{ $key }}" {{ old('sexe', $student->sexe) == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance" class="form-control"
       value="{{ old('date_naissance', $student->date_naissance ? $student->date_naissance->format('Y-m-d') : '') }}">
     
        </div>
        
        <div class="mb-3">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance" class="form-control"
                   value="{{ old('lieu_naissance', $student->lieu_naissance) }}">
        </div>
        

        <div class="mb-3">
            <label>Site</label>
            <select name="site_id" class="form-control" required>
                @foreach ($sites as $site)
                    <option value="{{ $site->id }}" {{ old('site_id', $student->site_id) == $site->id ? 'selected' : '' }}>
                        {{ $site->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Promotion</label>
            <select name="promotion_id" class="form-control" required>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ old('promotion_id', $student->promotion_id) == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Filière</label>
            <select name="filiere_id" class="form-control" required>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ old('filiere_id', $student->filiere_id) == $filiere->id ? 'selected' : '' }}>
                        {{ $filiere->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label>Photo actuelle</label><br>
            @if ($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}" width="80">
            @else
                <p>Aucune photo</p>
            @endif
        </div>

        <div class="mb-3">
            <label>Changer la photo</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
