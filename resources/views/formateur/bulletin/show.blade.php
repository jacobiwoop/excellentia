@extends('layouts.for')

@section('content')
<div class="container mx-auto p-4">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold">BULLETIN TRIMESTRIEL</h1>
        <h2 class="text-xl">{{ $assignation->subject->nom }} - {{ $assignation->filiere->nom }}</h2>
        <p class="text-gray-600">Trimestre {{ $term }}</p>
    </div>

    @foreach($students as $data)
    <div class="bulletin bg-white rounded-lg shadow-md p-6 mb-8">
        <!-- En-tête élève -->
        <div class="grid grid-cols-2 gap-4 mb-6 border-b pb-4">
            <div>
                <p><span class="font-bold">Nom:</span> {{ $data['student']->nom_prenom }}</p>
                <p><span class="font-bold">Classe:</span> {{ $assignation->filiere->nom }}</p>
            </div>
            <div>
                <p><span class="font-bold">Matière:</span> {{ $assignation->subject->nom }}</p>
                <p><span class="font-bold">Moyenne:</span> 
                    <span class="text-xl font-bold {{ $data['moyenne'] >= 10 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $data['moyenne'] ?? 'N/A' }}
                    </span>
                </p>
            </div>
        </div>

        
        <!-- Détail des notes -->
        @if($data['grade'])
        <table class="w-full mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Évaluation</th>
                    <th class="p-2 text-center">Note</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-2">Interrogation 1</td>
                    <td class="p-2 text-center">{{ $data['grade']->interro1 ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="p-2">Interrogation 2</td>
                    <td class="p-2 text-center">{{ $data['grade']->interro2 ?? '-' }}</td>
                </tr>
                <!-- Ajoutez toutes les colonnes nécessaires -->
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-center py-4">Aucune note enregistrée</p>
        @endif
    </div>
    @endforeach
</div>
@endsection