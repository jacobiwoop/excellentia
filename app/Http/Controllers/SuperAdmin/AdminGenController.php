<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminGenController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin_gen')->paginate(10);
        return view('superadmin.admingen.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admingen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users')],
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin_gen',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.admin_gen.index')->with('success', 'Admin général créé avec succès.');
    }

    // Voici les méthodes pour edit et update

    public function edit($id)
    {
        $admin = User::where('role', 'admin_gen')->findOrFail($id);
        return view('superadmin.admingen.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin_gen')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('superadmin.admin_gen.index')->with('success', 'Admin général mis à jour avec succès.');
    }

    // Optionnel : méthode destroy
    public function destroy($id)
    {
        $admin = User::where('role', 'admin_gen')->findOrFail($id);
        $admin->delete();

        return redirect()->route('superadmin.admin_gen.index')->with('success', 'Admin général supprimé avec succès.');
    }
}
