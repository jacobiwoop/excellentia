@extends('layouts.dash')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>Suivi des Présences
            </h4>
        </div>

        <div class="card-body">
            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="alert alert-success p-2 text-center">
                        <strong>{{ $stats['present'] }}</strong> Présents
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-warning p-2 text-center">
                        <strong>{{ $stats['justified'] }}</strong> Justifiés
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-danger p-2 text-center">
                        <strong>{{ $stats['absent'] }}</strong> Absents
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-info p-2 text-center">
                        <strong>{{ $stats['total'] }}</strong> Total
                    </div>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Filière</th>
                            <th>Formateur</th>
                            <th class="text-center">Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle me-2" 
                                         style="width:30px;height:30px;display:flex;align-items:center;justify-content:center">
                                        <span class="text-primary fw-bold">{{ substr($attendance->student->nom_prenom, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $attendance->student->nom_prenom }}</div>
                                        <small class="text-muted">{{ $attendance->student->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $attendance->student->filiere->nom }}</td>
                            <td>{{ $attendance->formateur->name ?? '-' }}</td>
                            <td class="text-center">
                                @if($attendance->status == 1)
                                    <span class="badge bg-success">Présent</span>
                                @elseif($attendance->status == 2)
                                    <span class="badge bg-warning text-dark">Justifié</span>
                                @else
                                    <span class="badge bg-danger">Absent</span>
                                @endif
                            </td>
                            <td>{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection