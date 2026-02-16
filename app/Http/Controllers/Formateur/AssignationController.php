<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Assignation;
use Illuminate\Support\Facades\Auth;

class AssignationController extends Controller
{
    /**
     * Liste des matières assignées au formateur connecté
     */
    public function index()
    {
        $assignations = Assignation::with([
                'site',
                'filiere.students',
                'subject'
            ])
            ->where('formateur_id', Auth::id())
            ->get();

        // ⚠️ TOUJOURS passer $assignations à la vue
        return view('formateur.assignations', [
            'assignations' => $assignations
        ]);
    }
}
