@extends('layouts.for')

@section('content')
<div class="container-fluid px-4 py-1">
    
    <!-- Header sophistiqué -->
    <div class="page-header-modern mb-4">
        <!-- Breadcrumb minimaliste -->
        <div class="breadcrumb-modern mb-3">
            <a href="{{ route('formateur.grades.index') }}" class="breadcrumb-link">
                <i class="fas fa-arrow-left"></i>
                <span>Retour aux matières</span>
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="header-content">
            <!-- Gauche : Titre -->
            <div class="header-left">
                <h2 class="page-title">{{ $assignation->subject->nom }}</h2>
                <p class="page-subtitle">Gestion des évaluations</p>
            </div>

            <!-- Droite : Sélecteur et badge -->
            <div class="header-right">
                <div class="selector-wrapper">
                    <label class="selector-label">Classe</label>
                    <select class="selector-modern" id="classeSelector" onchange="changeClasse(this.value)">
                        @foreach($relatedAssignations as $related)
                            <option value="{{ $related->id }}" 
                                    {{ $related->id == $assignation->id ? 'selected' : '' }}>
                                {{ $related->filiere->nom }} - {{ $related->site->nom }}
                                @if($related->trimestres)
                                    • T{{ implode(', T', $related->trimestres) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="badge-modern">
                    <i class="fas fa-users"></i>
                    <span class="badge-number">{{ $students->count() }}</span>
                    <span class="badge-text">étudiant{{ $students->count() > 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau de notes -->
    @include('formateur.grades.partials.table', [
        'assignation' => $assignation,
        'students' => $students,
        'availableTerms' => $availableTerms
    ])

</div>

<style>
/* 🎨 Variables */
:root {
    --color-primary: #6366F1;
    --color-text: #111827;
    --color-muted: #6B7280;
    --color-border: #E5E7EB;
    --color-bg: #F9FAFB;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --radius: 12px;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* 📄 Header moderne */
.page-header-modern {
    padding-bottom: 1rem;
}

/* Breadcrumb minimaliste */
.breadcrumb-modern {
    display: inline-block;
}

.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-muted);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    background: var(--color-bg);
    color: var(--color-text);
}

.breadcrumb-link i {
    font-size: 0.75rem;
}

/* Contenu header */
.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: var(--radius);
    border: 1px solid var(--color-border);
    box-shadow: var(--shadow-sm);
}

/* Gauche */
.header-left {
    flex: 1;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-text);
    margin: 0;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

.page-subtitle {
    color: var(--color-muted);
    font-size: 0.95rem;
    margin: 0.5rem 0 0 0;
}

/* Droite */
.header-right {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
}

/* Sélecteur moderne */
.selector-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 300px;
}

.selector-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--color-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
}

.selector-modern {
    height: 44px;
    padding: 0 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--color-text);
    background: white;
    cursor: pointer;
    transition: var(--transition);
}

.selector-modern:hover {
    border-color: var(--color-primary);
}

.selector-modern:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Badge moderne */
.badge-modern {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
    padding: 0.75rem 1.25rem;
    border-radius: 10px;
    border: 1px solid #BFDBFE;
    white-space: nowrap;
}

.badge-modern i {
    color: var(--color-primary);
    font-size: 1.125rem;
}

.badge-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text);
    line-height: 1;
}

.badge-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text);
}

/* 📱 Responsive */
@media (max-width: 992px) {
    .header-content {
        flex-direction: column;
    }
    
    .header-right {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
    
    .selector-wrapper {
        min-width: auto;
        width: 100%;
    }
    
    .badge-modern {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .header-content {
        padding: 1rem;
    }
}
</style>

<script>
function changeClasse(assignationId) {
    window.location.href = "{{ route('formateur.grades.show', '') }}/" + assignationId;
}
</script>
@endsection