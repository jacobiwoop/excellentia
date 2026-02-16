@extends('layouts.dash')

@section('content')
    <style>
        .kpi-card {
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            text-align: center;
            cursor: pointer;
            border: 2px solid #FF6B9D;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: #FF6B9D;
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.2);
        }

        .action-card i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            position: relative;
            height: 400px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        .site-stat-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 2px solid #f8f9fa;
            height: 100%;
        }

        .site-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 107, 157, 0.15);
            border-color: #FF6B9D;
        }

        .site-icon-small {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #FF6B9D 0%, #FF8C42 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .stat-box-compact {
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .stat-box-compact:hover {
            transform: translateX(3px);
        }

        .stat-box-compact.recettes {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.08) 0%, rgba(40, 167, 69, 0.03) 100%);
        }

        .stat-box-compact.depenses {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.08) 0%, rgba(220, 53, 69, 0.03) 100%);
        }

        .stat-box-compact.solde {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.08) 0%, rgba(255, 140, 66, 0.03) 100%);
        }

        .filter-compact {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 2px solid #f8f9fa;
        }

        .form-select-sm {
            height: 38px;
            font-size: 0.9rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-select-sm:focus {
            border-color: #FF6B9D;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.15);
        }

        .btn-outline-primary {
            border-color: #FF6B9D;
            color: #FF6B9D;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: #FF6B9D;
            border-color: #FF6B9D;
            color: white;
        }
    </style>

    <div class="container-fluid mt-4">

        <!-- Filtres simplifiés -->
        <!-- En-tête avec période -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">
                    <i class="fas fa-chart-line me-2" style="color: #FF6B9D;"></i>Tableau de Bord Comptabilité
                </h1>
                <p class="text-muted mb-0">{{ $periodeTexte }}</p>
            </div>
            <button class="btn btn-dark" type="button" data-bs-toggle="collapse" data-bs-target="#filtresCollapse">
                <i class="fas fa-filter me-1"></i> Filtres
            </button>
        </div>

        <!-- Filtres collapsibles et compacts -->
        <div class="collapse show" id="filtresCollapse">
            <div class="filter-compact mb-4">
                <form class="row g-2 align-items-end" method="GET">
                    <div class="col-md-3">
                        <label class="small fw-bold text-dark mb-1">
                            <i class="fas fa-building me-1" style="color: #FF6B9D; font-size: 0.85rem;"></i>Site
                        </label>
                        <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Tous les sites</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>
                                    {{ $site->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small fw-bold text-dark mb-1">
                            <i class="fas fa-calendar me-1" style="color: #FF6B9D; font-size: 0.85rem;"></i>Mois
                        </label>
                        <select name="mois" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Toute l'année</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small fw-bold text-dark mb-1">
                            <i class="fas fa-calendar-alt me-1" style="color: #FF6B9D; font-size: 0.85rem;"></i>Année
                        </label>
                        <select name="annee" class="form-select form-select-sm" onchange="this.form.submit()">
                            @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-1">
                        <a href="{{ route('superadmin.student_fees.index') }}"
                            class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-redo me-1"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPIs principaux -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="kpi-card" style="background: #FF6B9D;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-wallet text-dark" style="font-size: 1.2rem;"></i>
                                <small class="text-uppercase fw-bold text-white"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">Solde Caisse</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">
                                {{ number_format(abs($soldeCaisse), 0, ',', ' ') }}</h2>
                            <small class="d-inline-block mt-2 px-3 py-1 rounded"
                                style="background-color: rgba(255,255,255,0.3); font-weight: 600; color: white;">
                                {{ $soldeCaisse >= 0 ? 'Positif' : 'Négatif' }}
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3">
                            <i class="fas fa-piggy-bank fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="kpi-card" style="background: #28a745;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-arrow-down text-dark" style="font-size: 1.2rem;"></i>
                                <small class="text-uppercase fw-bold text-white"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">Recettes du mois</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">
                                {{ number_format($totalRecettes, 0, ',', ' ') }}</h2>
                            <small class="mt-2 d-block text-white" style="font-size: 0.75rem; opacity: 0.9;">
                                <i class="fas fa-calendar-check me-1"></i>{{ $periodeTexte }}
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3">
                            <i class="fas fa-chart-line fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="kpi-card" style="background: #dc3545;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-arrow-up text-dark" style="font-size: 1.2rem;"></i>
                                <small class="text-uppercase fw-bold text-white"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">Dépenses du mois</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">
                                {{ number_format($totalDepenses, 0, ',', ' ') }}</h2>
                            <small class="mt-2 d-block text-white" style="font-size: 0.75rem; opacity: 0.9;">
                                <i class="fas fa-calendar-check me-1"></i>{{ $periodeTexte }}
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3">
                            <i class="fas fa-chart-bar fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="kpi-card" style="background: linear-gradient(135deg, #FF8C42 0%, #FFA726 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-file-invoice-dollar text-white" style="font-size: 1rem;"></i>
                                <small class="text-uppercase fw-bold text-white"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Total À Percevoir
                                </small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 1.8rem;">
                                {{ number_format($scolariteAPercevoir, 0, ',', ' ') }}
                            </h2>
                            <small class="text-white d-block" style="font-size: 0.65rem; opacity: 0.85;">
                                <i class="fas fa-coins me-1"></i>FCFA
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-2 rounded-3">
                            <i class="fas fa-money-check-alt fa-lg text-white"></i>
                        </div>
                    </div>

                    <!-- ✅ Détails compacts par statut -->
                    <div class="mt-2 pt-2" style="border-top: 1px solid rgba(255,255,255,0.25);">
                        <!-- En cours -->
                        <div class="d-flex justify-content-between align-items-center mb-1 px-2 py-1 rounded"
                            style="background: rgba(40, 167, 69, 0.15);">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-user-graduate text-white" style="font-size: 0.7rem;"></i>
                                <span class="text-white" style="font-size: 0.7rem;">En cours</span>
                            </div>
                            <strong class="text-white" style="font-size: 0.75rem;">
                                {{ number_format($scolariteAPercevoirEnCours, 0, ',', ' ') }}
                            </strong>
                        </div>

                        <!-- Terminés -->
                        <div class="d-flex justify-content-between align-items-center mb-1 px-2 py-1 rounded"
                            style="background: rgba(255, 193, 7, 0.15);">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-check-circle text-white" style="font-size: 0.7rem;"></i>
                                <span class="text-white" style="font-size: 0.7rem;">Terminés</span>
                            </div>
                            <strong class="text-white" style="font-size: 0.75rem;">
                                {{ number_format($scolariteAPercevoirTermines, 0, ',', ' ') }}
                            </strong>
                        </div>

                        <!-- Abandonnés -->
                        <div class="d-flex justify-content-between align-items-center px-2 py-1 rounded"
                            style="background: rgba(220, 53, 69, 0.15);">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-user-times text-white" style="font-size: 0.7rem;"></i>
                                <span class="text-white" style="font-size: 0.7rem;">Abandonnés</span>
                            </div>
                            <strong class="text-white" style="font-size: 0.75rem;">
                                {{ number_format($scolariteAPercevoirAbandonnes, 0, ',', ' ') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bolt me-2" style="color: #FF6B9D;"></i>Actions Rapides
            </h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <a href="{{ route('superadmin.student_fees.recettes', request()->all()) }}"
                        class="text-decoration-none">
                        <div class="action-card">
                            <h5 class="fw-bold text-dark">Voir les Recettes</h5>
                            <p class="text-muted mb-0">Détails complets avec ajouteur et site</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('superadmin.student_fees.depenses', request()->all()) }}"
                        class="text-decoration-none">
                        <div class="action-card">
                            <h5 class="fw-bold text-dark">Voir les Dépenses</h5>
                            <p class="text-muted mb-0">Détails complets avec ajouteur et site</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('superadmin.student_fees.etudiants', request()->all()) }}"
                        class="text-decoration-none">
                        <div class="action-card">
                            <h5 class="fw-bold text-dark">Voir les Étudiants</h5>
                            <p class="text-muted mb-0">Paiements et historiques</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats paiements étudiants -->


        <!-- Graphiques -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="chart-container">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-chart-area me-2"
                            style="color: #FF6B9D;"></i>Évolution sur 6 mois</h5>
                    <div class="chart-wrapper">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="chart-container">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-chart-pie me-2"
                            style="color: #FF6B9D;"></i>Répartition des Paiements</h5>
                    <div class="chart-wrapper">
                        <canvas id="statutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par site (si pas de filtre) -->
        @if (!$siteId && count($sitesStats) > 0)
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-building me-2"
                        style="color: #FF6B9D;"></i>Répartition par Site</h5>
                <div class="row">
                    @foreach ($sitesStats as $stat)
                        <div class="col-md-4 mb-3">
                            <div class="site-stat-card">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="site-icon-small">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                                            {{ $stat['nom'] }}</h6>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="stat-box-compact recettes">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-arrow-up"
                                                        style="color: #28a745; font-size: 1rem;"></i>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Recettes</span>
                                                </div>
                                                <div class="text-end">
                                                    <strong class="text-success"
                                                        style="font-size: 0.9rem;">{{ number_format($stat['recettes'], 0, ',', ' ') }}</strong>
                                                    <small class="text-muted d-block"
                                                        style="font-size: 0.65rem;">FCFA</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="stat-box-compact depenses">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-arrow-down"
                                                        style="color: #dc3545; font-size: 1rem;"></i>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Dépenses</span>
                                                </div>
                                                <div class="text-end">
                                                    <strong class="text-danger"
                                                        style="font-size: 0.9rem;">{{ number_format($stat['depenses'], 0, ',', ' ') }}</strong>
                                                    <small class="text-muted d-block"
                                                        style="font-size: 0.65rem;">FCFA</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="stat-box-compact solde">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-wallet"
                                                        style="color: {{ $stat['solde'] >= 0 ? '#28a745' : '#dc3545' }}; font-size: 1rem;"></i>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Solde</span>
                                                </div>
                                                <div class="text-end">
                                                    <strong
                                                        class="{{ $stat['solde'] >= 0 ? 'text-success' : 'text-danger' }}"
                                                        style="font-size: 0.9rem;">
                                                        {{ number_format(abs($stat['solde']), 0, ',', ' ') }}
                                                    </strong>
                                                    <small class="text-muted d-block"
                                                        style="font-size: 0.65rem;">FCFA</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier que Chart.js est chargé
            if (typeof Chart === 'undefined') {
                console.error('Chart.js n\'est pas chargé');
                return;
            }

            // Graphique Évolution
            const evolutionCtx = document.getElementById('evolutionChart');
            if (evolutionCtx) {
                new Chart(evolutionCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($moisLabels) !!},
                        datasets: [{
                            label: 'Recettes',
                            data: {!! json_encode($evolutionRecettes) !!},
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#28a745',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }, {
                            label: 'Dépenses',
                            data: {!! json_encode($evolutionDepenses) !!},
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#dc3545',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' +
                                            new Intl.NumberFormat('fr-FR').format(context.parsed.y) +
                                            ' FCFA';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return new Intl.NumberFormat('fr-FR', {
                                            notation: 'compact'
                                        }).format(value);
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Graphique Statut Paiements
            const statutCtx = document.getElementById('statutChart');
            if (statutCtx) {
                new Chart(statutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Payé', 'Partiel', 'Non payé'],
                        datasets: [{
                            data: [{{ $countPaye }}, {{ $countPartiel }},
                                {{ $countNonPaye }}
                            ],
                            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.parsed + ' (' +
                                            percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
