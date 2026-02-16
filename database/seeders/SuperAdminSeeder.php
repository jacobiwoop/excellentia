<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;   // ← Bien importer le modèle User
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('adminexcellentia'),
            'role' => 'super_admin',
            'site_id' => null, // super admin n'a pas de site
        ]);
    }
}
