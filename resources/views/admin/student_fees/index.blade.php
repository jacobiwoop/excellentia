@extends('layouts.ad')

@section('content')
    <style>
        .container {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .search-box {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
        }

        #searchInput {
            padding-left: 45px;
            height: 50px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        #searchInput:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.15);
        }

        .table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, #e91e63 0%, #f5317f 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.1);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-primary {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            border: none;
            transition: all 0.3s;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        .btn-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            border: none;
            transition: all 0.3s;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-dark:hover {
            background: linear-gradient(135deg, #1a252f 0%, #0d1419 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
        }

        .btn-outline-primary {
            color: #e91e63;
            border: 2px solid #e91e63;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background-color: #e91e63;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            color: #6c757d;
            border: 2px solid #6c757d;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: none;
            border-left: 4px solid #dc3545;
            color: #721c24;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: none;
            border-left: 4px solid #28a745;
            color: #155724;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #dee2e6;
            transition: all 0.3s;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.15);
        }

        .reste-cell {
            font-weight: bold;
            color: #e91e63;
            font-size: 1.05rem;
        }

        .montant-paye-input {
            max-width: 140px;
            font-weight: 600;
        }

        .filter-container {
            background: linear-gradient(135deg, #e9ecef 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-en-cours {
            background-color: #d4edda;
            color: #155724;
        }

        .status-termine {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-abandonne {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-links a {
            display: block;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .action-links a:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateX(5px);
        }

        .input-group-text {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            color: white;
        }

        .stats-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-card h4 {
            color: #e91e63;
            font-weight: 700;
            margin: 0;
        }
       
        .stats-card p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.9rem;
        }

        /* Animation pour les lignes cachées/affichées */
        .table tbody tr.hidden {
            display: none;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
    </style>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- En-tête avec boutons statut -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-money-check-alt me-2"></i>Gestion des paiements</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.student_fees.index', array_merge(request()->except('student_status'), ['student_status' => 'en_cours', 'fee_id' => $feeId, 'promotion_id' => request('promotion_id')])) }}"
                    class="btn btn-sm {{ request('student_status', 'en_cours') === 'en_cours' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-user-graduate me-1"></i> En formation
                </a>
                <a href="{{ route('admin.student_fees.index', array_merge(request()->except('student_status'), ['student_status' => 'ancien', 'fee_id' => $feeId, 'promotion_id' => request('promotion_id')])) }}"
                    class="btn btn-sm {{ request('student_status') === 'ancien' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-user-check me-1"></i> Anciens
                </a>
                <a href="{{ route('admin.student_fees.index') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-redo me-1"></i>
                </a>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="search-box">
            <div class="search-input-wrapper">
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Rechercher un étudiant par nom, prénom ou filière...">
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                <span id="resultCount">{{ $students->count() }}</span> étudiant(s) trouvé(s)
            </small>
        </div>

        <!-- Filtres -->
        <div class="filter-container">
            <form method="GET" action="{{ route('admin.student_fees.index') }}" class="row g-3 align-items-end">
                <input type="hidden" name="student_status" value="{{ request('student_status', 'en_cours') }}">

                <div class="col-md-4">
                    <label for="promotion_id"><i class="fas fa-layer-group me-1"></i>Promotion :</label>
                    <select name="promotion_id" id="promotion_id" class="form-control">
                        <option value="">-- Toutes les promotions --</option>
                        @foreach ($promotions as $promo)
                            <option value="{{ $promo->id }}"
                                {{ request('promotion_id') == $promo->id ? 'selected' : '' }}>
                                {{ $promo->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="fee_id"><i class="fas fa-file-invoice-dollar me-1"></i>Type de frais :</label>
                    <select name="fee_id" id="fee_id" class="form-control" required>
                        <option value="">-- Sélectionner un type --</option>
                        @foreach ($fees as $fee)
                            <option value="{{ $fee->id }}"
                                {{ request('fee_id', $feeId) == $fee->id ? 'selected' : '' }}>
                                {{ $fee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Erreur :</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire de paiement -->
        <form method="POST" action="{{ route('admin.student_fees.store') }}">
            @csrf
            <input type="hidden" name="fee_id" value="{{ $feeId }}">

            <div class="row mb-4">
                <div class="col-md-6">
                    <label><i class="fas fa-calendar-alt me-1"></i>Date de paiement</label>
                    <input type="date" name="date_paiement" class="form-control"
                        value="{{ old('date_paiement', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label><i class="fas fa-credit-card me-1"></i>Mode de paiement</label>
                    <select name="mode_paiement" class="form-control" required>
                        <option value="espèce">Espèce</option>
                        <option value="chèque">Chèque</option>
                        <option value="virement">Virement</option>
                        <option value="mobile_money">Mobile Money</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-white fw-bold">Étudiant</th>
                            <th class="text-white fw-bold">Promotion</th>
                            <th class="text-white fw-bold">Frais</th>
                            <th class="text-white fw-bold">Total</th>
                            <th class="text-white fw-bold">Déjà payé</th>
                            <th class="text-white fw-bold">Nouveau paiement</th>
                            <th class="text-white fw-bold">Reste</th>
                            <th class="text-white fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse($students as $student)
                            @php
                                $studentFee = $student->studentFees->first();
                                $totalPayments = $studentFee ? $studentFee->payments->sum('montant') : 0;
                                $feeNom = $studentFee
                                    ? optional($studentFee->fee)->nom
                                    : ($feeId
                                        ? optional($fees->firstWhere('id', $feeId))->nom
                                        : 'Pas de frais');
                                $studentFeeId = $studentFee ? $studentFee->id : null;
                                $feeMontant = $studentFee ? $studentFee->montant_total : 0;
                                $feeReduction = $studentFee ? $studentFee->montant_reduction : 0;
                                $montantAvecReduction = max(0, $feeMontant - $feeReduction);
                                $reste = max(0, $montantAvecReduction - $totalPayments);
                            @endphp

                            <tr class="student-row" data-student-name="{{ strtolower($student->nom_prenom ?? $student->nom . ' ' . $student->prenom) }}" 
                                data-filiere="{{ strtolower(optional($student->filiere)->nom ?? '') }}">
                                <td>
                                    <strong>{{ $student->nom_prenom ?? $student->nom . ' ' . $student->prenom }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-graduation-cap me-1"></i>{{ optional($student->filiere)->nom }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ optional($student->promotion)->nom ?? '-' }}</span>
                                </td>
                                <td>{{ $feeNom }}</td>
                                <td><strong>{{ number_format($montantAvecReduction, 0, ',', ' ') }}</strong> FCFA</td>
                                <td>{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                    @if ($studentFeeId)
                                        <input type="hidden" name="student_fee_ids[]" value="{{ $studentFeeId }}">
                                    @endif
                                    <div class="input-group">
                                        <input type="number" name="montants_payes[]" value="0" min="0"
                                            max="{{ $reste }}" class="form-control montant-paye-input"
                                            data-total="{{ $feeMontant }}" data-reduction="{{ $feeReduction }}"
                                            data-paid="{{ $totalPayments }}" oninput="updateCalculs(this)" />
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                </td>
                                <td class="reste-cell">{{ number_format($reste, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if ($studentFee)
                                        <div class="action-links">
                                            <a href="{{ route('admin.student_fees.history', $studentFee->id) }}"
                                                class="text-info" style="text-decoration: none;">
                                                Historique
                                            </a>
                                            <a href="{{ route('admin.student_fees.reduction.form', $studentFee->id) }}"
                                                class="text-danger" style="text-decoration: none;">
                                                Réduction
                                            </a>
                                            <a href="{{ route('admin.student_fees.manage', $studentFee->id) }}"
                                                class="text-primary" style="text-decoration: none;">
                                                Modifier
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr id="noResultRow">
                                <td colspan="8" class="text-center text-muted no-results">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Aucun étudiant trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-dark btn-lg">
                    <i class="fas fa-save me-2"></i> Enregistrer les paiements
                </button>
            </div>
        </form>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const studentRows = document.querySelectorAll('.student-row');
        const resultCount = document.getElementById('resultCount');
        const tableBody = document.getElementById('studentTableBody');

        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            let visibleCount = 0;

            studentRows.forEach(row => {
                const studentName = row.dataset.studentName;
                const filiere = row.dataset.filiere;
                
                // Recherche dans le nom ET la filière
                if (studentName.includes(filter) || filiere.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Mise à jour du compteur
            resultCount.textContent = visibleCount;

            // Afficher un message si aucun résultat
            const noResultRow = document.getElementById('noResultRow');
            if (visibleCount === 0 && !noResultRow) {
                const tr = document.createElement('tr');
                tr.id = 'noResultRow';
                tr.innerHTML = `
                    <td colspan="8" class="text-center text-muted no-results">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>Aucun étudiant ne correspond à votre recherche : "<strong>${filter}</strong>"</p>
                    </td>
                `;
                tableBody.appendChild(tr);
            } else if (visibleCount > 0 && noResultRow) {
                noResultRow.remove();
            }
        });

        function updateCalculs(input) {
            const row = input.closest('tr');
            const total = parseFloat(input.dataset.total) || 0;
            const reduction = parseFloat(input.dataset.reduction) || 0;
            const alreadyPaid = parseFloat(input.dataset.paid) || 0;

            let payment = parseFloat(input.value) || 0;
            const maxPayment = total - reduction - alreadyPaid;

            if (payment > maxPayment) payment = input.value = maxPayment;
            if (payment < 0) payment = input.value = 0;

            const reste = Math.max(0, total - reduction - alreadyPaid - payment);
            row.querySelector('.reste-cell').textContent = reste.toLocaleString('fr-FR') + ' FCFA';
        }
    </script>
@endsection