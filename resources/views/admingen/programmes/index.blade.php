@extends('layouts.app')

@section('content')
    <div class="container-fluid px-0 px-md-3">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-header text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold">Gestion des Programmes</h4>
                <a href="{{ route('admingen.programmes.create') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-plus-circle mr-1"></i> Nouveau Programme
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light-primary">
                            <tr>
                                <th class="py-3 px-3 text-nowrap">Date</th>
                                <th class="py-3 px-3 text-nowrap">Horaire</th>
                                <th class="py-3 px-3">Matière</th>
                                <th class="py-3 px-3">Formateur</th>
                                <th class="py-3 px-3">Site</th>
                                <th class="py-3 px-3">Filières</th>
                                <th class="py-3 px-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programmes as $programme)
                                <tr>
                                    <td class="py-3 px-3 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="font-weight-bold">{{ $programme->date_seance->format('d/m/Y') }}</div>
                                            <small
                                                class="text-info text-uppercase ms-2">{{ $programme->date_seance->isoFormat('dddd') }}</small>
                                        </div>
                                    </td>

                                    <td class="py-3 px-3 align-middle">
                                        <span>{{ $programme->heure_debut->format('H:i') }} -
                                            {{ $programme->heure_fin->format('H:i') }}</span>
                                    </td>

                                    <td class="py-3 px-3 align-middle">
                                        {{ $programme->titre_custom ?? optional($programme->subject)->nom ?? 'Non spécifié' }}
                                    </td>

                                    <td class="py-3 px-3 align-middle">
                                        {{ optional($programme->formateur)->name ?? 'Non attribué' }}
                                    </td>

                                    <td class="py-3 px-3 align-middle">
                                        {{ optional($programme->site)->nom ?? 'Non précisé' }}
                                    </td>


                                    <td class="py-3 px-3 align-middle">
                                        @php
                                            $nbTotalFilieres = \App\Models\Filiere::count();
                                            $nbFilieresProgramme = $programme->filieres->count();
                                        @endphp

                                        @if ($nbFilieresProgramme === $nbTotalFilieres)
                                            <span class="badge bg-success text-white">Toutes les filières</span>
                                        @elseif ($nbFilieresProgramme > 1)
                                            <span class="badge text-white">{{ $nbFilieresProgramme }} filières sélectionnées</span>
                                        @elseif ($nbFilieresProgramme === 1)
                                            <span class="badge text-white">{{ $programme->filieres->first()->nom }}</span>
                                        @else
                                            <span class="text-muted">Aucune filière</span>
                                        @endif
                                    </td>




                                    <td class="py-3 px-3 align-middle text-center">
                                        <a href="{{ route('admingen.programmes.edit', $programme->id) }}"
                                            class="btn btn-outline-warning btn-sm mx-1" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admingen.programmes.destroy', $programme->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm mx-1"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-muted">
                                        <i class="far fa-calendar-times fa-3x mb-3"></i>
                                        <h5>Aucun programme trouvé</h5>
                                        <p>Commencez par créer un nouveau programme</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($programmes->hasPages())
                    <div class="card-footer bg-white border-top-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $programmes->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection