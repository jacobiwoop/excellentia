@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2 class="h5 mb-0 fw-semibold text-gray-800">
                                <i class="fas fa-user-graduate me-2 text-gray"></i>Assigner un formateur
                            </h2>
                            <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-4 py-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-2 mb-4">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admingen.assignations.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="site_id" class="form-label fw-medium text-gray-700 mb-2">
                                        <i class="fas fa-building me-1 text-muted"></i> Site
                                    </label>
                                    <select name="site_id" id="site_id" class="form-select rounded-2 py-2 px-3 border-1"
                                        required>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Filière -->
                                <div class="col-md-12">
                                    <label class="form-label fw-medium text-gray-700 mb-2">
                                        <i class="fas fa-graduation-cap me-1 text-muted"></i> Filières
                                    </label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="selectAllFilieres">
                                        <label class="form-check-label fw-bold" for="selectAllFilieres">
                                            Toutes les filières
                                        </label>
                                    </div>
                                    <div class="row">
                                        @foreach($filieres as $filiere)
                                            <div class="col-md-6">
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input filiere-checkbox" type="checkbox"
                                                        name="filiere_ids[]" value="{{ $filiere->id }}"
                                                        id="filiere_{{ $filiere->id }}">
                                                    <label class="form-check-label"
                                                        for="filiere_{{ $filiere->id }}">{{ $filiere->nom }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <!-- Matière -->
                                <div class="col-md-6">
                                    <label for="subject_id" class="form-label fw-medium text-gray-700 mb-2">
                                        <i class="fas fa-book me-1 text-muted"></i> Matière
                                    </label>
                                    <select name="subject_id" id="subject_id"
                                        class="form-select rounded-2 py-2 px-3 border-1" required>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Formateur -->
                                <div class="col-md-6">
                                    <label for="formateur_id" class="form-label fw-medium text-gray-700 mb-2">
                                        <i class="fas fa-chalkboard-teacher me-1 text-muted"></i> Formateur
                                    </label>
                                    <select name="formateur_id" id="formateur_id"
                                        class="form-select rounded-2 py-2 px-3 border-1" required>
                                        @foreach($formateurs as $formateur)
                                            <option value="{{ $formateur->id }}">{{ $formateur->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 pt-4 mt-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                                    <i class="fas fa-save me-2"></i> Enregistrer
                                </button>
                                <button type="reset" class="btn btn-light px-4 py-2 rounded-2 fw-medium">
                                    <i class="fas fa-undo me-2"></i> Réinitialiser
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .form-control,
        .form-select {
            border: 1px solid #e0e0e0;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #e9ecef;
        }

        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }

        .text-muted {
            opacity: 0.7;
        }
    </style>
@endpush
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAllFilieres');
        const checkboxes = document.querySelectorAll('.filiere-checkbox');

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        function updateSelectAll() {
            const allChecked = [...checkboxes].every(cb => cb.checked);
            selectAll.checked = allChecked;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectAll);
        });

        updateSelectAll();
    });
</script>

@push('scripts')
    <script>
        // Script pour améliorer l'UX
        document.addEventListener('DOMContentLoaded', function () {
            // Focus sur le premier champ
            document.querySelector('form').elements[0].focus();

            // Animation des sélecteurs
            document.querySelectorAll('.form-select').forEach(select => {
                select.addEventListener('focus', function () {
                    this.parentNode.querySelector('label').style.color = '#0d6efd';
                });

                select.addEventListener('blur', function () {
                    this.parentNode.querySelector('label').style.color = '';
                });
            });
        });
    </script>
@endpush