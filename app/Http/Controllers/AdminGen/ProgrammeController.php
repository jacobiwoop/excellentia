<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Programme;
use App\Models\Subject;
use App\Models\User;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programme::with(['subject', 'formateur', 'filieres', 'site'])
            ->orderBy('date_seance', 'desc')
            ->paginate(10);

        return view('admingen.programmes.index', compact('programmes'));
    }

    public function create()
    {
        return view('admingen.programmes.create', [
            'subjects' => Subject::all(),
            'formateurs' => User::where('role', 'formateur')->get(),
            'filieres' => Filiere::all(),
            'sites' => Site::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_seance' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'subject_id' => 'nullable|exists:subjects,id',
            'titre_custom' => 'nullable|string|max:100',
            'formateur_id' => 'required|exists:users,id',
            'filiere_ids' => 'required|array|min:1',
            'filiere_ids.*' => 'exists:filieres,id',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:250',
            'recurrence' => 'required|in:ponctuel,hebdomadaire,mensuel',
            'date_fin_recurrence' => 'nullable|required_if:recurrence,hebdomadaire,mensuel|date|after:date_seance'
        ]);

        if (empty($request->subject_id) && empty($request->titre_custom)) {
            return back()->withErrors('Renseignez soit une matière existante, soit un intitulé personnalisé');
        }

        unset($validated['filiere_ids']); // Pas dans la table programmes

        if ($request->recurrence === 'ponctuel') {
            $programme = Programme::create($validated);
            $programme->filieres()->sync($request->filiere_ids);
        } else {
            $dates = $this->generateDates(
                $request->date_seance,
                $request->date_fin_recurrence,
                $request->recurrence
            );

            foreach ($dates as $date) {
                $progData = array_merge($validated, ['date_seance' => $date]);
                $programme = Programme::create($progData);
                $programme->filieres()->sync($request->filiere_ids);
            }
        }

        return redirect()->route('admingen.programmes.index')->with('success', 'Programme enregistré pour les filières sélectionnées !');
    }

    private function generateDates($start, $end, $type)
    {
        $dates = [];
        $current = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        while ($current <= $endDate) {
            $dates[] = $current->format('Y-m-d');
            $current = ($type === 'hebdomadaire') ? $current->addWeek() : $current->addMonth();
        }
        
        return $dates;
    }

    public function edit(Programme $programme)
    {
        return view('admingen.programmes.edit', [
            'programme' => $programme->load('filieres'),
            'subjects' => Subject::all(),
            'formateurs' => User::where('role', 'formateur')->get(),
            'filieres' => Filiere::all(),
            'sites' => Site::all(),
        ]);
    }

    public function update(Request $request, Programme $programme)
    {
        $validated = $request->validate([
            'date_seance' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'subject_id' => 'nullable|exists:subjects,id',
            'site_id' => 'required|exists:sites,id',
            'description' => 'nullable|string|max:250',
            'titre_custom' => 'nullable|string|max:100',
            'formateur_id' => 'required|exists:users,id',
            'filiere_ids' => 'required|array|min:1',
            'filiere_ids.*' => 'exists:filieres,id',
        ]);

        if (empty($request->subject_id) && empty($request->titre_custom)) {
            return back()->withErrors('Renseignez soit une matière existante, soit un intitulé personnalisé');
        }

        unset($validated['filiere_ids']);

        $programme->update($validated);

        $programme->filieres()->sync($request->filiere_ids);

        return redirect()->route('admingen.programmes.index')
            ->with('success', 'Programme mis à jour avec succès');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        return redirect()->route('admingen.programmes.index')
            ->with('success', 'Programme supprimé avec succès');
    }
}
