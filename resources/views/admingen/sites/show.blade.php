@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mt-4">Liste des étudiants : {{ $students->count() }}</h4>
    <div class="card border-0 rounded-3 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light-primary">
                        <tr>
                            <th class="py-2 px-2">Photo</th>
                            <th class="py-2 px-2">Nom</th>
                            <th class="py-2 px-2">Matricule</th>
                            <th class="py-2 px-2">Sexe</th>
                            <th class="py-2 px-2">Email</th>
                            <th class="py-2 px-2">Téléphone</th>
                            <th class="py-2 px-2">Site</th>
                            <th class="py-2 px-2">Filière</th>
                            <th class="py-2 px-2">Promotion</th>
                            <th class="py-2 px-2 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="py-2 px-2 align-middle">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                         alt="{{ $student->nom_prenom }}"
                                         class="rounded-circle shadow-sm" width="40" height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-2 px-2 align-middle fw-semibold">{{ $student->nom_prenom }}</td>
                            <td class="py-2 px-2 align-middle font-monospace">{{ $student->matricule }}</td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge rounded-pill bg-{{ $student->sexe === 'M' ? 'warning' : 'pink' }} text-white">
                                    {{ $student->sexe }}
                                </span>
                            </td>
                            <td class="py-2 px-2 align-middle text-truncate" style="max-width: 150px;">
                                {{ $student->email }}
                            </td>
                            <td class="py-2 px-2 align-middle">{{ $student->telephone }}</td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $student->site->nom }}</span>
                            </td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge bg-purple-light text-purple-dark">{{ $student->filiere->nom }}</span>
                            </td>
                            <td class="py-2 px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $student->promotion->nom }}</span>
                            </td>
                            <td class="py-2 px-2 align-middle text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Nouveau bouton Voir -->
                                    <a href="{{ route('admingen.students.show', $student->id) }}"
                                       class="btn btn-sm btn-outline-info rounded-pill hover-scale px-2"
                                       data-bs-toggle="tooltip" title="Voir détails">
                                        Détails
                                    </a>
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
@endsection
