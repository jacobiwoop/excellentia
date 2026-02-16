<?php

namespace App\Http\Controllers\AdminGen;

use App\Models\Filiere;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::all();
        return view('admingen.filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('admingen.filieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|unique:filieres,nom',
            'code' => 'required|unique:filieres,code|max:10',
        ]);

        Filiere::create($request->all());
        return redirect()->route('admingen.filieres.index')->with('success', 'Filière ajoutée avec succès.');
    }

    public function edit(Filiere $filiere)
    {
        return view('admingen.filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'nom' => 'required|unique:filieres,nom,' . $filiere->id,
            'code' => 'required|unique:filieres,code,' . $filiere->id . '|max:10',
        ]);

        $filiere->update($request->all());
        return redirect()->route('admingen.filieres.index')->with('success', 'Filière mise à jour.');
    }

    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
        return redirect()->route('admingen.filieres.index')->with('success', 'Filière supprimée.');
    }
}
