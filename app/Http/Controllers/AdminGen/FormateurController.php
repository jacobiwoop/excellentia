<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FormateurController extends Controller
{
    public function index()
    {
        $formateurs = User::where('role', 'formateur')->get();
        return view('admingen.formateurs.index', compact('formateurs'));
    }

    public function create()
    {
       
        return view('admingen.formateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
           
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'formateur',
            
        ]);

        return redirect()->route('admingen.formateurs.index')->with('success', 'Formateur créé avec succès.');
    }

    public function edit($id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);
        $sites = Site::all();
        return view('admingen.formateurs.edit', compact('formateur'));
    }

    public function update(Request $request, $id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $formateur->id,
            'password' => 'nullable|string|min:6|confirmed',
           
        ]);

        $formateur->name = $request->name;
        $formateur->email = $request->email;
       

        if ($request->filled('password')) {
            $formateur->password = Hash::make($request->password);
        }

        $formateur->save();

        return redirect()->route('admingen.formateurs.index')->with('success', 'Formateur mis à jour.');
    }

    public function destroy($id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);
        $formateur->delete();

        return redirect()->route('admingen.formateurs.index')->with('success', 'Formateur supprimé.');
    }
}
