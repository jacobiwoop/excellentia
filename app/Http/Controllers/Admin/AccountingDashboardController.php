<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Recette;
use App\Models\Depense;

class AccountingDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $siteId = $user->site_id;

        
        
        // Filtres mois/année
        $mois = $request->input('mois', date('m'));
        $annee = $request->input('annee', date('Y'));
        
        // Type de frais (formation, inscription, soutenance)
        $feeType = $request->input('fee_type', 'formation');
        
        // ========== KPI 1 : CAISSE (DEPUIS LE DÉBUT - TOUS LES PAIEMENTS) ==========
        $totalRecettesDepuisDebut = Recette::where('site_id', $siteId)->sum('montant');
        $totalDepensesDepuisDebut = Depense::where('site_id', $siteId)->sum('montant');
        $soldeCaisse = $totalRecettesDepuisDebut - $totalDepensesDepuisDebut;
        
        // ========== KPI 2, 3, 4 : SCOLARITÉ GLOBALE (UNIQUEMENT ÉTUDIANTS EN COURS) ==========
        
        // Charger TOUS les frais des étudiants EN COURS uniquement (tous types confondus)
        $allStudentFees = StudentFee::where('site_id', $siteId)
            ->whereHas('student', fn($q) => $q->where('statut', 'en_cours'))
            ->with(['payments'])
            ->get();
        
        // Total à percevoir (tous les frais - réductions)
        $scolariteAPercevoir = 0;
        $scolaritePayee = 0;
        
        foreach ($allStudentFees as $sf) {
            $montantNet = max(0, ($sf->montant_total ?? 0) - ($sf->montant_reduction ?? 0));
            $scolariteAPercevoir += $montantNet;
            $scolaritePayee += $sf->payments->sum('montant');
        }
        
        $scolariteRestante = max(0, $scolariteAPercevoir - $scolaritePayee);
        $tauxRecouvrement = $scolariteAPercevoir > 0 ? round(($scolaritePayee / $scolariteAPercevoir) * 100, 1) : 0;
        
        // ========== SECTION COMPTABILITÉ (RECETTES/DÉPENSES DU MOIS) ==========
        
        // Effectif des étudiants actifs (EN COURS)
        $effectifEnCours = Student::where('site_id', $siteId)
            ->where('statut', 'en_cours')
            ->count();
        
        // Recettes DU MOIS (TOUS les paiements, même des anciens)
        $recettesMois = Recette::where('site_id', $siteId)
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->sum('montant');
        
        $recettesScolarite = Recette::where('site_id', $siteId)
            ->whereNotNull('student_fee_id')
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->sum('montant');
        
        $recettesAutres = Recette::where('site_id', $siteId)
            ->whereNull('student_fee_id')
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->sum('montant');
        
        // Dépenses DU MOIS
        $depensesMois = Depense::where('site_id', $siteId)
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->sum('montant');
        
        $depensesSalaires = Depense::where('site_id', $siteId)
            ->whereNotNull('formateur_id')
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->sum('montant');
        
        $depensesAutres = Depense::where('site_id', $siteId)
            ->whereNull('formateur_id')
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->sum('montant');
        
        // Solde DU MOIS (pas le même que la caisse)
        $soldeMois = $recettesMois - $depensesMois;
        
        // Nombre d'enregistrements
        $nbRecettes = Recette::where('site_id', $siteId)
            ->whereMonth('date_recette', $mois)
            ->whereYear('date_recette', $annee)
            ->count();
        
        $nbDepenses = Depense::where('site_id', $siteId)
            ->whereMonth('date_depense', $mois)
            ->whereYear('date_depense', $annee)
            ->count();
        
        // ========== STATS PAIEMENTS (POUR LE TYPE DE FRAIS SÉLECTIONNÉ - EN COURS UNIQUEMENT) ==========
        
        $studentFees = StudentFee::where('site_id', $siteId)
            ->when($feeType, function ($q) use ($feeType) {
                $q->whereHas('fee', fn($f) => $f->where('type', $feeType));
            })
            ->whereHas('student', fn($q) => $q->where('statut', 'en_cours'))
            ->with(['fee', 'student'])
            ->withSum('payments', 'montant')
            ->get();
        
        $totalFrais = $studentFees->sum(fn($sf) => (float) ($sf->montant_total ?? 0));
        $totalReductions = $studentFees->sum(fn($sf) => (float) ($sf->montant_reduction ?? 0));
        $totalNet = max(0, $totalFrais - $totalReductions);
        $totalPayeScolarite = $studentFees->sum(fn($sf) => (float) ($sf->payments_sum_montant ?? 0));
        
        // Comptage statuts (EN COURS uniquement)
        $countPaye = 0;
        $countPartiel = 0;
        $countNonPaye = 0;
        
        foreach ($studentFees as $sf) {
            $paid = (float) ($sf->payments_sum_montant ?? 0);
            $netForThis = max(0, (float) ($sf->montant_total ?? 0) - (float) ($sf->montant_reduction ?? 0));
            
            if ($netForThis <= 0) {
                $countPaye++;
            } elseif ($paid >= $netForThis) {
                $countPaye++;
            } elseif ($paid > 0) {
                $countPartiel++;
            } else {
                $countNonPaye++;
            }
        }
        
        $tauxPaiement = $totalNet > 0 ? round(($totalPayeScolarite / $totalNet) * 100, 1) : 0;
        
        return view('admin.accounting_dashboard.index', compact(
            'feeType',
            'mois',
            'annee',
            
            // KPIs principaux
            'soldeCaisse',
            'scolariteAPercevoir',
            'scolaritePayee',
            'scolariteRestante',
            'tauxRecouvrement',
            'effectifEnCours',
            
            // Recettes/Dépenses du mois
            'recettesMois',
            'recettesScolarite',
            'recettesAutres',
            'depensesMois',
            'depensesSalaires',
            'depensesAutres',
            'soldeMois',
            'nbRecettes',
            'nbDepenses',
            
            // Stats paiements (type de frais sélectionné)
            'totalNet',
            'totalPayeScolarite',
            'countPaye',
            'countPartiel',
            'countNonPaye',
            'tauxPaiement'
        ));
    }
}