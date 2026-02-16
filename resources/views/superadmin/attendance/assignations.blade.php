@extends('layouts.for')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Choisissez une classe</h3>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($filieres as $filiere)
                    <a href="{{ route('formateur.attendance.show', $filiere->id) }}" 
                       class="list-group-item list-group-item-action">
                        {{ $filiere->nom }} 
                        <span class="badge bg-primary float-end">
                            {{ $filiere->students->count() }} étudiants
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection