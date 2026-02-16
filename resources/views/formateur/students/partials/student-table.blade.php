<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="bg-white">
            <tr>
                <th class="py-3 px-4 fw-semibold text-secondary">Matricule</th>
                <th class="py-3 px-4 fw-semibold text-secondary">Nom complet</th>
                <th class="py-3 px-4 fw-semibold text-secondary">Site</th>
                <th class="py-3 px-4 fw-semibold text-secondary">Promotion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr class="student-row">
                    <td class="py-3 px-4">
                        <span class="badge bg-light text-dark">
                            {{ $student->matricule }}
                        </span>
                    </td>
                    <td class="py-3 px-4 fw-semibold">
                        <i class="fas fa-user-circle text-primary me-2"></i>
                        {{ $student->nom_prenom }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="badge bg-danger bg-opacity-10 text-white">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $student->site->nom ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        {{ $student->promotion->nom ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Aucun étudiant actif pour cette filière
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.student-row {
    transition: all 0.2s ease;
}


.table > :not(caption) > * > * {
    border-bottom-width: 1px;
}
</style>