<?php
namespace App\Http\Controllers\Admingen;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
{
    $subjects = Subject::with('filieres')->get(); // Charger les filières liées à chaque matière
    return view('admingen.subjects.index', compact('subjects'));
}


    public function create()
    {
        $filieres = Filiere::all();
        return view('admingen.subjects.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
        ]);

        // Création de la matière (sans filières)
        $subject = Subject::create([
            'nom' => $request->nom,
        ]);

        // Attacher les filières sélectionnées
        $subject->filieres()->attach($request->filieres);

        return redirect()->route('admingen.subjects.index')->with('success', 'Matière ajoutée avec succès');
    }

    public function edit(Subject $subject)
    {
        $filieres = Filiere::all();
        // Charger les filières liées pour précocher dans la vue
        $subject->load('filieres');
        return view('admingen.subjects.edit', compact('subject', 'filieres'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
        ]);

        // Mise à jour du nom
        $subject->update([
            'nom' => $request->nom,
        ]);

        // Mise à jour des filières liées
        $subject->filieres()->sync($request->filieres);

        return redirect()->route('admingen.subjects.index')->with('success', 'Matière mise à jour');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admingen.subjects.index')->with('success', 'Matière supprimée');
    }
}
