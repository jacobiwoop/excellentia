@extends('layouts.for')

@section('content')
    <div class="container-fluid py-4">
        <div class="card rounded-4 overflow-hidden border-0 shadow-sm">
            
            <!-- Header noir élégant -->
            <div class="card-header bg-gradient-dark py-4 px-5 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0 text-dark fw-bold">
                        <i class="fas fa-users me-3"></i>Mes Étudiants
                    </h2>
                    <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm fw-bold">
                        {{ $filieres->sum(fn($f) => $f->students->count()) }} Étudiant{{ $filieres->sum(fn($f) => $f->students->count()) > 1 ? 's' : '' }}
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0"> 
                @if($filieres->isEmpty())
                    <!-- État vide -->
                    <div class="text-center py-5 my-4">
                        <div class="empty-icon mb-4">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Aucun étudiant trouvé</h5>
                        <p class="text-muted mb-4">Vous n'avez aucun étudiant actif dans vos classes assignées</p>
                    </div>
                @else
                    @php
                        // Grouper les filières par sites
                        $siteGroups = collect();
                        foreach ($filieres as $filiere) {
                            foreach ($filiere->sites as $site) {
                                $siteGroup = $siteGroups->firstWhere('site_id', $site->id);
                                
                                if (!$siteGroup) {
                                    $siteGroups->push([
                                        'site_id' => $site->id,
                                        'site_nom' => $site->nom,
                                        'filieres' => collect()
                                    ]);
                                    $siteGroup = $siteGroups->last();
                                }
                                
                                // Ajouter la filière avec ses étudiants filtrés par site
                                $filiereForSite = clone $filiere;
                                $filiereForSite->students = $filiere->students->filter(function($student) use ($site) {
                                    return $student->site_id === $site->id;
                                });
                                
                                if ($filiereForSite->students->count() > 0) {
                                    $siteGroup['filieres']->push($filiereForSite);
                                }
                            }
                        }
                        
                        $multipleSites = $siteGroups->count() > 1;
                    @endphp

                    @if($multipleSites)
                        <!-- Onglets par site -->
                        <ul class="nav nav-tabs nav-tabs-dark px-4 pt-3" id="siteTabs" role="tablist">
                            @foreach($siteGroups as $index => $group)
                                @php
                                    $totalStudents = $group['filieres']->sum(fn($f) => $f->students->count());
                                @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            id="site-tab-{{ $group['site_id'] }}"
                                            data-bs-toggle="tab" 
                                            data-bs-target="#site-{{ $group['site_id'] }}" 
                                            type="button"
                                            role="tab">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $group['site_nom'] }}
                                        <span class="badge bg-dark text-white ms-2">{{ $totalStudents }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Contenu des onglets -->
                        <div class="tab-content" id="siteTabsContent">
                            @foreach($siteGroups as $index => $group)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                     id="site-{{ $group['site_id'] }}"
                                     role="tabpanel">
                                    
                                    <!-- Accordion des filières -->
                                    <div class="accordion accordion-flush" id="accordion-{{ $group['site_id'] }}">
                                        @foreach($group['filieres'] as $filiere)
                                            <div class="accordion-item border-bottom">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed py-3 px-4" type="button"
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#filiere-{{ $filiere->id }}-site-{{ $group['site_id'] }}" 
                                                        aria-expanded="false">
                                                        <div class="d-flex align-items-center w-100">
                                                            <span class="student-count-badge me-3">
                                                                {{ $filiere->students->count() }}
                                                            </span>
                                                            <span class="fw-semibold">{{ $filiere->nom }}</span>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="filiere-{{ $filiere->id }}-site-{{ $group['site_id'] }}" 
                                                     class="accordion-collapse collapse" 
                                                     data-bs-parent="#accordion-{{ $group['site_id'] }}">
                                                    <div class="accordion-body p-0">
                                                        <div class="p-3 bg-light">
                                                            @include('formateur.students.partials.student-table', [
                                                                'students' => $filiere->students
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Un seul site : affichage direct -->
                        <div class="accordion accordion-flush" id="filieresAccordion">
                            @foreach($filieres as $filiere)
                                <div class="accordion-item border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-3 px-4" type="button"
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#filiere-{{ $filiere->id }}" 
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center w-100">
                                                <span class="student-count-badge me-3">
                                                    {{ $filiere->students->count() }}
                                                </span>
                                                <span class="fw-semibold flex-grow-1">{{ $filiere->nom }}</span>
                                                
                                                <!-- Badge site -->
                                                <span class="badge bg-secondary text-white">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $filiere->sites->first()->nom }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="filiere-{{ $filiere->id }}" 
                                         class="accordion-collapse collapse" 
                                         data-bs-parent="#filieresAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="p-3 bg-light">
                                                @include('formateur.students.partials.student-table', [
                                                    'students' => $filiere->students
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            <!-- Card Footer -->
            @if(!$filieres->isEmpty())
                <div class="card-footer bg-white py-3 px-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            @if($multipleSites ?? false)
                                Sélectionnez un site puis cliquez sur une filière
                            @else
                                Cliquez sur une filière pour voir les étudiants
                            @endif
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
/* 🎨 Gradient noir élégant */
.bg-gradient-dark {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

/* 🔢 Badge compteur noir */
.student-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: white;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1rem;
}

/* 📂 Onglets noirs */
.nav-tabs-dark {
    border-bottom: 2px solid #e9ecef;
    background: #f8f9fa;
}

.nav-tabs-dark .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 0;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.nav-tabs-dark .nav-link:hover {
    color: #1a1a1a;
    background: rgba(0, 0, 0, 0.03);
}

.nav-tabs-dark .nav-link.active {
    color: #1a1a1a;
    background: white;
    border-bottom: 3px solid #1a1a1a;
}

.nav-tabs-dark .nav-link .badge {
    font-size: 0.7rem;
    padding: 3px 8px;
}

/* 📂 Accordion */
.accordion-button:not(.collapsed) {
    background-color: rgba(0, 0, 0, 0.03);
    color: #1a1a1a;
    font-weight: 600;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0, 0, 0, 0.1);
}

.accordion-button {
    transition: all 0.3s ease;
}

.accordion-button:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* 📭 Empty state */
.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
}
</style>
@endpush
