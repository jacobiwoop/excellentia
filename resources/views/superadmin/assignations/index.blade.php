@extends('layouts.dash')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header élégant -->
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2 class="page-title mb-2">
                        <i class="fas fa-tasks me-2"></i>Gestion des Assignations
                    </h2>
                    <p class="page-subtitle mb-0">
                        {{ $assignationsGrouped->sum(fn($g) => $g['formateurs']->count()) }} formateur(s) • 
                        {{ $assignationsGrouped->count() }} site(s)
                    </p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <div class="search-box-modern">
                        <input type="text" 
                               id="searchInput" 
                               class="search-input-modern" 
                               placeholder="Rechercher un formateur...">
                    </div>
                    
                    <a href="{{ route('superadmin.assignations.create') }}" class="btn-modern btn-modern-primary">
                        <i class="fas fa-plus"></i>
                        <span>Nouvelle assignation</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close-modern" data-bs-dismiss="alert">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Onglets des sites uniquement -->
        <div class="tabs-wrapper mb-4">
            <div class="tabs-container">
                @foreach($assignationsGrouped as $siteId => $group)
                    <button class="tab-btn {{ $loop->first ? 'active' : '' }}" data-site="{{ $siteId }}">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $group['site']->nom }}
                        <span class="tab-badge">{{ $group['formateurs']->count() }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Contenu des sites -->
        @forelse($assignationsGrouped as $siteId => $group)
            <div class="site-content {{ $loop->first ? 'active' : '' }}" data-site-id="{{ $siteId }}">
                <div class="row g-3">
                    @foreach($group['formateurs'] as $formateurId => $formateurData)
                        <div class="col-12 col-md-6 col-lg-4 formateur-card-wrapper" 
                             data-formateur="{{ strtolower($formateurData['formateur']->name) }}">
                            
                            <div class="formateur-card">
                                <!-- Header de la carte -->
                                <div class="formateur-card-header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-modern">
                                            {{ strtoupper(substr($formateurData['formateur']->name, 0, 2)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="formateur-name">{{ $formateurData['formateur']->name }}</h5>
                                            <div class="formateur-meta">
                                                <span class="meta-item">
                                                    <i class="fas fa-book"></i>
                                                    {{ $formateurData['total_matieres'] }} matière{{ $formateurData['total_matieres'] > 1 ? 's' : '' }}
                                                </span>
                                                <span class="meta-separator">•</span>
                                                <span class="meta-item">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $group['site']->nom }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liste des matières -->
                                <div class="matieres-list">
                                    @foreach($formateurData['matieres'] as $matiereData)
                                        <div class="matiere-item">
                                            <div class="matiere-info">
                                                <div class="matiere-icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                                <div class="matiere-details">
                                                    <span class="matiere-name">{{ $matiereData['subject']->nom }}</span>
                                                    <span class="matiere-filiere">
                                                        @if($matiereData['filiere_display'] === 'Toutes les filières')
                                                            <i class="fas fa-check-double text-success me-1"></i>
                                                            {{ $matiereData['filiere_display'] }}
                                                        @else
                                                            <i class="fas fa-layer-group me-1"></i>
                                                            {{ $matiereData['filiere_display'] }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="matiere-actions">
                                                <a href="{{ route('superadmin.assignations.edit', $matiereData['assignation_id']) }}"
                                                   class="action-btn action-btn-edit"
                                                   data-bs-toggle="tooltip"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('superadmin.assignations.destroy.multiple') }}" 
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('⚠️ Supprimer cette assignation ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="site_id" value="{{ $group['site']->id }}">
                                                    <input type="hidden" name="formateur_id" value="{{ $formateurData['formateur']->id }}">
                                                    <input type="hidden" name="subject_id" value="{{ $matiereData['subject']->id }}">
                                                    
                                                    <button type="submit"
                                                            class="action-btn action-btn-delete"
                                                            data-bs-toggle="tooltip"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="empty-state-modern">
                <div class="empty-icon-modern">
                    <i class="fas fa-inbox"></i>
                </div>
                <h4 class="empty-title-modern">Aucune assignation</h4>
                <p class="empty-text-modern">Commencez par créer votre première assignation</p>
                <a href="{{ route('superadmin.assignations.create') }}" class="btn-modern btn-modern-primary mt-3">
                    <i class="fas fa-plus"></i>
                    <span>Créer une assignation</span>
                </a>
            </div>
        @endforelse
    </div>

        <style>
            /* 🎨 Variables */
            :root {
                --primary: #1a1a1a;
                --primary-light: #4B5563;
                --success: #10B981;
                --warning: #F59E0B;
                --danger: #EF4444;
                --light: #F9FAFB;
                --border: #E5E7EB;
                --text: #111827;
                --text-muted: #6B7280;
                --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
                --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
                --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
                --radius: 12px;
                --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* 📄 Header */
            .header-section {
                padding-bottom: 1.5rem;
                border-bottom: 2px solid var(--border);
            }

            .page-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--text);
                letter-spacing: -0.02em;
            }

            .page-subtitle {
                color: var(--text-muted);
                font-size: 0.95rem;
            }

            /* 🔍 Recherche moderne */
            .search-box-modern {
                position: relative;
                min-width: 280px;
            }

            .search-box-modern i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-muted);
                font-size: 0.9rem;
            }

            .search-input-modern {
                width: 100%;
                height: 44px;
                padding: 0 1rem 0 2.5rem;
                border: 1.5px solid var(--border);
                border-radius: var(--radius);
                font-size: 0.9rem;
                transition: var(--transition);
                background: white;
            }

            .search-input-modern:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
            }

            /* 🔘 Boutons modernes */
            .btn-modern {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                height: 44px;
                padding: 0 1.5rem;
                border: none;
                border-radius: var(--radius);
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
                transition: var(--transition);
                text-decoration: none;
            }

            .btn-modern-primary {
                background: var(--primary);
                color: white;
            }

            .btn-modern-primary:hover {
                background: var(--primary-light);
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
                color: white;
            }

            /* 🎯 Alert moderne */
            .alert-modern {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem 1.25rem;
                border-radius: var(--radius);
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
                transition: opacity 0.2s;
            }

            .btn-close-modern:hover {
                opacity: 1;
            }

            /* 📑 Onglets élégants */
            .tabs-wrapper {
                position: relative;
            }

            .tabs-container {
                display: flex;
                gap: 0.5rem;
                overflow-x: auto;
                padding-bottom: 0.5rem;
                scrollbar-width: thin;
            }

            .tabs-container::-webkit-scrollbar {
                height: 4px;
            }

            .tabs-container::-webkit-scrollbar-thumb {
                background: var(--border);
                border-radius: 4px;
            }

            .tab-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.25rem;
                background: white;
                border: 1.5px solid var(--border);
                border-radius: var(--radius);
                font-weight: 600;
                font-size: 0.9rem;
                color: var(--text-muted);
                cursor: pointer;
                transition: var(--transition);
                white-space: nowrap;
            }

            .tab-btn:hover {
                border-color: var(--primary);
                color: var(--primary);
            }

            .tab-btn.active {
                background: var(--primary);
                border-color: var(--primary);
                color: white;
            }

            .tab-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 20px;
                height: 20px;
                padding: 0 0.4rem;
                background: rgba(0, 0, 0, 0.15);
                border-radius: 10px;
                font-size: 0.75rem;
                font-weight: 700;
                margin-left: 0.5rem;
            }

            .tab-btn.active .tab-badge {
                background: rgba(255, 255, 255, 0.25);
            }

            /* 🎴 Cartes formateurs */
            .site-content {
                display: none;
            }

            .site-content.active {
                display: block;
                animation: fadeIn 0.4s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .formateur-card {
                background: white;
                border: 1.5px solid var(--border);
                border-radius: var(--radius);
                overflow: hidden;
                transition: var(--transition);
                height: 100%;
            }

            .formateur-card:hover {
                border-color: var(--primary);
                box-shadow: var(--shadow-lg);
                transform: translateY(-4px);
            }

            .formateur-card-header {
                padding: 1.25rem;
                background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%);
                border-bottom: 1.5px solid var(--border);
            }

            .avatar-modern {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .formateur-name {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--text);
                margin: 0 0 0.25rem 0;
            }

            .formateur-meta {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.8rem;
                color: var(--text-muted);
            }

            .meta-item {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
            }

            .meta-separator {
                opacity: 0.4;
            }

            /* 📚 Liste des matières */
            .matieres-list {
                padding: 0.5rem;
            }

            .matiere-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.875rem;
                border-radius: 8px;
                transition: var(--transition);
            }

            .matiere-item:hover {
                background: var(--light);
            }

            .matiere-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex: 1;
            }

            .matiere-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background: linear-gradient(135deg, #E0F2FE 0%, #DBEAFE 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #0369A1;
                font-size: 0.9rem;
                flex-shrink: 0;
            }

            .matiere-details {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .matiere-name {
                font-weight: 600;
                font-size: 0.9rem;
                color: var(--text);
            }

            .matiere-filiere {
                font-size: 0.8rem;
                color: var(--text-muted);
                display: flex;
                align-items: center;
            }

            /* 🎬 Actions */
            .matiere-actions {
                display: flex;
                gap: 0.5rem;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: var(--transition);
                font-size: 0.8rem;
            }

            .action-btn-edit {
                background: #FEF3C7;
                color: #92400E;
            }

            .action-btn-edit:hover {
                background: #FCD34D;
                transform: scale(1.1);
            }

            .action-btn-delete {
                background: #FEE2E2;
                color: #991B1B;
            }

            .action-btn-delete:hover {
                background: #FCA5A5;
                transform: scale(1.1);
            }

            /* 📭 État vide */
            .empty-state-modern {
                text-align: center;
                padding: 5rem 2rem;
            }

            .empty-icon-modern {
                width: 100px;
                height: 100px;
                margin: 0 auto 1.5rem;
                border-radius: 50%;
                background: var(--light);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2.5rem;
                color: var(--text-muted);
            }

            .empty-title-modern {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text);
                margin-bottom: 0.5rem;
            }

            .empty-text-modern {
                color: var(--text-muted);
                margin-bottom: 0;
            }

            /* 📑 Onglets élégants - CENTRÉS */
