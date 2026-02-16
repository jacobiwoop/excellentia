@extends('layouts.dash')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #FF8C42 0%, #f77e33 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(255, 107, 157, 0.3);
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
            background: linear-gradient(135deg, #FF8C42 0%, #f77e33 100%);
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
            background-color: #fff8fa;
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
            border-color: #FF6B9D;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.15);
        }

        .btn-status {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
    </style>

    <div class="container-fluid mt-4">
        <!-- En-tête -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold text-white">
                        <i class="fas fa-users me-2 text-white"></i>Gestion des Étudiants
                    </h2>
                    <p class="mb-0" style="opacity: 0.9;">
                        Suivi des paiements et historiques
                        @if($siteId)
                            - {{ $sites->find($siteId)->nom }}
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('superadmin.student_fees.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Boutons statut -->
        <div class="mb-3">
            <a href="{{ route('superadmin.student_fees.etudiants', array_merge(request()->except('student_status'), ['student_status' => 'en_cours'])) }}"
                class="btn btn-status {{ $studentStatus === 'en_cours' ? 'btn-dark' : 'btn-outline-dark' }}">
                <i class="fas fa-user-graduate me-1"></i> En Formation
            </a>
            <a href="{{ route('superadmin.student_fees.etudiants', array_merge(request()->except('student_status'), ['student_status' => 'ancien'])) }}"
                class="btn btn-status {{ $studentStatus === 'ancien' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                <i class="fas fa-user-check me-1"></i> Anciens
            </a>
        </div>

        <!-- Filtres -->
        <div class="filter-bar">
            <form method="GET" class="row g-3">
                <input type="hidden" name="student_status" value="{{ $studentStatus }}">
                
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-building me-1" style="color: #333;"></i>Site</label>
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
                    <label class="fw-bold text-dark"><i class="fas fa-layer-group me-1" style="color: #333;"></i>Promotion</label>
                    <select name="promotion_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach ($promotions as $promo)
                            <option value="{{ $promo->id }}" {{ $promotionId == $promo->id ? 'selected' : '' }}>
                                {{ $promo->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-graduation-cap me-1" style="color: #333;"></i>Filière</label>
                    <select name="filiere_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ $filiereId == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="fw-bold text-dark"><i class="fas fa-file-invoice-dollar me-1" style="color: #333;"></i>Frais</label>
                    <select name="fee_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        @foreach ($fees as $fee)
                            <option value="{{ $fee->id }}" {{ $feeId == $fee->id ? 'selected' : '' }}>
                                {{ $fee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Barre de recherche -->
        <div class="filter-bar">
            <div class="search-box-custom">
                <input type="text" id="searchStudent" class="form-control" 
                       placeholder="Rechercher par nom, site, filière ou promotion...">
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                <span id="studentCount">{{ count($studentData) }}</span> étudiant(s) trouvé(s)
            </small>
        </div>

        <!-- Tableau des étudiants -->
        <div class="table-custom">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-white"><i class="fas fa-user me-1"></i>Étudiant</th>
                            <th class="text-white"><i class="fas fa-building me-1"></i>Site</th>
                            <th class="text-white"><i class="fas fa-layer-group me-1"></i>Promotion</th>
                            <th class="text-white"><i class="fas fa-graduation-cap me-1"></i>Filière</th>
                            <th class="text-white"><i class="fas fa-file-invoice me-1"></i>Frais</th>
                            <th class="text-white text-end"><i class="fas fa-calculator me-1"></i>Total</th>
                            <th class="text-white text-end"><i class="fas fa-check me-1"></i>Payé</th>
                            <th class="text-white text-end"><i class="fas fa-hourglass-half me-1"></i>Reste</th>
                            <th class="text-white text-center"><i class="fas fa-info-circle me-1"></i>Statut</th>
                            <th class="text-white text-center"><i class="fas fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse($studentData as $data)
                            <tr class="student-row" 
                                data-student-name="{{ strtolower($data['nom_prenom']) }}"
                                data-site="{{ strtolower($data['site']) }}"
                                data-filiere="{{ strtolower($data['filiere']) }}"
                                data-promotion="{{ strtolower($data['promotion']) }}">
                                <td>
                                    <strong class="text-dark">{{ $data['nom_prenom'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%); color: white; padding: 8px 12px;">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $data['site'] }}
                                    </span>
                                </td>
                                <td>{{ $data['promotion'] }}</td>
                                <td>{{ $data['filiere'] }}</td>
                                <td><small class="text-muted">{{ $data['fees'] }}</small></td>
                                <td class="text-end fw-bold">{{ number_format($data['total'], 0, ',', ' ') }} FCFA</td>
                                <td class="text-end text-success fw-bold">{{ number_format($data['paid'], 0, ',', ' ') }} FCFA</td>
                                <td class="text-end text-danger fw-bold">{{ number_format($data['reste'], 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">
                                    @if ($data['statut'] == 'Payé')
                                        <span class="badge bg-success" style="padding: 8px 15px; font-size: 0.85rem;">
                                            <i class="fas fa-check-circle me-1"></i>{{ $data['statut'] }}
                                        </span>
                                    @elseif($data['statut'] == 'Partiel')
                                        <span class="badge bg-warning" style="padding: 8px 15px; font-size: 0.85rem;">
                                            <i class="fas fa-clock me-1"></i>{{ $data['statut'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger" style="padding: 8px 15px; font-size: 0.85rem;">
                                            <i class="fas fa-times-circle me-1"></i>{{ $data['statut'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($data['student_fee_id'])
                                        <a href="{{ route('superadmin.student_fees.history', $data['student_fee_id']) }}"
                                            class="btn btn-sm btn-outline-dark" title="Voir l'historique">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr id="noStudentRow">
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                    <p>Aucun étudiant trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="mt-4">
                {{ $students->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Recherche en temps réel
        const searchStudent = document.getElementById('searchStudent');
        const studentRows = document.querySelectorAll('.student-row');
        const studentCount = document.getElementById('studentCount');
        const tableBody = document.getElementById('studentTableBody');

        searchStudent.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            let visibleCount = 0;

            studentRows.forEach(row => {
                const studentName = row.dataset.studentName;
                const site = row.dataset.site;
                const filiere = row.dataset.filiere;
                const promotion = row.dataset.promotion;

                if (studentName.includes(filter) || site.includes(filter) || filiere.includes(filter) || promotion.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            studentCount.textContent = visibleCount;

            const noStudentRow = document.getElementById('noStudentRow');
            if (visibleCount === 0 && !noStudentRow && studentRows.length > 0) {
                const tr = document.createElement('tr');
                tr.id = 'noStudentRow';
                tr.innerHTML = `
                    <td colspan="10" class="text-center text-muted py-5">
                        <i class="fas fa-search fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                        <p>Aucun résultat pour "<strong>${filter}</strong>"</p>
                    </td>
                `;
                tableBody.appendChild(tr);
            } else if (visibleCount > 0 && noStudentRow) {
                noStudentRow.remove();
            }
        });
    </script>
@endsection