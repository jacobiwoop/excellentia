@extends('layouts.app') @section('content')
<div class="container mt-4">
    <h3 class="mb-4">📋 Marquer les présences</h3>
    <form method="POST" action="{{ route('admingen.attendance.showStudents') }}" class="p-4 shadow rounded bg-light"> @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="site_id" class="form-label">🌍 Site</label>
                <select name="site_id" id="site_id" class="form-select" required>
                    <option value="" disabled selected>-- Site --</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filiere_id" class="form-label">🎓 Filière</label>
                <select name="filiere_id" id="filiere_id" class="form-select" required>
                    <option value="" disabled selected>-- Filière --</option>
                    @foreach ($filieres as $filiere)
                        <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">📅 Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">🔍 Voir les étudiants</button>
            </div>
        </div>
    </form>
</div>
@endsection