.tabs-wrapper {
    position: relative;
}

.tabs-container {
    display: flex;
    justify-content: center; /* ✅ CENTRÉ */
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scrollbar-width: thin;
    flex-wrap: wrap; /* ✅ Permet le retour à la ligne si besoin */
}

.tabs-container::-webkit-scrollbar {
    height: 4px;
}

.tabs-container::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}

.tab-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.25rem;
    background: white;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.tab-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.tab-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 0.4rem;
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-left: 0.5rem;
}

.tab-btn.active .tab-badge {
    background: rgba(255, 255, 255, 0.25);
} 

            /* 📱 Responsive */
            @media (max-width: 768px) {
                .search-box-modern {
                    min-width: 100%;
                }

                .formateur-card-header {
                    padding: 1rem;
                }

                .avatar-modern {
                    width: 44px;
                    height: 44px;
                }

                .formateur-name {
                    font-size: 1rem;
                }
            }
        </style>
 
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.tab-btn');
                const contents = document.querySelectorAll('.site-content');
                const searchInput = document.getElementById('searchInput');

                // Gestion des onglets
                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        tabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        const siteId = this.dataset.site;

                        contents.forEach(c => {
                            if (c.dataset.siteId === siteId) {
                                c.classList.add('active');
                            } else {
                                c.classList.remove('active');
                            }
                        });

                        searchInput.value = '';
                        document.querySelectorAll('.formateur-card-wrapper').forEach(w => w.style.display = '');
                    });
                });

                // Recherche
                searchInput.addEventListener('input', function() {
                    const term = this.value.toLowerCase();
                    const wrappers = document.querySelectorAll('.formateur-card-wrapper');

                    wrappers.forEach(wrapper => {
                        const formateur = wrapper.dataset.formateur;
                        wrapper.style.display = formateur.includes(term) ? '' : 'none';
                    });
                });

                // Tooltips
                const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltips.map(el => new bootstrap.Tooltip(el));
            });
        </script>
@endsection