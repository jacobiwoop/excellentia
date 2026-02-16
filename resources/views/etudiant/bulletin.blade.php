@extends('layouts.stu')

@section('content')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f5;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 100%;
        padding: 10px;
    }

    .header {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 20px 10px;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
    }

    .header h2 {
        margin: 5px 0 0;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    .info {
        display: flex;
        flex-direction: column;
        padding: 20px 15px;
        font-size: 14px;
        color: black;
    }

    .info div {
        width: 100%;
        margin: 8px 0;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .separator {
        border-top: 2px solid #000;
        margin: 20px 15px;
    }

    .grades {
        width: 100%;
        padding: 0 10px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .grades table {
        width: 100%;
        border-collapse: collapse;
        min-width: 500px;
        font-size: 13px;
    }

    .grades th, .grades td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }

    .grades th {
        background-color: #333;
        color: white;
        font-weight: 500;
    }

    .grades td {
        background-color: #e8eaea;
        color: black;
    }

    .footer {
        display: flex;
        flex-direction: column;
        padding: 20px 15px;
        font-weight: bold;
        color: black;
        font-size: 14px;
    }

    .footer div {
        margin: 5px 0;
    }

    .notation {
        padding: 0 15px 30px;
        color: black;
        font-size: 14px;
    }

    .notation h4 {
        border-top: 2px solid #000;
        padding-top: 8px;
        margin-top: 20px;
        font-size: 16px;
    }

    .notation ul {
        list-style-type: none;
        padding-left: 0;
        margin-top: 10px;
    }

    .notation li {
        padding: 3px 0;
    }

    .no-print {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .no-print .btn-export {
        margin-left: auto; /* Pousse le bouton à droite */
    }

    .no-print .btn-group {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .no-print .btn {
        padding: 6px 12px;
        font-size: 13px;
        white-space: nowrap;
    }

    @media (min-width: 768px) {
        .header {
            padding: 30px 10px;
        }

        .header h1 {
            font-size: 32px;
        }

        .header h2 {
            font-size: 16px;
        }

        .info {
            flex-direction: row;
            flex-wrap: wrap;
            padding: 30px 50px;
            font-size: 16px;
        }

        .info div {
            width: 45%;
        }

        .separator {
            margin: 30px 50px;
            border-top-width: 3px;
        }

        .grades {
            padding: 0 50px;
        }

        .grades table {
            font-size: 14px;
        }

        .grades th, .grades td {
            padding: 10px;
        }

        .footer {
            flex-direction: row;
            justify-content: space-between;
            padding: 30px 50px;
            font-size: 16px;
        }

        .notation {
            padding: 0 50px 50px;
        }

        .notation h4 {
            font-size: 18px;
        }

        .no-print {
            padding: 10px 50px;
        }

        .no-print .btn {
            padding: 8px 16px;
            font-size: 14px;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container">
    <div class="no-print">
        <div class="btn-group">
            @foreach([1, 2, 3] as $term)
                <a href="{{ route('etudiant.bulletin', ['term' => $term]) }}"
                   class="btn btn-outline-dark btn-sm {{ $currentTerm == $term ? 'active' : '' }}">
                    Trimestre {{ $term }}
                </a>
            @endforeach
        </div>
        <button onclick="window.print()" class="btn btn-dark btn-sm btn-export">
            <i class="fas fa-print"></i> Exporter
        </button>
    </div>

    <div class="header">
        <h1 class="text-white">BULLETIN SCOLAIRE</h1>
        <h2 class="text-white">ÉCOLE ET COLLÈGE DE VILLE</h2>
    </div>

    <div class="info">
        <div><strong>Nom de l'élève :</strong> {{ $student->nom_prenom }}</div>
        <div><strong>Filière :</strong> {{ $student->filiere->nom }}</div>
        <div><strong>Année scolaire :</strong> 2024-2025</div>
        <div><strong>Trimestre :</strong> {{ $currentTerm }}</div>
    </div>

    <div class="separator"></div>

<div class="grades">
    <table>
        <thead>
            <tr>
                <th>MATIÈRE</th>
                <th>MOY. INTERRO</th>
                <th>DEVOIR</th>  {{-- ✅ Retiré "MOY." --}}
                <th>MOY. FINALE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
            <tr>
                <td>{{ $grade->assignation->subject->nom }}</td>
                <td>{{ $grade->moy_interro ?? '-' }}</td>
                <td>{{ $grade->devoir ?? '-' }}</td>  {{-- ✅ Afficher la note du devoir --}}
                <td>{{ $grade->moy_finale ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    @php
        $moyenne = $grades->avg('moy_finale');
        if ($moyenne >= 16) $mention = 'Très bien';
        elseif ($moyenne >= 14) $mention = 'Bien';
        elseif ($moyenne >= 12) $mention = 'Assez bien';
        elseif ($moyenne >= 10) $mention = 'Passable';
        else $mention = 'Échec';
    @endphp

    <div class="footer">
        <div><strong>Moyenne Générale :</strong> {{ number_format($moyenne, 2) }}</div>
        <div><strong>Mention :</strong> {{ $mention }}</div>
    </div>

    <div class="notation">
        <h4>SYSTÈME DE NOTATION :</h4>
        <ul>
            <li>&ge; 16 Très bien</li>
            <li>&ge; 14 Bien</li>
            <li>&ge; 12 Assez bien</li>
            <li>&ge; 10 Passable</li>
            <li>&lt; 10 Échec</li>
        </ul>
    </div>
</div>
@endsection