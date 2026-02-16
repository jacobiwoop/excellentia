@extends('layouts.dash')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11 col-lg-10">
                <!-- Card principale -->
                <div class="card-modern">
                    <!-- Header -->
                    <div class="card-modern-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="header-title">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    Nouvelle Assignation
                                </h2>
                                <p class="header-subtitle">Assignez un formateur à des matières</p>
                            </div>
                            <a href="{{ route('superadmin.assignations.index') }}" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-modern-body">
                        @if (session('success'))
                            <div class="alert-modern alert-modern-success">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                                <button type="button" class="btn-close-modern" data-bs-dismiss="alert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('superadmin.assignations.store') }}" method="POST" id="assignationForm">
                            @csrf

                            <!-- Section 1 : Informations de base -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">1</div>
                                    <div>
                                        <h3 class="section-title">Informations de base</h3>
                                        <p class="section-subtitle">Sélectionnez le site et le formateur</p>
                                    </div>
                                </div>
                                
                                <div class="row g-4">
                                    <!-- Site -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="label-modern">
                                                <i class="fas fa-building me-2"></i>Site
                                            </label>
                                            <select name="site_id" id="site_id" class="select-modern" required>
                                                <option value="">Choisir un site...</option>
                                                @foreach ($sites as $site)
                                                    <option value="{{ $site->id }}">{{ $site->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Formateur -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="label-modern">
                                                <i class="fas fa-chalkboard-teacher me-2"></i>Formateur
                                            </label>
                                            <select name="formateur_id" id="formateur_id" class="select-modern" required>
                                                <option value="">Choisir un formateur...</option>
                                                @foreach ($formateurs as $formateur)
                                                    <option value="{{ $formateur->id }}">{{ $formateur->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2 : Matières -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">2</div>
                                    <div>
                                        <h3 class="section-title">Matières</h3>
                                        <p class="section-subtitle">Sélectionnez une ou plusieurs matières</p>
                                    </div>
                                </div>

                                <div class="checkbox-group">
                                    <div class="checkbox-header">
                                        <label class="checkbox-modern">
                                            <input type="checkbox" id="selectAllSubjects">
                                            <span class="checkbox-label-bold">Toutes les matières</span>
                                        </label>
                                    </div>
                                    
                                    <div class="checkbox-grid">
                                        @foreach ($subjects as $subject)
                                            <label class="checkbox-modern">
                                                <input class="subject-checkbox" 
                                                       type="checkbox"
                                                       name="subject_ids[]" 
                                                       value="{{ $subject->id }}"
                                                       data-subject-id="{{ $subject->id }}"
                                                       id="subject_{{ $subject->id }}">
                                                <span class="checkbox-label">{{ $subject->nom }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3 : Trimestres -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">3</div>
                                    <div>
                                        <h3 class="section-title">Trimestres actifs</h3>
                                        <p class="section-subtitle">Choisissez les trimestres concernés</p>
                                    </div>
                                </div>

                                <div class="trimestre-pills">
                                    <label class="pill-modern pill-primary">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="1" id="trim1" checked>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 1
                                        </span>
                                    </label>
                                    
                                    <label class="pill-modern pill-success">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="2" id="trim2" checked>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 2
                                        </span>
                                    </label>
                                    
                                    <label class="pill-modern pill-info">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="3" id="trim3" checked>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 3
                                        </span>
                                    </label>
                                </div>

                                <div class="hint-modern">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Ex: Si une matière n'est enseignée qu'au T1 et T3, décochez T2
                                </div>
                            </div>

                            <!-- Section 4 : Filières -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">4</div>
                                    <div>
                                        <h3 class="section-title">Filières</h3>
                                        <p class="section-subtitle">Sélectionnez les filières concernées</p>
                                    </div>
                                </div>

                                <!-- Info dynamique -->
                                <div id="filiereInfo" class="info-banner" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span id="filiereInfoText"></span>
                                </div>

                                <div class="checkbox-group">
                                    <div class="checkbox-header">
                                        <label class="checkbox-modern">
                                            <input type="checkbox" id="selectAllFilieres">
                                            <span class="checkbox-label-bold">Toutes les filières</span>
                                        </label>
                                    </div>
                                    
                                    <div class="checkbox-grid">
                                        @foreach($filieres as $filiere)
                                            <label class="checkbox-modern">
                                                <input class="filiere-checkbox" 
                                                       type="checkbox"
                                                       name="filiere_ids[]" 
                                                       value="{{ $filiere->id }}"
                                                       data-filiere-id="{{ $filiere->id }}"
                                                       id="filiere_{{ $filiere->id }}">
                                                <span class="checkbox-label">{{ $filiere->nom }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="form-actions">
                                <button type="reset" class="btn-modern btn-modern-light">
                                    <i class="fas fa-undo me-2"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn-modern btn-modern-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer l'assignation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* 🎨 Variables */
    :root {
        --primary: #1a1a1a;
        --primary-light: #4B5563;
        --success: #10B981;
        --info: #3B82F6;
        --light: #F9FAFB;
        --border: #E5E7EB;
        --text: #111827;
        --text-muted: #6B7280;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* 📄 Card moderne */
    .card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .card-modern-header {
        padding: 2rem 2.5rem;
        background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%);
        border-bottom: 2px solid var(--border);
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.02em;
    }

    .header-subtitle {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.95rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        background: white;
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        color: var(--text);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .btn-back:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateX(-4px);
    }

    .card-modern-body {
        padding: 2.5rem;
    }

    /* 🎯 Alert moderne */
    .alert-modern {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 2rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-modern-success {
        background: #ECFDF5;
        border: 1px solid #86EFAC;
        color: #166534;
    }

    .btn-close-modern {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
    }

    .btn-close-modern:hover {
        opacity: 1;
    }

    /* 📋 Sections du formulaire */
    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 2px dashed var(--border);
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-number {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 0.25rem 0;
    }

    .section-subtitle {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin: 0;
    }

    /* 📝 Form groups */
    .form-group-modern {
        margin-bottom: 0;
    }

    .label-modern {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .select-modern {
        width: 100%;
        height: 48px;
        padding: 0 1rem;
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        font-size: 0.9rem;
        color: var(--text);
        background: white;
        transition: var(--transition);
    }

    .select-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    /* ✅ Checkboxes modernes */
    .checkbox-group {
        background: var(--light);
        border-radius: var(--radius);
        padding: 1.5rem;
        border: 1.5px solid var(--border);
    }

    .checkbox-header {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .checkbox-grid::-webkit-scrollbar {
        width: 6px;
    }

    .checkbox-grid::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .checkbox-modern {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.75rem 1rem;
        background: white;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        margin: 0;
    }

    .checkbox-modern:hover {
        border-color: var(--primary);
        background: #FAFAFA;
    }

    .checkbox-modern input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .checkbox-label {
        font-size: 0.875rem;
        color: var(--text);
        cursor: pointer;
    }

    .checkbox-label-bold {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text);
        cursor: pointer;
    }

    .checkbox-modern input:checked ~ .checkbox-label,
    .checkbox-modern input:checked ~ .checkbox-label-bold {
        color: var(--primary);
        font-weight: 600;
    }

    /* 💊 Pills pour trimestres */
    .trimestre-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .pill-modern {
        position: relative;
        display: inline-block;
        margin: 0;
        cursor: pointer;
    }

    .pill-modern input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .pill-content {
        display: inline-flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        border: 2px solid var(--border);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-muted);
        background: white;
        transition: var(--transition);
    }

    .pill-modern:hover .pill-content {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .pill-primary input:checked ~ .pill-content {
        background: #EEF2FF;
        border-color: #6366F1;
        color: #4338CA;
    }

    .pill-success input:checked ~ .pill-content {
        background: #ECFDF5;
        border-color: var(--success);
        color: #065F46;
    }

    .pill-info input:checked ~ .pill-content {
        background: #EFF6FF;
        border-color: var(--info);
        color: #1E40AF;
    }

    /* 💡 Hint */
    .hint-modern {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #FEF3C7;
        border: 1px solid #FCD34D;
        border-radius: 8px;
        color: #92400E;
        font-size: 0.875rem;
    }

    /* 📢 Info banner */
    .info-banner {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: var(--radius);
        color: #1E40AF;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        animation: slideDown 0.3s ease;
    }

    /* 🔘 Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 2px solid var(--border);
    }

    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        height: 48px;
        padding: 0 2rem;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modern-primary {
        background: var(--primary);
        color: white;
    }

    .btn-modern-primary:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-modern-light {
        background: var(--light);
        color: var(--text);
        border: 1.5px solid var(--border);
    }

    .btn-modern-light:hover {
        background: #F3F4F6;
        border-color: var(--primary);
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .card-modern-header,
        .card-modern-body {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .checkbox-grid {
            grid-template-columns: 1fr;
        }

        .trimestre-pills {
            flex-direction: column;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectFilieres = @json($subjectFilieresMap);
        
        const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
        const filiereCheckboxes = document.querySelectorAll('.filiere-checkbox');
        const filiereInfo = document.getElementById('filiereInfo');
        const filiereInfoText = document.getElementById('filiereInfoText');
        const selectAllSubjects = document.getElementById('selectAllSubjects');
        const selectAllFilieres = document.getElementById('selectAllFilieres');

        function updateFilieresBasedOnSubjects() {
            const selectedSubjects = Array.from(subjectCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => parseInt(cb.dataset.subjectId));
            
            if (selectedSubjects.length === 0) {
                filiereCheckboxes.forEach(cb => cb.checked = false);
                filiereInfo.style.display = 'none';
                updateSelectAllFilieres();
                return;
            }
            
            let relatedFilieres = [];
            selectedSubjects.forEach(subjectId => {
                if (subjectFilieres[subjectId]) {
                    relatedFilieres = [...relatedFilieres, ...subjectFilieres[subjectId]];
                }
            });
            
            relatedFilieres = [...new Set(relatedFilieres)];
            
            filiereCheckboxes.forEach(cb => {
                const filiereId = parseInt(cb.dataset.filiereId);
                cb.checked = relatedFilieres.includes(filiereId);
            });
            
            if (relatedFilieres.length > 0) {
                filiereInfoText.textContent = `Les filières associées aux matières sélectionnées ont été pré-cochées.`;
                filiereInfo.style.display = 'flex';
            } else {
                filiereInfo.style.display = 'none';
            }
            
            updateSelectAllFilieres();
        }

        selectAllSubjects.addEventListener('change', function() {
            subjectCheckboxes.forEach(cb => cb.checked = this.checked);
            updateFilieresBasedOnSubjects();
        });

        function updateSelectAllSubjects() {
            selectAllSubjects.checked = [...subjectCheckboxes].every(cb => cb.checked);
        }

        subjectCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateSelectAllSubjects();
                updateFilieresBasedOnSubjects();
            });
        });

        selectAllFilieres.addEventListener('change', function() {
            filiereCheckboxes.forEach(cb => cb.checked = this.checked);
            filiereInfo.style.display = 'none';
        });

        function updateSelectAllFilieres() {
            selectAllFilieres.checked = [...filiereCheckboxes].every(cb => cb.checked);
        }

        filiereCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectAllFilieres);
        });

        document.getElementById('assignationForm').addEventListener('submit', function(e) {
            if (document.querySelectorAll('.subject-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner au moins une matière !');
                return false;
            }

            if (document.querySelectorAll('.trimestre-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner au moins un trimestre !');
                return false;
            }

            if (document.querySelectorAll('.filiere-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner au moins une filière !');
                return false;
            }
        });
    });
</script>
