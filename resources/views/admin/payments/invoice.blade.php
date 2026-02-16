<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $student->nom_prenom }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1, h2 { margin: 0; }
        .header { margin-bottom: 20px; }
        .totals { margin-top: 30px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Facture Étudiant</h1>
        <p><strong>Nom :</strong> {{ $student->nom_prenom }}</p>
        <p><strong>Promotion :</strong> {{ $promotion }}</p>
        <p><strong>Date :</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Type de frais</th>
                <th>Montant total</th>
                <th>Réduction</th>
                <th>Montant payé</th>
                <th>Montant restant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentFees as $fee)
            <tr>
                <td>{{ ucfirst($fee->fee->nom) }}</td>
                <td>{{ number_format($fee->montant_total, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($fee->montant_reduction ?? 0, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($fee->montant_paye ?? 0, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($fee->reste ?? 0, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="totals">
        Total payé : {{ number_format($totalPaid, 0, ',', ' ') }} FCFA <br>
        Total restant : {{ number_format($totalRemaining, 0, ',', ' ') }} FCFA
    </p>
</body>
</html>
