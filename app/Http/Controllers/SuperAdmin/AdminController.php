<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site; // n'oublie pas ce "use"
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Liste tous les admins
        $admins = User::where('role', 'admin_site')->get();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        $sites = Site::all();
        return view('superadmin.admins.create', compact('sites'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'site_id' => 'required|exists:sites,id', // ici

        ]);

        // Création de l'admin
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_site',
            'site_id' => $request->site_id, // Ajout ici
        ]);
        
        return redirect()->route('superadmin.admins.index')->with('success', 'Admin créé avec succès !');
    }


    public function edit($id)
    {
        $admin = User::where('role', 'admin_site')->findOrFail($id);
        $sites = Site::all();
        return view('superadmin.admins.edit', compact('admin', 'sites'));
    }
    
    public function update(Request $request, $id)
{
    $admin = User::where('role', 'admin_site')->findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $admin->id,
        'password' => 'nullable|string|min:6|confirmed',
        'site_id' => 'required|exists:sites,id', // Ajout ici
    ]);
    
    $admin->name = $request->name;
    $admin->email = $request->email;
    $admin->site_id = $request->site_id; // Ajout ici
    

    if ($request->password) {
        $admin->password = Hash::make($request->password);
    }
    

    $admin->save();

    return redirect()->route('superadmin.admins.index')->with('success', 'Admin mis à jour avec succès.');
}

    public function destroy($id)
{
    $admin = User::where('role', 'admin_site')->findOrFail($id);
    $admin->delete();

    return redirect()->route('superadmin.admins.index')->with('success', 'Admin supprimé avec succès.');
}


}
