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
                                    <i class="fas fa-edit me-2"></i>
                                    Modifier l'Assignation
                                </h2>
                                <p class="header-subtitle">Modifiez les informations de l'assignation</p>
                            </div>
                            <a href="{{ route('superadmin.assignations.index') }}" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-modern-body">
                        <!-- Info actuelle -->
                        <div class="info-current">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <strong>Assignation actuelle :</strong> 
                                {{ $assignation->formateur->name }} • {{ $assignation->subject->nom }} • {{ $assignation->site->nom }}
                            </div>
                        </div>

                        <form action="{{ route('superadmin.assignations.update', $assignation->id) }}" method="POST" id="editAssignationForm">
                            @csrf
                            @method('PUT')

                            <!-- Section 1 : Informations de base -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">1</div>
                                    <div>
                                        <h3 class="section-title">Informations de base</h3>
                                        <p class="section-subtitle">Modifiez le site et le formateur</p>
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
                                                @foreach($sites as $site)
                                                    <option value="{{ $site->id }}" {{ $assignation->site_id == $site->id ? 'selected' : '' }}>
                                                        {{ $site->nom }}
                                                    </option>
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
                                                @foreach($formateurs as $formateur)
                                                    <option value="{{ $formateur->id }}" {{ $assignation->formateur_id == $formateur->id ? 'selected' : '' }}>
                                                        {{ $formateur->name }}
                                                    </option>
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
                                        <p class="section-subtitle">Modifiez ou ajoutez des matières</p>
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
                                        @foreach($subjects as $subject)
                                            <label class="checkbox-modern">
                                                <input class="subject-checkbox" 
                                                       type="checkbox"
                                                       name="subject_ids[]" 
                                                       value="{{ $subject->id }}"
                                                       data-subject-id="{{ $subject->id }}"
                                                       id="subject_{{ $subject->id }}"
                                                       {{ $assignation->subject_id == $subject->id ? 'checked' : '' }}>
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
                                        <p class="section-subtitle">Modifiez les trimestres concernés</p>
                                    </div>
                                </div>

                                @php
                                    $currentTrimestres = $assignation->trimestres ?? [1, 2, 3];
                                @endphp

                                <div class="trimestre-pills">
                                    <label class="pill-modern pill-primary">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="1" id="trim1" 
                                               {{ in_array(1, $currentTrimestres) ? 'checked' : '' }}>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 1
                                        </span>
                                    </label>
                                    
                                    <label class="pill-modern pill-success">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="2" id="trim2" 
                                               {{ in_array(2, $currentTrimestres) ? 'checked' : '' }}>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 2
                                        </span>
                                    </label>
                                    
                                    <label class="pill-modern pill-info">
                                        <input class="trimestre-checkbox" type="checkbox" name="trimestres[]" value="3" id="trim3" 
                                               {{ in_array(3, $currentTrimestres) ? 'checked' : '' }}>
                                        <span class="pill-content">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Trimestre 3
                                        </span>
                                    </label>
                                </div>

                                <div class="hint-modern">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Les trimestres actuels sont pré-sélectionnés
                                </div>
                            </div>

                            <!-- Section 4 : Filières -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">4</div>
                                    <div>
                                        <h3 class="section-title">Filières</h3>
                                        <p class="section-subtitle">Modifiez les filières concernées</p>
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
                                                       id="filiere_{{ $filiere->id }}"
                                                       {{ in_array($filiere->id, $filiereIds ?? []) ? 'checked' : '' }}>
                                                <span class="checkbox-label">{{ $filiere->nom }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="form-actions">
                                <a href="{{ route('superadmin.assignations.index') }}" class="btn-modern btn-modern-light">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn-modern btn-modern-warning">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
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
        --warning: #F59E0B;
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
        background: linear-gradient(135deg, #FEF3C7 0%, #FFFFFF 100%);
        border-bottom: 2px solid #FCD34D;
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
        border-color: var(--warning);
        color: var(--warning);
        transform: translateX(-4px);
    }

    .card-modern-body {
        padding: 2.5rem;
    }

    /* 💡 Info actuelle */
    .info-current {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #FEF3C7 0%, #FFFBEB 100%);
        border: 1.5px solid #FCD34D;
        border-radius: var(--radius);
        margin-bottom: 2rem;
        color: #92400E;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #FCD34D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    /* 📋 Sections */
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
        background: linear-gradient(135deg, var(--warning) 0%, #FBBF24 100%);
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
        border-color: var(--warning);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    /* ✅ Checkboxes */
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
        border-color: var(--warning);
        background: #FFFBEB;
    }

    .checkbox-modern input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--warning);
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
        color: var(--warning);
        font-weight: 600;
    }

    /* 💊 Pills */
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

    .btn-modern-warning {
        background: var(--warning);
        color: white;
    }

    .btn-modern-warning:hover {
        background: #FBBF24;
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
        border-color: var(--warning);
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
document.addEventListener('DOMContentLoaded', function () {
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
            filiereInfo.style.display = 'none';
            return;
        }
        
        let relatedFilieres = [];
        selectedSubjects.forEach(subjectId => {
            if (subjectFilieres[subjectId]) {
                relatedFilieres = [...relatedFilieres, ...subjectFilieres[subjectId]];
            }
        });
        
        relatedFilieres = [...new Set(relatedFilieres)];
        
        if (relatedFilieres.length > 0) {
            filiereInfoText.textContent = `Les filières associées ont été mises à jour.`;
            filiereInfo.style.display = 'flex';
        }
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

    selectAllFilieres.addEventListener('change', function () {
        filiereCheckboxes.forEach(cb => cb.checked = this.checked);
        filiereInfo.style.display = 'none';
    });

    function updateSelectAllFilieres() {
        selectAllFilieres.checked = [...filiereCheckboxes].every(cb => cb.checked);
    }

    filiereCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateSelectAllFilieres();
            if (filiereInfo.style.display === 'flex') {
                filiereInfoText.textContent = 'Sélection modifiée manuellement.';
            }
        });
    });

    updateSelectAllFilieres();
    updateSelectAllSubjects();

    document.getElementById('editAssignationForm').addEventListener('submit', function(e) {
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
