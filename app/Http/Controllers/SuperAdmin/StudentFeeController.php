<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Fee;
use App\Models\Promotion;
use App\Models\Filiere;
use App\Models\Site;
use App\Models\Recette;
use App\Models\Depense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentFeeController extends Controller
{
    // Page principale - Vue d'ensemble simplifiée
  public function index(Request $request)
{
    // Filtres
    $siteId = $request->input('site_id');
    $mois = $request->input('mois');
    $annee = $request->input('annee', now()->year);

    $sites = Site::all();

    // KPIs globaux
    $recettesQuery = Recette::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->whereYear('date_recette', $annee);
    
    if ($mois) {
        $recettesQuery->whereMonth('date_recette', $mois);
    }
    
    $totalRecettes = $recettesQuery->sum('montant');

    $depensesQuery = Depense::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->whereYear('date_depense', $annee);
    
    if ($mois) {
        $depensesQuery->whereMonth('date_depense', $mois);
    }
    
    $totalDepenses = $depensesQuery->sum('montant');

    $soldeCaisse = $totalRecettes - $totalDepenses;

    // ✅ À percevoir - Étudiants EN COURS
    $scolariteAPercevoirEnCours = StudentFee::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->whereHas('student', fn($q) => $q->where('statut', 'en_cours'))
        ->get()
        ->sum(function($sf) {
            return max(0, ($sf->montant_total - $sf->montant_reduction) - $sf->payments->sum('montant'));
        });

    // ✅ À percevoir - Étudiants TERMINÉS
    $scolariteAPercevoirTermines = StudentFee::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->whereHas('student', fn($q) => $q->where('statut', 'termine'))
        ->get()
        ->sum(function($sf) {
            return max(0, ($sf->montant_total - $sf->montant_reduction) - $sf->payments->sum('montant'));
        });

    // ✅ À percevoir - Étudiants ABANDONNÉS
    $scolariteAPercevoirAbandonnes = StudentFee::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->whereHas('student', fn($q) => $q->where('statut', 'abandonne'))
        ->get()
        ->sum(function($sf) {
            return max(0, ($sf->montant_total - $sf->montant_reduction) - $sf->payments->sum('montant'));
        });

    // ✅ Total à percevoir (somme de tout)
    $scolariteAPercevoir = $scolariteAPercevoirEnCours + $scolariteAPercevoirTermines + $scolariteAPercevoirAbandonnes;

    // ✅ Stats paiements - TOUS les étudiants
    $students = Student::query()
        ->when($siteId, fn($q) => $q->where('site_id', $siteId))
        ->with('studentFees.payments')
        ->get();

    $countPaye = 0;
    $countPartiel = 0;
    $countNonPaye = 0;

    foreach ($students as $student) {
        $total = 0;
        $paid = 0;
        
        foreach ($student->studentFees as $sf) {
            $total += max(0, ($sf->montant_total - $sf->montant_reduction));
            $paid += $sf->payments->sum('montant');
        }
        
        $reste = max(0, $total - $paid);
        
        if ($reste == 0 && $total > 0) $countPaye++;
        elseif ($paid > 0) $countPartiel++;
        else $countNonPaye++;
    }

    // Répartition par site
    $sitesStats = [];
    if (!$siteId) {
        foreach ($sites as $site) {
            $recetteQuery = Recette::where('site_id', $site->id)->whereYear('date_recette', $annee);
            $depenseQuery = Depense::where('site_id', $site->id)->whereYear('date_depense', $annee);
            
            if ($mois) {
                $recetteQuery->whereMonth('date_recette', $mois);
                $depenseQuery->whereMonth('date_depense', $mois);
            }
            
            $recette = $recetteQuery->sum('montant');
            $depense = $depenseQuery->sum('montant');

            $sitesStats[] = [
                'nom' => $site->nom,
                'id' => $site->id,
                'recettes' => $recette,
                'depenses' => $depense,
                'solde' => $recette - $depense
            ];
        }
    }

    // Évolution sur 6 ou 12 mois selon le filtre
    $evolutionRecettes = [];
    $evolutionDepenses = [];
    $moisLabels = [];

    if ($mois) {
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::createFromDate($annee, $mois, 1)->subMonths($i);
            $moisLabels[] = $date->translatedFormat('M');

            $evolutionRecettes[] = Recette::query()
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->whereMonth('date_recette', $date->month)
                ->whereYear('date_recette', $date->year)
                ->sum('montant');

            $evolutionDepenses[] = Depense::query()
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->whereMonth('date_depense', $date->month)
                ->whereYear('date_depense', $date->year)
                ->sum('montant');
        }
    } else {
        for ($m = 1; $m <= 12; $m++) {
            $date = \Carbon\Carbon::createFromDate($annee, $m, 1);
            $moisLabels[] = $date->translatedFormat('M');

            $evolutionRecettes[] = Recette::query()
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->whereMonth('date_recette', $m)
                ->whereYear('date_recette', $annee)
                ->sum('montant');

            $evolutionDepenses[] = Depense::query()
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->whereMonth('date_depense', $m)
                ->whereYear('date_depense', $annee)
                ->sum('montant');
        }
    }

    $periodeTexte = $mois 
        ? \Carbon\Carbon::createFromDate($annee, $mois, 1)->translatedFormat('F Y')
        : "Année $annee";

    return view('superadmin.student_fees.index', compact(
        'sites',
        'siteId',
        'mois',
        'annee',
        'totalRecettes',
        'totalDepenses',
        'soldeCaisse',
        'scolariteAPercevoir',
        'scolariteAPercevoirEnCours',      // ✅ Nouveau
        'scolariteAPercevoirTermines',     // ✅ Nouveau
        'scolariteAPercevoirAbandonnes',   // ✅ Nouveau
        'countPaye',
        'countPartiel',
        'countNonPaye',
        'sitesStats',
        'evolutionRecettes',
        'evolutionDepenses',
        'moisLabels',
        'periodeTexte'
    ));
}

    // Page détails recettes
    public function recettes(Request $request)
    {
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);
        $siteId = $request->input('site_id');

        $sites = Site::all();

        $recettes = Recette::with(['site', 'createdBy'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->orderByDesc('date_recette')
            ->paginate(20);

        $totalRecettes = Recette::query()
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->sum('montant');

        return view('superadmin.student_fees.recettes', compact('recettes', 'sites', 'siteId', 'mois', 'annee', 'totalRecettes'));
    }

    // Page détails dépenses
    public function depenses(Request $request)
    {
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);
        $siteId = $request->input('site_id');

        $sites = Site::all();

        $depenses = Depense::with(['site', 'createdBy'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->orderByDesc('date_depense')
            ->paginate(20);

        $totalDepenses = Depense::query()
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->sum('montant');

        return view('superadmin.student_fees.depenses', compact('depenses', 'sites', 'siteId', 'mois', 'annee', 'totalDepenses'));
    }

    // Page détails étudiants
    public function etudiants(Request $request)
    {
        $siteId = $request->input('site_id');
        $promotionId = $request->input('promotion_id');
        $filiereId = $request->input('filiere_id');
        $feeId = $request->input('fee_id');
        $studentStatus = $request->input('student_status', 'en_cours');

        $sites = Site::all();
        $promotions = Promotion::all();
        $filieres = Filiere::all();
        $fees = Fee::all();

        $students = Student::with(['site', 'promotion', 'filiere'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->when($promotionId, fn($q) => $q->where('promotion_id', $promotionId))
            ->when($filiereId, fn($q) => $q->where('filiere_id', $filiereId))
            ->when($studentStatus === 'en_cours', fn($q) => $q->where('statut', 'en_cours'))
            ->when($studentStatus === 'ancien', fn($q) => $q->whereIn('statut', ['termine', 'abandonne']))
            ->with(['studentFees' => function ($query) use ($feeId) {
                $query->when($feeId, fn($q) => $q->where('fee_id', $feeId))
                    ->with(['fee', 'payments']);
            }])
            ->paginate(50);

        $studentData = [];
        foreach ($students as $student) {
            $totalStudent = 0;
            $totalPaid = 0;
            $feesList = [];
            $studentFeeId = null;

            foreach ($student->studentFees as $studentFee) {
                $net = max(0, ($studentFee->montant_total ?? 0) - ($studentFee->montant_reduction ?? 0));
                $paid = $studentFee->payments->sum('montant') ?? 0;

                $totalStudent += $net;
                $totalPaid += $paid;
                $feesList[] = $studentFee->fee->nom ?? 'Frais inconnu';

                if ($studentFeeId === null) {
                    $studentFeeId = $studentFee->id;
                }
            }

            $reste = max(0, $totalStudent - $totalPaid);
            $statut = $reste == 0 && $totalStudent > 0 ? 'Payé' : ($totalPaid > 0 ? 'Partiel' : 'Non payé');

            $studentData[] = [
                'student' => $student,
                'nom_prenom' => $student->nom_prenom ?? $student->nom . ' ' . $student->prenom,
                'site' => $student->site->nom ?? '-',
                'promotion' => $student->promotion->nom ?? '-',
                'filiere' => $student->filiere->nom ?? '-',
                'fees' => implode(', ', array_unique($feesList)),
                'total' => $totalStudent,
                'paid' => $totalPaid,
                'reste' => $reste,
                'statut' => $statut,
                'student_fee_id' => $studentFeeId
            ];
        }

        return view('superadmin.student_fees.etudiants', compact(
            'sites',
            'promotions',
            'filieres',
            'fees',
            'studentData',
            'students',
            'siteId',
            'promotionId',
            'filiereId',
            'feeId',
            'studentStatus'
        ));
    }

    public function history($id)
    {
        $studentFee = StudentFee::with([
            'student.filiere',
            'fee.filieres',
            'payments'
        ])->findOrFail($id);

        return view('superadmin.student_fees.history', compact('studentFee'));
    }

public function downloadDepense($id)
{
    $depense = \App\Models\Depense::findOrFail($id);

    if (!$depense->justificatif) {
        return back()->with('error', 'Aucun justificatif disponible.');
    }

    // ✅ Cherche dans private_files (comme l'admin)
    $filePath = base_path('private_files/' . $depense->justificatif);
    
    // Si pas trouvé, essaie dans public (anciennes dépenses)
    if (!file_exists($filePath)) {
        $filePath = public_path($depense->justificatif);
    }
    
    // Dernière tentative avec uploads/justificatifs
    if (!file_exists($filePath)) {
        $filePath = public_path('uploads/justificatifs/' . basename($depense->justificatif));
    }

    if (!file_exists($filePath)) {
        return back()->with('error', 'Fichier introuvable : ' . $depense->justificatif);
    }

    return response()->download($filePath);
}

public function downloadRecette($id)
{
    $recette = \App\Models\Recette::findOrFail($id);

    if (!$recette->justificatif) {
        return back()->with('error', 'Aucun justificatif disponible.');
    }

    // ✅ Cherche dans private_files (comme l'admin)
    $filePath = base_path('private_files/' . $recette->justificatif);
    
    // Si pas trouvé, essaie dans public (anciennes recettes)
    if (!file_exists($filePath)) {
        $filePath = public_path($recette->justificatif);
    }
    
    // Dernière tentative avec uploads/justificatifs
    if (!file_exists($filePath)) {
        $filePath = public_path('uploads/justificatifs/' . basename($recette->justificatif));
    }

    if (!file_exists($filePath)) {
        return back()->with('error', 'Fichier introuvable : ' . $recette->justificatif);
    }

    return response()->download($filePath);
}
}