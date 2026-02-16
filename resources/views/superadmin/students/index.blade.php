@extends('layouts.dash')

@section('content')
    <div class="container-fluid px-3 py-4">
        <!-- Header et Filtres -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2 class="h4 fw-bold mb-0">
                    Liste des étudiants
                </h2>
                  <!-- Bouton Archives/Actifs (déplacé à gauche) -->
                    <a href="{{ route('superadmin.students.index', array_merge(request()->except('show_archives'), request('show_archives') ? [] : ['show_archives' => '1'])) }}"
                        class="btn btn-sm btn-{{ request('show_archives') ? 'success' : 'outline-secondary' }} rounded-pill hover-scale"
                        title="{{ request('show_archives') ? 'Afficher actifs' : 'Afficher archives' }}">
                        <i class="fas fa-{{ request('show_archives') ? 'users' : 'archive' }} me-1"></i>
                        {{ request('show_archives') ? 'Actifs' : 'Archives' }}
                    </a>
            </div>

            <div class="col-md-6 d-flex justify-content-end">
                <div class="filter-container p-2 rounded-3 d-flex align-items-center gap-2">
                    <!-- Filtre compact -->
                    <form action="{{ route('superadmin.students.index') }}" method="GET"
                        class="d-flex align-items-center gap-2 flex-grow-1">
                        <div class="position-relative flex-grow-1">
                            <select name="promotion_id" class="form-select form-select-sm border-0 bg-light rounded-pill">
                                <option value="">Toutes promotions</option>
                                @foreach($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                        {{ $promotion->nom }} ({{ \Carbon\Carbon::parse($promotion->date_debut)->format('Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="position-relative flex-grow-1">
                            <select name="filiere_id" class="form-select form-select-sm border-0 bg-light rounded-pill">
                                <option value="">Toutes filières</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(request('show_archives'))
                            <input type="hidden" name="show_archives" value="1">
                        @endif

                        <button type="submit" class="btn btn-sm btn-info rounded-circle hover-scale d-flex align-items-center justify-content-center" title="Filtrer" style="width: 36px; height: 36px;">
                            <i class="fas fa-search"></i>
                        </button>

                        @if(request()->hasAny(['promotion_id', 'filiere_id']))
                            <a href="{{ route('superadmin.students.index') }}{{ request('show_archives') ? '?show_archives=1' : '' }}"
                                class="btn btn-sm btn-outline-secondary rounded-circle hover-scale d-flex align-items-center justify-content-center" title="Réinitialiser" style="width: 36px; height: 36px;">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        @endif      
                    </form>

                    <a href="{{ route('superadmin.students.create') }}"
                        class="btn btn-sm btn-primary rounded-pill hover-scale ms-1 px-3" title="Ajouter étudiant">
                         Ajouter
                    </a>
                </div>
            </div>
        </div>

        <!-- Notification Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Barre d'actions groupées (cachée par défaut) -->
        <div id="bulkActions" class="card border-0 shadow-sm mb-3" style="display: none;">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold text-dark">
                            <i class="fas fa-check-double me-2 text-primary"></i>
                            <span id="selectedCount">0</span> étudiant(s) sélectionné(s)
                        </span>
                    </div>
                    <form id="bulkStatusForm" action="{{ route('superadmin.students.bulkUpdateStatut') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="student_ids" id="studentIdsInput">
                        
                        <button type="button" name="statut" value="en_cours" 
                                class="btn btn-sm btn-outline-warning rounded-pill bulk-status-btn px-3"
                                data-status="en_cours">
                            <i class="fas fa-play-circle me-1"></i> En cours
                        </button>

                        <button type="button" name="statut" value="termine" 
                                class="btn btn-sm btn-outline-success rounded-pill bulk-status-btn px-3"
                                data-status="termine">
                            <i class="fas fa-graduation-cap me-1"></i> Terminé
                        </button>

                        <button type="button" name="statut" value="abandonne" 
                                class="btn btn-sm btn-outline-danger rounded-pill bulk-status-btn px-3"
                                data-status="abandonne">
                            <i class="fas fa-times-circle me-1"></i> Abandonné
                        </button>

                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3" id="cancelSelection">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Carte du tableau -->
        <div class="card border-0 rounded-3 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 elegant-table">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th class="py-3 px-4 text-center" style="width: 50px;">
                                    <div class="form-check d-flex justify-content-center align-items-center m-0">
                                        <input type="checkbox" id="selectAll" class="form-check-input elegant-checkbox m-0">
                                    </div>
                                </th>
                                <th class="py-3 px-3">Photo</th>
                                <th class="py-3 px-3">Nom</th>
                                <th class="py-3 px-3">Matricule</th>
                                <th class="py-3 px-3">Sexe</th>
                                <th class="py-3 px-3">Téléphone</th>
                                <th class="py-3 px-3">Site</th>
                                <th class="py-3 px-3">Filière</th>
                           <!--      <th class="py-3 px-3">Statut</th>--> 
                                <th class="py-3 px-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr data-student-id="{{ $student->id }}" class="elegant-row">
                                    <td class="py-3 px-4 align-middle text-center">
                                        <div class="form-check d-flex justify-content-center align-items-center m-0">
                                            <input type="checkbox" class="form-check-input elegant-checkbox student-checkbox m-0" value="{{ $student->id }}">
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        @if($student->photo)
                                            <img src="{{ asset($student->photo) }}" alt="" 
                                                 width="45" height="45" class="rounded-circle shadow-sm elegant-avatar" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-gradient-light d-flex align-items-center justify-content-center elegant-avatar-placeholder"
                                                style="width: 45px; height: 45px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="fw-semibold text-dark">{{ $student->nom_prenom }}</span>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <code class="">{{ $student->matricule }}</code>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="badge rounded-pill elegant-badge badge-{{ $student->sexe === 'M' ? 'warning' : 'pink' }}">
                                            {{ $student->sexe }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="text-muted"><i class=""></i>{{ $student->telephone }}</span>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="badge rounded-pill">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $student->site->nom }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class=" rounded-pill">
                                            <i class="fas fa-graduation-cap me-1"></i>{{ $student->filiere->nom }}
                                        </span>
                                    </td>
                                <!--     <td class="py-3 px-3 align-middle">
                                        <span class="badge rounded-pill elegant-badge
                                            @if($student->statut === 'en_cours') badge-warning 
                                            @elseif($student->statut === 'termine') badge-success 
                                            @elseif($student->statut === 'abandonne') badge-danger 
                                            @endif">
                                            @if($student->statut === 'en_cours')
                                                <i class="fas fa-spinner me-1"></i>
                                            @elseif($student->statut === 'termine')
                                                <i class="fas fa-check-circle me-1"></i>
                                            @elseif($student->statut === 'abandonne')
                                                <i class="fas fa-ban me-1"></i>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $student->statut)) }}
                                        </span>
                                    </td> -->
                                    <td class="py-3 px-3 align-middle text-end">
                                        <div class="d-flex justify-content-end gap-2 align-items-center">
                                            <a href="{{ route('superadmin.students.show', $student->id) }}"
                                               class="btn btn-sm elegant-action-btn btn-action-info"
                                               title="Voir détails" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('superadmin.students.edit', $student->id) }}"
                                               class="btn btn-sm elegant-action-btn btn-action-warning"
                                               title="Modifier" data-bs-toggle="tooltip">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('superadmin.students.destroy', $student->id) }}" method="POST" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm elegant-action-btn btn-action-danger"
                                                        title="Supprimer"
                                                        data-bs-toggle="tooltip"
                                                        onclick="return confirm('Voulez-vous vraiment supprimer cet étudiant ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-50"></i>
                                            <p class="text-muted mb-0">Aucun étudiant {{ request('show_archives') ? 'archivé' : 'actif' }} trouvé.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="card-footer bg-transparent border-top py-3">
                    <div class="d-flex justify-content-center">
                        {{ $students->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Gradient principal */
        .bg-gradient-primary {
            color: white;
        }

       

        /* Table élégante */
        .elegant-table thead th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            border: none;
        }

        .elegant-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .elegant-row:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .elegant-row.selected {
            background-color: rgba(102, 126, 234, 0.08) !important;
            border-left: 3px solid #ffa24c;
        }

        /* Checkbox élégante */
        .elegant-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .elegant-checkbox:checked {
            background-color: #ffa24c;
            border-color: #ffa24c;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .elegant-checkbox:hover {
            border-color: #ffa24c;
        }

        /* Avatar */
        .elegant-avatar {
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .elegant-avatar:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .elegant-avatar-placeholder {
            border: 2px solid #e5e7eb;
        }

        /* Code matricule */
        .elegant-code {
            background: linear-gradient(135deg, #f0f0f0 0%, #e5e5e5 100%);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        /* Badges élégants */
        .elegant-badge {
            font-weight: 500;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .elegant-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .badge-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .badge-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
        }

        .badge-gray {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
        }

        .badge-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

       /* Boutons d'action élégants */
        .elegant-action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1.5px solid;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            position: relative;
        }

        .elegant-action-btn i {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .elegant-action-btn:hover i {
            transform: scale(1.1);
        }

        .btn-action-info {
            border-color: #06b6d4;
            color: #06b6d4;
        }

        .btn-action-info:hover {
            background: #06b6d4;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);
        }

        .btn-action-warning {
            border-color: #f59e0b;
            color: #f59e0b;
        }

        .btn-action-warning:hover {
            background: #f59e0b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .btn-action-danger {
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-action-danger:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        /* Animation hover scale */
        .hover-scale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Bulk actions */
        #bulkActions {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid #f59e0b;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Empty state */
        .empty-state {
            padding: 2rem;
        }

        /* Filter container */
        .filter-container {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .elegant-action-btn {
                width: 34px;
                height: 34px;
            }

            .elegant-badge {
                font-size: 0.7rem;
                padding: 4px 10px;
            }

            #bulkActions .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }

            .filter-container {
                flex-direction: column;
                align-items: stretch !important;
            }

            .filter-container form {
                width: 100%;
            }
        }

        /* Smooth scrolling */
        .table-responsive {
            scroll-behavior: smooth;
        }

        /* Focus states */
        .elegant-action-btn:focus,
        .elegant-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const studentIdsInput = document.getElementById('studentIdsInput');
            const cancelSelection = document.getElementById('cancelSelection');
            const bulkStatusBtns = document.querySelectorAll('.bulk-status-btn');

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            function updateBulkActions() {
                const checked = document.querySelectorAll('.student-checkbox:checked');
                const count = checked.length;
                
                if (count > 0) {
                    bulkActions.style.display = 'block';
                    selectedCount.textContent = count;
                    
                    const ids = Array.from(checked).map(cb => cb.value);
                    studentIdsInput.value = JSON.stringify(ids);
                    
                    checked.forEach(cb => {
                        cb.closest('tr').classList.add('selected');
                    });
                } else {
                    bulkActions.style.display = 'none';
                    document.querySelectorAll('tr.selected').forEach(tr => {
                        tr.classList.remove('selected');
                    });
                }
                
                selectAll.checked = count === studentCheckboxes.length && count > 0;
                selectAll.indeterminate = count > 0 && count < studentCheckboxes.length;
            }

            selectAll.addEventListener('change', function() {
                studentCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkActions();
            });

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });

            cancelSelection.addEventListener('click', function() {
                studentCheckboxes.forEach(cb => cb.checked = false);
                selectAll.checked = false;
                updateBulkActions();
            });

            bulkStatusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const status = this.dataset.status;
                    const count = document.querySelectorAll('.student-checkbox:checked').length;
                    
                    let confirmMessage = '';
                    
                    switch(status) {
                        case 'en_cours':
                            confirmMessage = `Confirmer le passage de ${count} étudiant(s) au statut "En cours" ?`;
                            break;
                        case 'termine':
                            confirmMessage = `⚠️ Confirmer que ${count} étudiant(s) ont terminé leur formation ?`;
                            break;
                        case 'abandonne':
                            confirmMessage = `⚠️ ATTENTION : Confirmer l'abandon de ${count} étudiant(s) ?\n\nCette action marquera ces étudiants comme ayant abandonné leur formation.`;
                            break;
                    }
                    
                    if (confirm(confirmMessage)) {
                        const statusInput = document.createElement('input');
                        statusInput.type = 'hidden';
                        statusInput.name = 'statut';
                        statusInput.value = status;
                        
                        const form = document.getElementById('bulkStatusForm');
                        form.appendChild(statusInput);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection