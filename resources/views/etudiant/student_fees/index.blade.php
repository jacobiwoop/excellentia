@extends('layouts.stu')

@section('content')
    <div class="container-fluid px-4">
        <!-- En-tête amélioré -->
        <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-invoice-dollar text-info me-2"></i>Mes Paiements
                </h1>
                <p class="text-muted mb-0">Dernière mise à jour : {{ $lastUpdated }}</p>
            </div>
            <div class="mb-4">
                <a href="{{ route('etudiant.student_fees.full_history') }}" class="btn btn-outline-info">
                    <i class="fas fa-history"></i> Voir l'historique complet
                </a>
            </div>

        </div>

        <!-- Cartes indicateurs avec répartition par type de frais -->
        <div class="row g-4 mb-4">
            {{-- Total Standard --}}
            <div class="col-md-4">
                <div class="card custom-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold">Total Standard</span>
                            <h3 class="mt-2">{{ number_format($totalNormal, 0, ',', ' ') }} FCFA</h3>
                            <div class="mt-2">
                                @foreach($feesData as $fee)
                                    <small class="d-block text-muted">
                                        Frais de {{ $fee->type }} : {{ number_format($fee->montant_standard, 0, ',', ' ') }}
                                        FCFA
                                    </small>
                                @endforeach
                            </div>
                        </div>
                        <div class="custom-icon">
                            <i class="fas fa-file-invoice text-dark fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Payé --}}
            <div class="col-md-4">
                <div class="card custom-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold">Total Payé</span>
                            <h3 class="mt-2 text-success">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</h3>
                            <div class="mt-2">
                                @foreach($feesData as $fee)
                                    @if($fee->montant_paye > 0)
                                        <small class="d-block text-muted">
                                            {{ $fee->nom }} : {{ number_format($fee->montant_paye, 0, ',', ' ') }} FCFA
                                        </small>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="custom-icon">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reste à Payer --}}
            <div class="col-md-4">
                <div class="card custom-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold">Reste à Payer</span>
                            <h3 class="mt-2 text-warning">{{ number_format($totalRemaining, 0, ',', ' ') }} FCFA</h3>
                            <div class="mt-2">
                                @foreach($feesData as $fee)
                                    @if($fee->reste > 0)
                                        <small class="d-block text-muted">
                                            {{ $fee->nom }} : {{ number_format($fee->reste, 0, ',', ' ') }} FCFA
                                        </small>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="custom-icon">
                            <i class="fas fa-hourglass-half text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Détail des paiements amélioré -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>Détail de mes paiements par type de frais
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Type de Frais</th>
                            <th>Date Paiement</th>
                            <th class="text-end">Montant Total</th>
                            <th class="text-end">Montant Payé</th>
                            <th class="text-end">Reste</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentFees as $fee)
                            @php
                                $montantTotal = max(0, $fee->montant_total - $fee->montant_reduction);
                                $resteCumul = $montantTotal;
                                // Trier du plus ancien au plus récent pour calculer correctement le reste
                                $sortedPayments = $fee->payments->sortBy('date_paiement');
                            @endphp

                            @if($sortedPayments->isNotEmpty())
                                @php
                                    // Pour afficher du plus récent en haut, on inverse la collection après calcul du reste
                                    $paymentsWithReste = collect();
                                @endphp

                                @foreach($sortedPayments as $payment)
                                    @php
                                        $resteCumul -= $payment->montant;
                                        if ($resteCumul < 0)
                                            $resteCumul = 0;

                                        $paymentsWithReste->push([
                                            'payment' => $payment,
                                            'reste' => $resteCumul,
                                        ]);
                                    @endphp
                                @endforeach

                                {{-- Afficher du plus récent au plus ancien --}}
                                @foreach($paymentsWithReste->reverse() as $p)
                                    <tr style="font-size:1.1rem;">
                                        <td class="fw-bold">{{ $fee->fee->nom }}</td>
                                        <td class="fw-bold">{{ \Carbon\Carbon::parse($p['payment']->date_paiement)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-end text-success fw-bold">+{{ number_format($p['payment']->montant, 0, ',', ' ') }}
                                            FCFA</td>
                                        <td class="text-end fw-bold {{ $p['reste'] == 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($p['reste'], 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="text-center">
                                            @if($p['reste'] == $montantTotal)
                                                <span class="badge bg-danger fw-bold">Impayé</span>
                                            @elseif($p['reste'] > 0)
                                                <span class="badge bg-warning fw-bold">Partiel</span>
                                            @else
                                                <span class="badge bg-success fw-bold">Payé</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button onclick="generatePDF(event, {{ $p['payment']->id }})" class="btn btn-dark fw-bold">
                                                <i class="fas fa-file-pdf"></i> Exporter
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr style="font-size:1.1rem;">
                                    <td class="fw-bold">{{ $fee->fee->nom }}</td>
                                    <td>-</td>
                                    <td class="text-end fw-bold">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end text-success fw-bold">0 FCFA</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger fw-bold">Impayé</span>
                                    </td>
                                    <td class="text-center">-</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>


        </div>
    </div>

    <style>
        .card {
            border-radius: 0.5rem;
            border: none;
            transition: transform 0.2s;
        }


        .table th {
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }


        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
            min-width: 80px;
        }

        .progress {
            height: 6px;
            border-radius: 3px;
        }

        .generating-pdf {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        .custom-card {
            border-radius: 1rem;
            border: 1px solid #e0e0e0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .custom-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
        }

        .custom-card .card-body {
            padding: 1.5rem;
        }

        .custom-card h3 {
            font-weight: 600;
            font-size: 1.6rem;
        }

        .custom-icon {
            background: #f8f9fa;
            padding: 0.7rem;
            border-radius: 0.75rem;
        }
    </style>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        async function generatePDF(event, paymentId) {
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;

            try {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
                btn.disabled = true;

                // Récupérer le HTML
                const response = await fetch(`/etudiant/student-fees/receipt/${paymentId}`);
                if (!response.ok) throw new Error('Erreur de réseau');
                const html = await response.text();

                // Créer un élément temporaire
                const element = document.createElement('div');
                element.innerHTML = html;
                document.body.appendChild(element);

                // Options du PDF
                const opt = {
                    margin: 10,
                    filename: `recu_paiement_${paymentId}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        logging: true,
                        useCORS: true,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                // Génération du PDF
                await html2pdf().set(opt).from(element).save();
                element.remove();

            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la génération du PDF');
            } finally {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        }
    </script>
    <script>
        // Activer les tooltips Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endsection