@extends('layouts.for')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Card -->
    <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-header text-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h5 class="mb-2 mb-md-0">
                    <i class="fas fa-calendar-check me-2"></i>Gestion des Présences
                </h5>
                
                <form method="GET" class="d-flex align-items-center mt-2 mt-md-0">
                    <input type="date" name="date" value="{{ $selectedDate }}" 
                           class="form-control form-control-sm me-2" 
                           style="max-width: 150px;"
                           max="{{ date('Y-m-d') }}"
                           min="{{ date('Y-m-d') }}"
                           onchange="this.form.submit()">
                    <button type="submit" class="btn btn-sm btn-light">
                        <i class="fas fa-filter"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Stats Bar -->
        <div class="card-body py-2 bg-light">
            <div class="row text-center">
                <div class="col-4">
                    <div class="p-2 bg-opacity-10 rounded-3">
                        <h6 class="mb-0 text-success fw-bold" style="font-size: 25px;">{{ $stats['present'] }}</h6>
                        <p class="text-success">Présents</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2  bg-opacity-10 rounded-3">
                        <h6 class="mb-0 text-dark fw-bold" style="font-size: 25px;">{{ $stats['justified'] }}</h6>
                        <p class="text-dark">Justifiés</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-opacity-10 rounded-3">
                        <h6 class="mb-0 text-danger fw-bold" style="font-size: 25px;">{{ $stats['absent'] }}</h6>
                        <p class="text-danger">Absents</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <form method="POST" action="{{ route('formateur.attendance.store') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">
        
        @foreach($filieres as $filiere)
            <div class="card mb-3 border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-2 px-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        {{ $filiere->nom }} :
                        <span class=" bg-opacity-10 text-dark ms-2">
                            {{ $filiere->students->count() }} étudiants
                        </span>
                    </h6>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @foreach($filiere->students as $student)
                                    @php
                                        $attendance = $student->attendances->first();
                                        $status = $attendance->status ?? 1;
                                    @endphp
                                    <tr>
                                        <td class="align-middle ps-3" style="width: 60%">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-opacity-10 rounded-circle me-2" style="background: #f08b04;">
                                                    <span class="text-white">{{ substr($student->nom_prenom, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $student->nom_prenom }}</h6>
                                                    <small class="text-muted">{{ $student->matricule }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle pe-3">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <input type="radio" 
                                                       class="btn-check" 
                                                       name="attendances[{{ $student->id }}][status]" 
                                                       id="present-{{ $student->id }}" 
                                                       value="1" {{ $status == 1 ? 'checked' : '' }}>
                                                       <label class="btn btn-presence-present px-3 py-1 fw-semibold" 
                                                       for="present-{{ $student->id }}">
                                                    Présent
                                                </label>
                                            
                                                <input type="radio" 
                                                       class="btn-check" 
                                                       name="attendances[{{ $student->id }}][status]" 
                                                       id="justified-{{ $student->id }}" 
                                                       value="2" {{ $status == 2 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning px-3 py-1 fw-semibold" for="justified-{{ $student->id }}">
                                                    Justifié
                                                </label>
                                            
                                                <input type="radio" 
                                                       class="btn-check" 
                                                       name="attendances[{{ $student->id }}][status]" 
                                                       id="absent-{{ $student->id }}" 
                                                       value="0" {{ $status == 0 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger px-3 py-1 fw-semibold" for="absent-{{ $student->id }}">
                                                    Absent
                                                </label>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="d-flex justify-content-center pb-4 pt-2 bg-white sticky-bottom" style="position: sticky; bottom: 0; z-index: 10;">
            <button type="submit" class="btn btn-dark px-4 py-2 shadow">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')

@endpush