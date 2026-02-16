@extends('layouts.ad')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0 fw-bold" style="color: #2d3436;">
                <i class="fas fa-coins me-2" style="color: #28a745;"></i>
                Recettes
            </h2>
            <p class="text-muted mb-0">Gestion des entrées d'argent</p>
        </div>
        <a href="{{ route('admin.recettes.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Ajouter une recette
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres et Statistiques -->
    <div class="row g-3 mb-4">
        <!-- Filtres -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.recettes.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mois</label>
                            <select name="mois" class="form-select">
                                <option value="">Tous les mois</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $mois == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Année</label>
                            <select name="annee" class="form-select">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="fas fa-filter me-1"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <small class="opacity-75">Total Recettes</small>
                            <h3 class="mb-0 fw-bold text-white">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 p-2 rounded">
                            <i class="fas fa-coins fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
                        <div class="flex-fill">
                            <small class="opacity-75 d-block">Scolarité</small>
                            <strong>{{ number_format($recettesScolarite, 0, ',', ' ') }}</strong>
                        </div>
                        <div class="flex-fill">
                            <small class="opacity-75 d-block">Autres</small>
                            <strong>{{ number_format($recettesAutres, 0, ',', ' ') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0">Date</th>
                            <th class="border-0">Motif</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Montant</th>
                            <th class="border-0">Justificatif</th>
                            <th class="border-0">Enregistré par</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recettes as $recette)
                            <tr>
                                <td>
                                    <span class="text-muted">{{ $recette->date_recette->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <strong>{{ $recette->motif }}</strong>
                                    @if($recette->description)
                                        <br><small class="text-muted">{{ Str::limit($recette->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($recette->is_automatique)
                                        <span class="badge" style="background-color: #ff9320;">
                                            <i class="fas fa-graduation-cap me-1"></i>Scolarité
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #28a745;">
                                            <i class="fas fa-hand-holding-usd me-1"></i>Autre
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color: #28a745;">{{ number_format($recette->montant, 0, ',', ' ') }} FCFA</strong>
                                </td>
                              <td>
    @if($recette->justificatif)
        <a href="{{ route('admin.recettes.download', $recette->id) }}" class="btn btn-sm btn-outline-dark">
            <i class="fas fa-file-download"></i>
        </a>
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $recette->createdBy->name }}<br>
                                        {{ $recette->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-end">
                                    @if(!$recette->is_automatique)
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.recettes.edit', $recette->id) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.recettes.destroy', $recette->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Auto</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">Aucune recette trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $recettes->links() }}
    </div>
</div>

<style>
.table thead th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.card {
    transition: all 0.3s ease;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection