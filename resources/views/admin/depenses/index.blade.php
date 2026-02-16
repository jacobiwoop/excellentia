@extends('layouts.ad')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0 fw-bold" style="color: #2d3436;">
                <i class="fas fa-money-bill-wave me-2" style="color: #dc3545;"></i>
                Dépenses
            </h2>
            <p class="text-muted mb-0">Gestion des sorties d'argent</p>
        </div>
        <a href="{{ route('admin.depenses.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> Ajouter une dépense
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
                    <form method="GET" action="{{ route('admin.depenses.index') }}" class="row g-3">
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
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <small class="opacity-75">Total Dépenses</small>
                            <h3 class="mb-0 fw-bold text-white">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 p-2 rounded">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
                        <div class="flex-fill">
                            <small class="opacity-75 d-block">Salaires</small>
                            <strong>{{ number_format($depensesSalaires, 0, ',', ' ') }}</strong>
                        </div>
                        <div class="flex-fill">
                            <small class="opacity-75 d-block">Autres</small>
                            <strong>{{ number_format($depensesAutres, 0, ',', ' ') }}</strong>
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
                        @forelse($depenses as $depense)
                            <tr>
                                <td>
                                    <span class="text-muted">{{ $depense->date_depense->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <strong>{{ $depense->motif }}</strong>
                                    @if($depense->description)
                                        <br><small class="text-muted">{{ Str::limit($depense->description, 50) }}</small>
                                    @endif
                                    @if($depense->formateur)
                                        <br><small class="text-primary">
                                            <i class="fas fa-user me-1"></i>{{ $depense->formateur->name }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($depense->is_salaire)
                                        <span class="badge" style="background-color: #da107b;">
                                            <i class="fas fa-user-tie me-1"></i>Salaire
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #6c757d;">
                                            <i class="fas fa-shopping-cart me-1"></i>Autre
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color: #dc3545;">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</strong>
                                </td>
                               <td>
    @if($depense->justificatif)
        <a href="{{ route('admin.depenses.download', $depense->id) }}" class="btn btn-sm btn-outline-dark" title="Télécharger le justificatif">
            <i class="fas fa-file-download"></i>
        </a>
    @else
        <span class="text-muted">
            <i class="fas fa-minus"></i>
        </span>
    @endif
</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $depense->createdBy->name }}<br>
                                        {{ $depense->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.depenses.edit', $depense->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.depenses.destroy', $depense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">Aucune dépense trouvée</p>
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
        {{ $depenses->links() }}
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