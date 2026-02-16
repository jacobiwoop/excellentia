<?php

namespace App\Http\Controllers\AdminGen;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::all();
        
        return view('admingen.sites.index', compact('sites'));
    }

    public function create()
    {
        return view('admingen.sites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:sites,code',
        ]);

        Site::create($request->only('nom', 'code'));

        return redirect()->route('admingen.sites.index')->with('success', 'Site créé avec succès.');
    }

    public function edit(Site $site)
    {
        return view('admingen.sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:sites,code,' . $site->id,
        ]);

        $site->update($request->only('nom', 'code'));

        return redirect()->route('admingen.sites.index')->with('success', 'Site mis à jour.');
    }

    public function show($id)
    {
        $site = Site::findOrFail($id);
    
        // Récupération des étudiants liés à ce site
        $students = $site->students;
    
        return view('admingen.sites.show', compact('site', 'students'));
    }
    


    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('admingen.sites.index')->with('success', 'Site supprimé.');
    }
}
