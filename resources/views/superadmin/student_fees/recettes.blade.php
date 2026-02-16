@extends('layouts.dash')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }

        .filter-bar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table-custom thead th {
            background: #333;
            color: white;
            font-weight: 600;
            padding: 18px;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table-custom tbody tr {
            transition: all 0.3s;
        }

        .table-custom tbody tr:hover {
            background-color: #f0fff4;
        }

        .table-custom tbody td {
            padding: 18px;
            vertical-align: middle;
        }

        .search-box-custom {
            position: relative;
        }

        .search-box-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-box-custom input {
            padding-left: 45px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            height: 50px;
        }

        .search-box-custom input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }
    </style>

    <div class="container-fluid mt-4">
        <!-- En-tête -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold text-white">
                        <i class="fas fa-arrow-up me-2 text-white"></i>Détails des Recettes
                    </h2>
                    <p class="mb-0" style="opacity: 0.9;">
                        @if ($mois)
                            {{ \Carbon\Carbon::createFromDate($annee, $mois, 1)->translatedFormat('F Y') }}
                        @else
                            Année {{ $annee }}
                        @endif
                        @if ($siteId)
                            - {{ $sites->find($siteId)->nom }}
                        @else
                            - Tous les sites
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 fw-bold text-white">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</h3>
                    <small style="opacity: 0.9;">Total des recettes</small>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="filter-bar">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-building me-1 text-success"></i>Site</label>
                    <select name="site_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Tous les sites</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>
                                {{ $site->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-calendar me-1 text-success"></i>Mois</label>
                    <select name="mois" class="form-control" onchange="this.form.submit()">
                        <option value="">Toute l'année</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-calendar-alt me-1 text-success"></i>Année</label>
                    <select name="annee" class="form-control" onchange="this.form.submit()">
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('superadmin.student_fees.index') }}" class="btn btn-outline-dark w-100">
                        <i class="fas fa-arrow-left me-1"></i> Retour au Dashboard
                    </a>
                </div>
            </form>
        </div>

        <!-- Barre de recherche -->
        <div class="filter-bar">
            <div class="search-box-custom">
                <input type="text" id="searchRecette" class="form-control"
                    placeholder="Rechercher par motif, description, site ou ajouteur...">
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                <span id="recetteCount">{{ $recettes->count() }}</span> recette(s) trouvée(s)
            </small>
        </div>

        <!-- Tableau des recettes -->
        <div class="table-custom">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-white"><i class="fas fa-calendar me-1"></i>Date</th>
                            <th class="text-white"><i class="fas fa-file-alt me-1"></i>Motif</th>
                            <th class="text-white"><i class="fas fa-building me-1"></i>Site</th>
                            <th class="text-white"><i class="fas fa-user me-1"></i>Ajouté par</th>
                            <th class="text-white"><i class="fas fa-comment me-1"></i>Description</th>
                            <th class="text-white"><i class="fas fa-file-invoice me-1"></i>Justificatif</th>
                            <th class="text-white text-end"><i class="fas fa-money-bill-wave me-1"></i>Montant</th>
                        </tr>
                    </thead>
                    <tbody id="recetteTableBody">
                        @forelse($recettes as $recette)
                            <tr class="recette-row" data-motif="{{ strtolower($recette->motif) }}"
                                data-site="{{ strtolower($recette->site->nom ?? '') }}"
                                data-description="{{ strtolower($recette->description ?? '') }}"
                                data-ajouteur="{{ strtolower($recette->createdBy->name ?? 'système') }}">
                                <td>
                                    <strong
                                        class="text-dark">{{ \Carbon\Carbon::parse($recette->date_recette)->format('d/m/Y') }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($recette->date_recette)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $recette->motif }}</strong>
                                </td>
                                <td>
                                    <span class="badge"
                                        style="background: #333; color: white; padding: 8px 12px; font-size: 0.85rem;">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $recette->site->nom ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle me-1 text-success"></i>
                                    <strong class="text-dark">{{ $recette->createdBy->name ?? 'Système' }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($recette->created_at)->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $recette->description ?? '-' }}</small>
                                </td>
                                <td>
                                    @if ($recette->justificatif)
                                        <a href="{{ route('superadmin.student_fees.recettes.download', $recette->id) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success" style="font-size: 1rem; padding: 10px 15px;">
                                        +{{ number_format($recette->montant, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr id="noRecetteRow">
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                    <p>Aucune recette trouvée pour cette période.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($recettes->hasPages())
            <div class="mt-4">
                {{ $recettes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        // Recherche en temps réel
        const searchRecette = document.getElementById('searchRecette');
        const recetteRows = document.querySelectorAll('.recette-row');
        const recetteCount = document.getElementById('recetteCount');
        const tableBody = document.getElementById('recetteTableBody');

        searchRecette.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            let visibleCount = 0;

            recetteRows.forEach(row => {
                const motif = row.dataset.motif;
                const site = row.dataset.site;
                const description = row.dataset.description;
                const ajouteur = row.dataset.ajouteur;

                if (motif.includes(filter) || site.includes(filter) || description.includes(filter) ||
                    ajouteur.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            recetteCount.textContent = visibleCount;

            const noRecetteRow = document.getElementById('noRecetteRow');
            if (visibleCount === 0 && !noRecetteRow && recetteRows.length > 0) {
                const tr = document.createElement('tr');
                tr.id = 'noRecetteRow';
                tr.innerHTML = `
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-search fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                        <p>Aucun résultat pour "<strong>${filter}</strong>"</p>
                    </td>
                `;
                tableBody.appendChild(tr);
            } else if (visibleCount > 0 && noRecetteRow) {
                noRecetteRow.remove();
            }
        });
    </script>
@endsection
