@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-secondary fw-bold">Marquer la présence - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h2>
      <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
        </div>

    <form action="{{ route('admingen.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="filiere_id" value="{{ $filiere_id }}">

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-4 bg-light text-uppercase fw-semibold text-muted">Nom</th>
                            <th class="py-3 bg-light text-uppercase fw-semibold text-muted">Email</th>
                            <th class="py-3 pe-4 bg-light text-uppercase fw-semibold text-muted text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            @php
                                // Récupère la présence déjà enregistrée, sinon défaut à 1 (présent)
                                $attendance = $student->attendances
                                    ->where('date', $date)
                                    ->first();
                                $status = $attendance->status ?? 1;
                            @endphp
                            <tr class="border-top border-light">
                                <td class="ps-4 fw-medium">{{ $student->nom_prenom }}</td>
                                <td class="text-muted">{{ $student->email }}</td>
                                <td class="pe-4 text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Statut présence">
                                        <input type="radio"
                                            class="btn-check"
                                            name="statuses[{{ $student->id }}]"
                                            id="present-{{ $student->id }}"
                                            value="1"
                                            {{ $status == 1 ? 'checked' : '' }}>
                                        <label class="btn btn-success px-3 py-1 fw-semibold" for="present-{{ $student->id }}">
                                            Présent
                                        </label>

                                        <input type="radio"
                                            class="btn-check"
                                            name="statuses[{{ $student->id }}]"
                                            id="justified-{{ $student->id }}"
                                            value="2"
                                            {{ $status == 2 ? 'checked' : '' }}>
                                        <label class="btn btn-warning px-3 py-1 fw-semibold" for="justified-{{ $student->id }}">
                                            Justifié
                                        </label>

                                        <input type="radio"
                                            class="btn-check"
                                            name="statuses[{{ $student->id }}]"
                                            id="absent-{{ $student->id }}"
                                            value="0"
                                            {{ $status == 0 ? 'checked' : '' }}>
                                        <label class="btn btn-danger px-3 py-1 fw-semibold" for="absent-{{ $student->id }}">
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

      
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-group').forEach(group => {
    group.querySelectorAll('input[type="radio"]').forEach(input => {
      input.addEventListener('change', function() {
        // Quand un bouton radio change dans ce groupe
        const groupLabels = group.querySelectorAll('label');
        groupLabels.forEach(label => {
          // On remet tous les labels en version outline
          if (label.classList.contains('btn-success')) {
            label.classList.replace('btn-success', 'btn-outline-success');
          }
          if (label.classList.contains('btn-warning')) {
            label.classList.replace('btn-warning', 'btn-outline-warning');
          }
          if (label.classList.contains('btn-danger')) {
            label.classList.replace('btn-danger', 'btn-outline-danger');
          }
        });

        // On applique la couleur pleine au label correspondant au bouton sélectionné
        const selectedLabel = group.querySelector('label[for="' + this.id + '"]');
        if (selectedLabel.classList.contains('btn-outline-success')) {
          selectedLabel.classList.replace('btn-outline-success', 'btn-success');
        } else if (selectedLabel.classList.contains('btn-outline-warning')) {
          selectedLabel.classList.replace('btn-outline-warning', 'btn-warning');
        } else if (selectedLabel.classList.contains('btn-outline-danger')) {
          selectedLabel.classList.replace('btn-outline-danger', 'btn-danger');
        }
      });

      // Déclenche l'événement change au chargement pour bien afficher les boutons actifs
      if (input.checked) {
        input.dispatchEvent(new Event('change'));
      }
    });
  });
});
</script>

@endsection
