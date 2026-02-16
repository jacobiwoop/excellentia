@php
    // ✅ Récupérer les trimestres disponibles depuis l'assignation
$availableTerms = $assignation->trimestres ?? [1, 2, 3];

// Récupération des étudiants
$students = \App\Models\Student::where('filiere_id', $assignation->filiere_id)
    ->where('site_id', $assignation->site_id)
    ->where('statut', 'en_cours')
    ->with([
        'grades' => function ($q) use ($assignation) {
            $q->where('assignation_id', $assignation->id);
            },
        ])
        ->get();
@endphp

@if ($students->isEmpty())
    <div class="empty-state-grades">
        <div class="empty-icon-grades">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="35" fill="#F8F9FA" />
                <path d="M30 35H50M30 40H50M30 45H42" stroke="#ADB5BD" stroke-width="2" stroke-linecap="round" />
            </svg>
        </div>
        <h4 class="empty-title-grades">Aucun étudiant trouvé</h4>
        <p class="empty-text-grades">Aucun étudiant actif n'est inscrit dans cette filière pour ce site.</p>
    </div>
@else
    <form action="{{ route('formateur.grades.store') }}" method="POST" id="grades-form-{{ $assignation->id }}"
        class="grades-form-modern">
        @csrf
        <input type="hidden" name="assignation_id" value="{{ $assignation->id }}">

        <!-- Info Banner minimaliste -->
        <div class="info-banner-minimal">

            <p class="info-hint">Saisissez les notes de 0 à 20 • Calcul automatique des moyennes</p>
        </div>

        <!-- Tableau minimaliste -->
        <div class="table-wrapper-minimal">
            <table class="grades-table-minimal">
                <thead>
                    <tr class="header-main">
                        <th rowspan="2" class="col-student" style="color: black">Étudiants</th>
                        @foreach ($availableTerms as $term)
                            @php
                                $colors = [
                                    1 => ['bg' => '#6366F1', 'light' => '#EEF2FF'],
                                    2 => ['bg' => '#10B981', 'light' => '#ECFDF5'],
                                    3 => ['bg' => '#F59E0B', 'light' => '#FEF3C7'],
                                ];
                                $color = $colors[$term] ?? ['bg' => '#6B7280', 'light' => '#F3F4F6'];
                            @endphp
                            <th colspan="6" class="col-term"
                                style="--term-bg: {{ $color['bg'] }}; --term-light: {{ $color['light'] }};">
                                Trimestre {{ $term }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="header-sub">
                        @foreach ($availableTerms as $term)
                            <th class="col-sub">Int 1</th>
                            <th class="col-sub">Int 2</th>
                            <th class="col-sub">Int 3</th>
                            <th class="col-sub moy">Moy Int</th>
                            <th class="col-sub">Devoir</th>
                            <th class="col-sub final">Moy Fin</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr class="row-student">
                            <td class="cell-student">
                                <div class="student-name">

                                    <span>{{ $student->nom_prenom }}</span>
                                </div>
                            </td>

                            @foreach ($availableTerms as $term)
                                @php
                                    $grade = $student->grades->firstWhere('term', $term);
                                @endphp

                                <!-- Interro 1 -->
                                <td class="cell-input">
                                    <input type="number" step="0.01" min="0" max="20"
                                        class="input-grade"
                                        name="grades[{{ $student->id }}][{{ $term }}][interro1]"
                                        value="{{ $grade->interro1 ?? '' }}" placeholder="—">
                                </td>

                                <!-- Interro 2 -->
                                <td class="cell-input">
                                    <input type="number" step="0.01" min="0" max="20"
                                        class="input-grade"
                                        name="grades[{{ $student->id }}][{{ $term }}][interro2]"
                                        value="{{ $grade->interro2 ?? '' }}" placeholder="—">
                                </td>

                                <!-- Interro 3 -->
                                <td class="cell-input">
                                    <input type="number" step="0.01" min="0" max="20"
                                        class="input-grade"
                                        name="grades[{{ $student->id }}][{{ $term }}][interro3]"
                                        value="{{ $grade->interro3 ?? '' }}" placeholder="—">
                                </td>

                                <!-- Moyenne Interros -->
                                <td class="cell-computed moy-interro moy-interro-{{ $term }}">
                                    <div class="computed-box">
                                        <span class="display-value">{{ $grade->moy_interro ?? '' }}</span>
                                    </div>
                                    <input type="hidden"
                                        name="grades[{{ $student->id }}][{{ $term }}][moy_interro]"
                                        value="{{ $grade->moy_interro ?? '' }}">
                                </td>

                                <!-- Devoir -->
                                <td class="cell-input">
                                    <input type="number" step="0.01" min="0" max="20"
                                        class="input-grade input-devoir"
                                        name="grades[{{ $student->id }}][{{ $term }}][devoir]"
                                        value="{{ $grade->devoir ?? '' }}" placeholder="—">
                                </td>

                                <!-- Moyenne Finale -->
                                <td class="cell-computed final moy-finale moy-finale-{{ $term }}">
                                    <div class="computed-box final-box">
                                        <span class="display-value">{{ $grade->moy_finale ?? '' }}</span>
                                    </div>
                                    <input type="hidden"
                                        name="grades[{{ $student->id }}][{{ $term }}][moy_finale]"
                                        value="{{ $grade->moy_finale ?? '' }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer minimaliste -->
        <div class="form-footer-minimal">
            <div class="footer-left">
                <i class="fas fa-info-circle"></i>
                <span>Les modifications sont sauvegardées pour tous les trimestres actifs</span>
            </div>
            <button type="submit" class="btn-save-minimal">
                <span>Enregistrer</span>
            </button>
        </div>
    </form>
@endif

<style>
    /* 🎨 Variables */
    :root {
        --color-indigo: #6366F1;
        --color-green: #10B981;
        --color-amber: #F59E0B;
        --color-text: #111827;
        --color-muted: #6B7280;
        --color-border: #E5E7EB;
        --color-bg: #F9FAFB;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        --radius: 12px;
    }

    /* 📭 État vide */
    .empty-state-grades {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--color-bg);
        border-radius: var(--radius);
    }

    .empty-icon-grades {
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .empty-title-grades {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .empty-text-grades {
        color: var(--color-muted);
        font-size: 0.95rem;
    }

    /* 📋 Formulaire */
    .grades-form-modern {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid var(--color-border);
    }

    /* 🎯 Banner minimaliste */
    .info-banner-minimal {
        background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #BFDBFE;
    }

    .info-flex {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .info-metrics {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }

    .metric-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--color-text);
        line-height: 1;
    }

    .metric-text {
        font-weight: 600;
        color: var(--color-text);
    }

    .info-terms {
        background: white;
        padding: 0.375rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--color-indigo);
    }

    .info-hint {
        font-size: 0.875rem;
        color: var(--color-muted);
        margin: 0;
    }

    /* 📊 Tableau minimaliste */
    .table-wrapper-minimal {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .grades-table-minimal {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* En-têtes */
    .header-main th {
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.875rem 1rem;
        text-align: center;
        border: none;
    }

    .col-student {
        position: sticky;
        left: 0;
        z-index: 3;
        text-align: left !important;
        min-width: 200px;
    }

    .col-term {
        background: var(--term-bg) !important;
    }

    .header-sub th {
        background: var(--color-bg);
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.625rem 0.5rem;
        border-bottom: 2px solid var(--color-border);
    }

    .col-sub.moy {
        background: #FEF3C7;
        color: #92400E;
    }

    .col-sub.final {
        background: #D1FAE5;
        color: #065F46;
    }

    /* Lignes */
    .row-student {
        transition: background 0.15s ease;
    }

    .row-student:hover {
        background: var(--color-bg);
    }

    .row-student:hover .student-avatar {
        transform: scale(1.05);
    }

    .cell-student {
        position: sticky;
        left: 0;
        z-index: 2;
        background: white;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--color-border);
        min-width: 200px;
    }

    .row-student:hover .cell-student {
        background: var(--color-bg);
    }

    .student-name {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .student-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--color-indigo), var(--color-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }

    .student-name>span {
        font-weight: 600;
        color: var(--color-text);
        font-size: 0.9rem;
    }

    /* Inputs */
    .cell-input {
        padding: 0.5rem;
        border-bottom: 1px solid var(--color-border);
        background: white;
    }

    .input-grade {
        width: 68px;
        height: 38px;
        border: 1.5px solid var(--color-border);
        border-radius: 6px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--color-text);
        transition: all 0.15s ease;
        background: white;
    }

    .input-grade::placeholder {
        color: #D1D5DB;
        font-weight: 400;
    }

    .input-grade:hover {
        border-color: #9CA3AF;
    }

    .input-grade:focus {
        outline: none;
        border-color: var(--color-indigo);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .input-grade.is-invalid {
        border-color: #EF4444;
        background: #FEE2E2;
    }

    .input-devoir {
        border-color: var(--color-amber);
        background: #FFFBEB;
    }

    .input-devoir:focus {
        border-color: var(--color-amber);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    /* Cellules calculées */
    .cell-computed {
        padding: 0.5rem;
        border-bottom: 1px solid var(--color-border);
        background: #FFFBEB;
    }

    .cell-computed.final {
        background: #ECFDF5;
    }

    .computed-box {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        border-radius: 6px;
        background: white;
        border: 1.5px solid #FDE68A;
    }

    .final-box {
        border-color: #6EE7B7;
    }

    .display-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--color-text);
    }

    /* Footer */
    .form-footer-minimal {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: var(--color-bg);
        border-top: 1px solid var(--color-border);
    }

    .footer-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-muted);
        font-size: 0.875rem;
    }

    .btn-save-minimal {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        background: var(--color-text);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: var(--shadow);
    }

    .btn-save-minimal:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-save-minimal:hover i {
        transform: translateX(3px);
    }

    .btn-save-minimal i {
        transition: transform 0.2s ease;
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .info-flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .form-footer-minimal {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .btn-save-minimal {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    (function() {
        'use strict';

        const availableTerms = @json($availableTerms);

        document.addEventListener('DOMContentLoaded', function() {
            const formId = 'grades-form-{{ $assignation->id }}';
            const form = document.getElementById(formId);

            if (!form) return;

            function calculateAverage(row, term) {
                if (!availableTerms.includes(parseInt(term))) return;

                const getVal = (field) => {
                    const input = row.querySelector(`input[name*="[${term}][${field}]"]`);
                    if (!input) return null;
                    const val = input.value.trim();
                    return val !== '' ? parseFloat(val) : null;
                };

                const interro1 = getVal('interro1');
                const interro2 = getVal('interro2');
                const interro3 = getVal('interro3');
                const devoir = getVal('devoir');

                const interros = [interro1, interro2, interro3].filter(v => v !== null && !isNaN(v));
                const moyInterro = interros.length > 0 ? interros.reduce((a, b) => a + b, 0) / interros
                    .length : null;
                const moyFinale = (moyInterro !== null && devoir !== null) ? (moyInterro + devoir) / 2 :
                    null;

                const moyInterroCell = row.querySelector(`.moy-interro-${term} .display-value`);
                const moyInterroInput = row.querySelector(`.moy-interro-${term} input[type="hidden"]`);
                const moyFinaleCell = row.querySelector(`.moy-finale-${term} .display-value`);
                const moyFinaleInput = row.querySelector(`.moy-finale-${term} input[type="hidden"]`);

                if (moyInterroCell) moyInterroCell.textContent = moyInterro !== null ? moyInterro.toFixed(
                    2) : '—';
                if (moyInterroInput) moyInterroInput.value = moyInterro !== null ? moyInterro.toFixed(2) :
                    '';
                if (moyFinaleCell) moyFinaleCell.textContent = moyFinale !== null ? moyFinale.toFixed(2) :
                    '—';
                if (moyFinaleInput) moyFinaleInput.value = moyFinale !== null ? moyFinale.toFixed(2) : '';
            }

            form.querySelectorAll('.input-grade').forEach(function(input) {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    const nameParts = this.name.match(/grades\[(\d+)\]\[(\d+)\]\[(\w+)\]/);
                    if (!nameParts) return;
                    const term = parseInt(nameParts[2]);
                    calculateAverage(row, term);
                });
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                let invalidCount = 0;

                form.querySelectorAll('.input-grade').forEach(function(input) {
                    const val = input.value.trim();
                    if (val !== '') {
                        const numVal = parseFloat(val);
                        if (isNaN(numVal) || numVal < 0 || numVal > 20) {
                            input.classList.add('is-invalid');
                            valid = false;
                            invalidCount++;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('❌ Erreur : ' + invalidCount +
                        ' note(s) invalide(s).\nLes notes doivent être entre 0 et 20.');
                }
            });
        });
    })();
</script>