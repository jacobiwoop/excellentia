<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('date_debut', 'desc')->get();
        return view('superadmin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('superadmin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        Promotion::create([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()->route('superadmin.promotions.index')->with('success', 'Promotion ajoutée avec succès.');
    }

    public function edit(Promotion $promotion)
    {
        return view('superadmin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $promotion->update([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()->route('superadmin.promotions.index')->with('success', 'Promotion modifiée avec succès.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('superadmin.promotions.index')->with('success', 'Promotion supprimée avec succès.');
    }
}
