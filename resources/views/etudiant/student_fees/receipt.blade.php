<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        @page { margin: 0; size: A4 portrait; }
        @media print {
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 1.5cm;
            color: #333;
            font-size: 14px;
            background-color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }
        .institution {
            text-align: right;
            line-height: 1.3;
        }
        .institution-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .info-block {
            flex: 1 1 200px;
            min-width: 200px;
        }
        .info-block-right { 
            text-align: right; 
            padding-left: 40px;
            padding-right: 0;
            border-radius: 4px;
            padding: 12px;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .payment-table th {
            background-color: #333;
            color: white;
            padding: 10px 12px;
            text-align: left;
        }
        .payment-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #777;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .highlight { color: #e74c3c; font-weight: bold; }
        .info-label {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            color: #2c3e50;
            display: block;
        }
        .text-success { color: #28a745; }
        .date-payment-info {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div id="receipt-content">
        <div class="header">
            <div class="institution">
                <div class="institution-name">INSTITUT EXCELLENTIA</div>
                <div>Reçu de Paiement</div>
                <div>N°: {{ $receiptNumber }}</div>
            </div>
        </div>

        <div class="receipt-info">
            <div class="info-block">
                <div class="info-label">ÉTUDIANT(E)</div>
                <div>{{ $student->nom_prenom }}</div>
                <div>Matricule: {{ $student->matricule ?? 'N/A' }}</div>
                <div>Filière: {{ $student->filiere->nom ?? '' }}</div>
            </div>
            <div class="info-block info-block-right">
                <div class="info-label">DATE</div>
                <div class="date-payment-info">{{ $date }}</div>
                <div class="info-label">MODE DE PAIEMENT</div>
                <div class="date-payment-info">{{ ucfirst($payment->mode_paiement ?? 'Espèces') }}</div>
            </div>
        </div>

        <table class="payment-table">
            <thead>
                <tr>
                    <th>Type de Frais</th>
                    <th>Date Paiement</th>
                    <th>Total</th>
                    <th>Payé</th>
                    <th>Reste</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $montantTotal = max(0, $payment->studentFee->montant_total - $payment->studentFee->montant_reduction);
                    $resteCumul = $montantTotal;
                @endphp

                <tr>
                    <td>{{ $payment->studentFee->fee->nom }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->date_paiement)->format('d/m/Y') }}</td>
                    <td>{{ number_format($montantTotal,0,',',' ') }} FCFA</td>
                    <td class="text-success">{{ number_format($payment->montant,0,',',' ') }} FCFA</td>
                    @php
                        $resteCumul -= $payment->montant;
                        if($resteCumul < 0) $resteCumul = 0;
                    @endphp
                    <td>
                        <span class="{{ $resteCumul == 0 ? '' : 'highlight' }}">
                            {{ number_format($resteCumul,0,',',' ') }} FCFA
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        @if($payment->studentFee->montant_reduction > 0)
        <div style="margin-top: 15px;">
            <strong>Réduction appliquée:</strong> 
            -{{ number_format($payment->studentFee->montant_reduction, 0, ',', ' ') }} FCFA
            @if($payment->studentFee->reduction_motif)
                <br><small>(Motif: {{ $payment->studentFee->reduction_motif }})</small>
            @endif
        </div>
        @endif

        <div class="footer">
            Document officiel - Institut Excellentia © {{ date('Y') }}<br>
            Ce reçu est valable comme justificatif de paiement
        </div>
    </div>
</body>
</html>