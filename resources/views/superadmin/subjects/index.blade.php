@extends('layouts.dash')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="row mb-4">
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h2 class="h4 fw-bold mb-0">Liste des Matières</h2>
                        <a href="{{ route('superadmin.subjects.create') }}"
                            class="btn btn-primary btn-sm rounded-pill hover-scale">
                            <i class="fas fa-plus-circle me-1"></i> Ajouter une matière
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Filières</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $subject->id }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $subject->nom }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    @php
                                                        $countFils = $subject->filieres->count();
                                                        $totalFils = \App\Models\Filiere::count();
                                                    @endphp

                                                    @if($countFils === $totalFils)
                                                        Toutes les filières
                                                    @elseif($countFils === 0)
                                                        Non attribué
                                                    @elseif($countFils === 1)
                                                        {{-- Une seule filière, on affiche son nom --}}
                                                        {{ $subject->filieres->first()->nom }}
                                                    @else   
                                                        {{-- Plusieurs filières mais pas toutes --}}
                                                        {{ $countFils }} filière(s)
                                                        {{-- Ou si tu préfères la liste complète, décommente :
                                                        {{ $subject->filieres->pluck('nom')->join(', ') }}
                                                        --}}
                                                    @endif
                                                </p>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('superadmin.subjects.edit', $subject) }}"
                                                        class="btn btn-info btn-sm me-2" data-toggle="tooltip" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('superadmin.subjects.destroy', $subject) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Confirmer la suppression ?')"
                                                            class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .table thead th {
            font-size: 0.75rem;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.375rem;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
    </style>
@endsection