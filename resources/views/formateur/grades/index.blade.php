@extends('layouts.for')

@section('content')
<div class="container-fluid px-4 py-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header minimaliste -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Mes Matières</h3>
        <p class="text-muted small mb-0">Sélectionnez une matière pour gérer les notes</p>
    </div>

    @if(isset($matiereGroups) && $matiereGroups->count() > 0)
        
        <!-- Grille de cartes élégantes -->
        <div class="row g-3">
            @foreach($matiereGroups as $subjectId => $group)
                @php
                    $colors = ['rose', 'orange', 'dark'];
                    $colorIndex = $loop->index % 3;
                    $color = $colors[$colorIndex];
                @endphp
                
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <!-- ✅ Carte entièrement cliquable -->
                    <a href="{{ route('formateur.grades.show', $group['assignations']->first()->id) }}" 
                       class="subject-card card-{{ $color }}" 
                       style="text-decoration: none;">
                        
                        <!-- Icône flottante -->
                        <div class="floating-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        
                        <!-- Contenu -->
                        <div class="card-content">
                            <h5 class="subject-title">{{ $group['subject']->nom }}</h5>
                            
                            <!-- Stats en ligne -->
                            <div class="stats-row">
                                <span class="stat-item">
                                    <i class="fas fa-users"></i>
                                    {{ $group['total_students'] }} étudiant{{ $group['total_students'] > 1 ? 's' : '' }}
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $group['count'] }} classe{{ $group['count'] > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            <!-- ✅ Indicateur visuel de clic -->
                            <div class="arrow-indicator">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    @else
        <!-- État vide stylé -->
        <div class="empty-box">
            <div class="empty-circle">
                <i class="fas fa-book-open"></i>
            </div>
            <h5 class="mt-3 mb-2">Aucune matière disponible</h5>
            <p class="text-muted small">Les classes sans étudiants actifs ne sont pas affichées</p>
        </div>
    @endif

</div>

<style>
/* 🎨 Palette de couleurs */
:root {
    --rose: #ff006e;
    --rose-light: #ffe7f1;
    --orange: #ff7f00;
    --orange-light: #fff1e0;
    --dark: #1a1a1a;
    --dark-light: #fff2f2;
}

/* 🎴 Carte principale (maintenant cliquable) */
.subject-card {
    position: relative;
    background: white;
    border-radius: 16px;
    padding: 24px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    border: 1px solid #e9ecef;
    cursor: pointer;
}

/* ✅ Fonds colorés très pâles selon la variante */
.card-rose {
    background: var(--rose-light);
}

.card-orange {
    background: var(--orange-light);
}

.card-dark {
    background: var(--dark-light);
}

/* ✅ Effet hover amélioré */
.subject-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.12);
}

.card-rose:hover {
    background: linear-gradient(135deg, var(--rose-light) 0%, #ffffff 100%);
}

.card-orange:hover {
    background: linear-gradient(135deg, var(--orange-light) 0%, #ffffff 100%);
}

.card-dark:hover {
    background: linear-gradient(135deg, var(--dark-light) 0%, #ffffff 100%);
}

/* 🎯 Icône flottante */
.floating-icon {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #6c757d;
    transition: all 0.3s ease;
}

.subject-card:hover .floating-icon {
    background: white;
    transform: scale(1.1);
}

/* 📝 Contenu */
.card-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.subject-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 16px;
    padding-right: 50px;
    line-height: 1.3;
}

/* 📊 Stats en ligne */
.stats-row {
    display: flex;
    gap: 12px;
    margin-bottom: auto;
    flex-wrap: wrap;
}

.stat-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.7);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    transition: all 0.3s ease;
}

.subject-card:hover .stat-item {
    background: white;
    transform: translateY(-2px);
}

.stat-item i {
    font-size: 0.75rem;
}

/* ✅ Flèche indicatrice (remplace le bouton) */
.arrow-indicator {
    margin-top: 16px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    color: #6c757d;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.card-rose:hover .arrow-indicator {
    color: var(--rose);
    transform: translateX(8px);
}

.card-orange:hover .arrow-indicator {
    color: var(--orange);
    transform: translateX(8px);
}

.card-dark:hover .arrow-indicator {
    color: var(--dark);
    transform: translateX(8px);
}

/* 📭 État vide */
.empty-box {
    text-align: center;
    padding: 80px 20px;
    background: #f8f9fa;
    border-radius: 20px;
    margin-top: 40px;
}

.empty-circle {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
}

.empty-box h5 {
    color: var(--dark);
    font-weight: 700;
}

/* 📱 Responsive */
@media (max-width: 576px) {
    .subject-card {
        padding: 20px 16px;
        min-height: 180px;
    }
    
    .subject-title {
        font-size: 1.05rem;
    }
    
    .floating-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .stat-item {
        font-size: 0.75rem;
        padding: 5px 10px;
    }
}

/* ✨ Animations */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.subject-card {
    animation: slideUp 0.5s ease-out backwards;
}

.subject-card:nth-child(1) { animation-delay: 0.05s; }
.subject-card:nth-child(2) { animation-delay: 0.1s; }
.subject-card:nth-child(3) { animation-delay: 0.15s; }
.subject-card:nth-child(4) { animation-delay: 0.2s; }
.subject-card:nth-child(5) { animation-delay: 0.25s; }
.subject-card:nth-child(6) { animation-delay: 0.3s; }
</style>
@endsection