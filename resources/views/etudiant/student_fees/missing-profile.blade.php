@extends('layouts.stu')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    <h2 class="h3 mb-0">Profil Étudiant Manquant</h2>
                </div>
                <div class="card-body text-center py-5">
                    <p class="lead">Désolé {{ $user->name }}, votre compte n'est pas associé à un profil étudiant.</p>
                    <p class="text-muted">Vous ne pouvez pas accéder aux fonctionnalités de paiement sans profil étudiant valide.</p>
                    
                    <div class="mt-4">
                        <p>Veuillez contacter l'administration pour résoudre ce problème :</p>
                        <a href="mailto:admin@votre-ecole.com" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i> Contacter l'administration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection