@extends('layouts.ad')

@section('content')
    <div class="container-fluid py-4" style="background-color: #f8f9fa;">
        <!-- Header avec filtres -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="h3 mb-1 fw-bold" style="color: #2d3436;">
                    <i class="fas fa-chart-line me-2" style="color: #ff9320;"></i>
                    Tableau de bord
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Vue globale de votre gestion financière</p>
            </div>

            <!-- Filtres compacts -->
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="mois" class="form-select form-select-sm shadow-sm"
                    style="width: 140px; border: 1px solid #dee2e6;">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $mois == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="annee" class="form-select form-select-sm shadow-sm"
                    style="width: 100px; border: 1px solid #dee2e6;">
                    @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-dark btn-sm shadow-sm px-3">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </form>
        </div>

        <!-- 
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card-modern"
                    style="background: linear-gradient(135deg, {{ $soldeCaisse >= 0 ? '#FF876B ' : '#FFA07A' }} 0%, {{ $soldeCaisse >= 0 ? '#FF8C42' : '#FF6347' }} 100%);">
                    <div class="d-flex justify-content-between align-items-start text-dark">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-wallet" style="font-size: 1.2rem; opacity: 0.9;"></i>
                                <small class="text-uppercase text-white"
                                    style="font-weight: 600; font-size: 0.7rem; letter-spacing: 1px; opacity: 0.95;">Caisse
                                    Totale</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">
                                {{ number_format(abs($soldeCaisse), 0, ',', ' ') }}
                            </h2>
                            <small class="d-inline-block mt-2 px-2 py-1 rounded text-white"
                                style="background-color: rgba(255,255,255,0.25); font-size: 0.75rem; font-weight: 600;">
                                {{ $soldeCaisse >= 0 ? '✓ Positif' : '✗ Négatif' }}
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3" style="backdrop-filter: blur(10px);">
                            <i class="fas fa-piggy-bank fa-2x" style="opacity: 0.9;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="kpi-card-modern" style="background: linear-gradient(135deg, #FF6B9D 0%, #da107b 100%);">
                    <div class="d-flex justify-content-between align-items-start text-dark">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-file-invoice-dollar" style="font-size: 1.2rem; opacity: 0.9;"></i>
                                <small class="text-uppercase text-white"
                                    style="font-weight: 600; font-size: 0.7rem; letter-spacing: 1px; opacity: 0.95;">À
                                    Percevoir</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">
                                {{ number_format($scolariteAPercevoir, 0, ',', ' ') }}
                            </h2>
                            <small class="mt-2 d-block text-white" style="opacity: 0.9; font-size: 0.75rem;">
                                <i class="fas fa-graduation-cap me-1 text-white"></i>Total frais scolarité
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3" style="backdrop-filter: blur(10px);">
                            <i class="fas fa-chart-line fa-2x" style="opacity: 0.9;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card-modern" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <div class="d-flex justify-content-between align-items-start text-dark">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-check-circle" style="font-size: 1.2rem; opacity: 0.9;"></i>
                                <small class="text-uppercase text-white"
                                    style="font-weight: 600; font-size: 0.7rem; letter-spacing: 1px; opacity: 0.95;">Déjà
                                    Payé</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem; ">
                                {{ number_format($scolaritePayee, 0, ',', ' ') }}
                            </h2>
                            <small class="mt-2 d-block text-white" style="opacity: 0.9; font-size: 0.75rem;">
                                <i class="fas fa-percent me-1 text-white"></i>Taux : {{ $tauxRecouvrement }}%
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3" style="backdrop-filter: blur(10px);">
                            <i class="fas fa-money-check-alt fa-2x" style="opacity: 0.9;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="kpi-card-modern" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <div class="d-flex justify-content-between align-items-start text-dark">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-exclamation-triangle" style="font-size: 1.2rem; opacity: 0.9;"></i>
                                <small class="text-uppercase text-white"
                                    style="font-weight: 600; font-size: 0.7rem; letter-spacing: 1px; opacity: 0.95;">Reste à
                                    Payer</small>
                            </div>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem; ">
                                {{ number_format($scolariteRestante, 0, ',', ' ') }}
                            </h2>
                            <small class="mt-2 d-block text-white" style="opacity: 0.9; font-size: 0.75rem;">
                                <i class="fas fa-users me-1 text-white"></i>{{ $effectifEnCours }} étudiants actifs
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-3" style="backdrop-filter: blur(10px);">
                            <i class="fas fa-hourglass-half fa-2x" style="opacity: 0.9;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>  -->

        <!-- Section Titre : Période Filtrée -->
        <div class="mb-3">
            <h5 class="text-muted fw-bold">
                <i class="fas fa-calendar-alt me-2"></i>
                Période : {{ \Carbon\Carbon::create()->month($mois)->translatedFormat('F') }} {{ $annee }}
            </h5>
        </div>

        <!-- Détails Recettes et Dépenses DU MOIS -->
        <div class="row g-3 mb-4">
            <!-- Détail Recettes -->
            <div class="col-md-6">
                <div class="detail-card-modern">
                    <div class="detail-card-header-modern">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-badge" style="background-color: #28a74515;">
                                <i class="fas fa-coins" style="color: #28a745;"></i>
                            </div>
                            <span class="fw-bold">Recettes du Mois</span>
                        </div>
                    </div>
                    <div class="detail-card-body-modern">
                        <div class="detail-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="detail-icon" style="background-color: #ff932015;">
                                    <i class="fas fa-graduation-cap" style="color: #ff9320;"></i>
                                </div>
                                <div>
                                    <div class="detail-label-modern">Scolarité</div>
                                    <div class="detail-sublabel">Paiements étudiants</div>
                                </div>
                            </div>
                            <div class="detail-value-modern" style="color: #28a745;">
                                {{ number_format($recettesScolarite, 0, ',', ' ') }} <small>FCFA</small>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="detail-icon" style="background-color: #28a74515;">
                                    <i class="fas fa-hand-holding-usd" style="color: #28a745;"></i>
                                </div>
                                <div>
                                    <div class="detail-label-modern">Autres recettes</div>
                                    <div class="detail-sublabel">Ateliers, séminaires, etc.</div>
                                </div>
                            </div>
                            <div class="detail-value-modern" style="color: #28a745;">
                                {{ number_format($recettesAutres, 0, ',', ' ') }} <small>FCFA</small>
                            </div>
                        </div>
                        <div class="detail-divider-modern"></div>
                        <div class="detail-row">
                            <div class="detail-label-modern fw-bold" style="font-size: 1rem;">TOTAL MOIS</div>
                            <div class="detail-value-modern fw-bold" style="color: #28a745; font-size: 1.3rem;">
                                {{ number_format($recettesMois, 0, ',', ' ') }} <small
                                    style="font-size: 0.8rem;">FCFA</small>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                <i class="fas fa-file-invoice me-1"></i>{{ $nbRecettes }} enregistrement(s)
                            </small>
                        </div>
                    </div>
                    <div class="detail-card-footer-modern">
                        <a href="{{ route('admin.recettes.index') }}" class="detail-link-modern">
                            Voir toutes les recettes <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Détail Dépenses -->
            <div class="col-md-6">
                <div class="detail-card-modern">
                    <div class="detail-card-header-modern">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-badge" style="background-color: #dc354515;">
                                <i class="fas fa-money-bill-wave" style="color: #dc3545;"></i>
                            </div>
                            <span class="fw-bold">Dépenses du Mois</span>
                        </div>
                    </div>
                    <div class="detail-card-body-modern">
                        <div class="detail-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="detail-icon" style="background-color: #da107b15;">
                                    <i class="fas fa-user-tie" style="color: #da107b;"></i>
                                </div>
                                <div>
                                    <div class="detail-label-modern">Salaires formateurs</div>
                                    <div class="detail-sublabel">Rémunérations mensuelles</div>
                                </div>
                            </div>
                            <div class="detail-value-modern" style="color: #dc3545;">
                                {{ number_format($depensesSalaires, 0, ',', ' ') }} <small>FCFA</small>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="detail-icon" style="background-color: #6c757d15;">
                                    <i class="fas fa-shopping-cart" style="color: #6c757d;"></i>
                                </div>
                                <div>
                                    <div class="detail-label-modern">Autres dépenses</div>
                                    <div class="detail-sublabel">Loyer, fournitures, etc.</div>
                                </div>
                            </div>
                            <div class="detail-value-modern" style="color: #dc3545;">
                                {{ number_format($depensesAutres, 0, ',', ' ') }} <small>FCFA</small>
                            </div>
                        </div>
                        <div class="detail-divider-modern"></div>
                        <div class="detail-row">
                            <div class="detail-label-modern fw-bold" style="font-size: 1rem;">TOTAL MOIS</div>
                            <div class="detail-value-modern fw-bold" style="color: #dc3545; font-size: 1.3rem;">
                                {{ number_format($depensesMois, 0, ',', ' ') }} <small
                                    style="font-size: 0.8rem;">FCFA</small>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                <i class="fas fa-receipt me-1"></i>{{ $nbDepenses }} enregistrement(s)
                            </small>
                        </div>
                    </div>
                    <div class="detail-card-footer-modern">
                        <a href="{{ route('admin.depenses.index') }}" class="detail-link-modern">
                            Voir toutes les dépenses <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solde du Mois -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div
                    class="alert {{ $soldeMois >= 0 ? 'alert-success' : 'alert-danger' }} shadow-sm border-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem;">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Solde du Mois : {{ number_format(abs($soldeMois), 0, ',', ' ') }}
                                FCFA</h5>
                            <small>Recettes ({{ number_format($recettesMois, 0, ',', ' ') }}) - Dépenses
                                ({{ number_format($depensesMois, 0, ',', ' ') }})</small>
                        </div>
                    </div>
                    <div class="badge {{ $soldeMois >= 0 ? 'bg-success' : 'bg-danger' }}"
                        style="font-size: 1.2rem; padding: 0.75rem 1.5rem;">
                        {{ $soldeMois >= 0 ? 'Excédent' : 'Déficit' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Paiements Scolarité -->
        <div class="row g-3 mb-4">
            <div class="col-md-7">
                <div class="chart-card-modern">
                    <div class="chart-card-header-modern">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-badge" style="background-color: #6c757d15;">
                                <i class="fas fa-chart-pie" style="color: #6c757d;"></i>
                            </div>
                            <span class="fw-bold">Statut des Paiements ({{ ucfirst($feeType) }})</span>
                        </div>
                    </div>
                    <div class="chart-card-body-modern">
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <div class="stat-badge"
                                    style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                    <div class="stat-badge-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-badge-value">{{ $countPaye }}</div>
                                    <div class="stat-badge-label">Payés</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-badge"
                                    style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);">
                                    <div class="stat-badge-icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div class="stat-badge-value">{{ $countPartiel }}</div>
                                    <div class="stat-badge-label">Partiels</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-badge"
                                    style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                                    <div class="stat-badge-icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="stat-badge-value">{{ $countNonPaye }}</div>
                                    <div class="stat-badge-label">Non payés</div>
                                </div>
                            </div>
                        </div>
                        <div style="height: 220px; position: relative;">
                            <canvas id="dossiersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="chart-card-modern h-100">
                    <div class="chart-card-header-modern">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-badge" style="background-color: #28a74515;">
                                <i class="fas fa-percentage" style="color: #28a745;"></i>
                            </div>
                            <span class="fw-bold">Taux de Paiement ({{ ucfirst($feeType) }})</span>
                        </div>
                    </div>
                    <div class="chart-card-body-modern text-center d-flex flex-column justify-content-center">
                        <canvas id="progressCanvas" width="180" height="180" class="mx-auto mb-3"></canvas>

                        <div class="px-3">
                            <small class="text-muted d-block mb-2" style="font-size: 0.8rem;">Montant payé / Net à
                                payer</small>
                            <div class="d-flex justify-content-center gap-2 align-items-center flex-wrap">
                                <span class="badge"
                                    style="background-color: #28a74520; color: #28a745; font-size: 0.85rem; padding: 0.5rem 1rem;">
                                    {{ number_format($totalPayeScolarite, 0, ',', ' ') }} FCFA
                                </span>
                                <span style="color: #6c757d;">/</span>
                                <span class="badge"
                                    style="background-color: #6c757d15; color: #6c757d; font-size: 0.85rem; padding: 0.5rem 1rem;">
                                    {{ number_format($totalNet, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Dossiers avec animation
        const ctxDossiers = document.getElementById('dossiersChart').getContext('2d');
        new Chart(ctxDossiers, {
            type: 'doughnut',
            data: {
                labels: ['Payés', 'Partiels', 'Non payés'],
                datasets: [{
                    data: [{{ $countPaye }}, {{ $countPartiel }}, {{ $countNonPaye }}],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 15,
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '500'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Cercle de progression amélioré
        const canvas = document.getElementById('progressCanvas');
        const ctx = canvas.getContext('2d');
        const centerX = 90;
        const centerY = 90;
        const radius = 70;
        const percentage = {{ $tauxPaiement }};
        const lineWidth = 14;

        // Dessiner le cercle de fond
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = lineWidth;
        ctx.stroke();

        // Animation du cercle de progression
        let currentPercent = 0;
        const animationDuration = 1500;
        const framesPerSecond = 60;
        const totalFrames = (animationDuration / 1000) * framesPerSecond;
        const increment = percentage / totalFrames;

        function animateProgress() {
            if (currentPercent < percentage) {
                currentPercent += increment;
                if (currentPercent > percentage) currentPercent = percentage;

                // Effacer la zone du texte
                ctx.clearRect(centerX - radius, centerY - radius, radius * 2, radius * 2);

                // Redessiner le fond
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                ctx.strokeStyle = '#e9ecef';
                ctx.lineWidth = lineWidth;
                ctx.stroke();

                // Dessiner la progression
                const startAngle = -0.5 * Math.PI;
                const endAngle = startAngle + (currentPercent / 100) * 2 * Math.PI;

                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.strokeStyle = '#28a745';
                ctx.lineWidth = lineWidth;
                ctx.lineCap = 'round';
                ctx.stroke();

                // Dessiner le pourcentage
                ctx.fillStyle = '#28a745';
                ctx.font = 'bold 32px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(currentPercent.toFixed(1) + '%', centerX, centerY);

                requestAnimationFrame(animateProgress);
            }
        }

        // Démarrer l'animation
        window.addEventListener('load', animateProgress);
    </script>
@endsection

<style>
    /* KPI Cards Modernes */
    .kpi-card-modern {
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .kpi-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(40px, -40px);
    }

    .kpi-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Detail Cards Modernes */
    .detail-card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .detail-card-modern:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .detail-card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-card-body-modern {
        padding: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }

    .detail-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .detail-label-modern {
        font-weight: 600;
        color: #2d3436;
        font-size: 0.95rem;
    }

    .detail-sublabel {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .detail-value-modern {
        font-weight: 700;
        font-size: 1.1rem;
        text-align: right;
    }

    .detail-divider-modern {
        border-top: 2px dashed #dee2e6;
        margin: 1rem 0;
    }

    .detail-card-footer-modern {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-top: 1px solid #dee2e6;
    }

    .detail-link-modern {
        color: #495057;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .detail-link-modern:hover {
        color: #ff9320;
        transform: translateX(5px);
    }

    /* Chart Cards Modernes */
    .chart-card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
    }

    .chart-card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .chart-card-body-modern {
        padding: 1.5rem;
    }

    .icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    /* Stat Badges */
    .stat-badge {
        border-radius: 12px;
        padding: 1.25rem 1rem;
        text-align: center;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .stat-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .stat-badge-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }

    .stat-badge-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0.25rem 0;
    }

    .stat-badge-label {
        font-size: 0.75rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .kpi-card-modern {
            padding: 1.25rem;
        }

        .kpi-card-modern h2 {
            font-size: 1.5rem !important;
        }

        .detail-icon {
            width: 38px;
            height: 38px;
        }

        .stat-badge {
            padding: 1rem 0.75rem;
        }
    }
</style>
