<?php

use App\Http\Controllers\AdminGenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Etudiant\StudentDashboardController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\AssignationController;
use App\Http\Controllers\SuperAdmin\FiliereController;
use App\Http\Controllers\SuperAdmin\FormateurController;
use App\Http\Controllers\SuperAdmin\ProfilController;
use App\Http\Controllers\SuperAdmin\ProgrammeController;
use App\Http\Controllers\SuperAdmin\PromotionController;
use App\Http\Controllers\SuperAdmin\SiteController;
use App\Http\Controllers\SuperAdmin\StudentController;
use App\Http\Controllers\SuperAdmin\SubjectController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil publique (sans auth)
Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTHENTIFICATION CLASSIQUE (User) ====================
// Pour superadmin, admin et formateurs
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:web');

// ==================== ROUTES SUPERADMIN ====================
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth:web', 'isSuperAdmin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('admins', AdminController::class);
    Route::resource('sites', SiteController::class);
    Route::resource('filieres', FiliereController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('students', StudentController::class);
    Route::put('students/{id}/statut', [StudentController::class, 'updateStatut'])
        ->name('students.updateStatut');

    Route::resource('formateurs', FormateurController::class);
    Route::delete('/assignations/destroy-multiple', [AssignationController::class, 'destroyMultiple'])->name('assignations.destroy.multiple');

    // Route resource classique
    Route::resource('assignations', AssignationController::class)->names('assignations');

    Route::resource('subjects', SubjectController::class);
    Route::prefix('programmes')->group(function () {
        Route::get('/', [ProgrammeController::class, 'index'])->name('programmes.index');
        Route::get('/create', [ProgrammeController::class, 'create'])->name('programmes.create');
        Route::post('/', [ProgrammeController::class, 'store'])->name('programmes.store');
    });
    Route::get('/programmes/{programme}/edit', [ProgrammeController::class, 'edit'])->name('programmes.edit');
    Route::put('/programmes/{programme}', [ProgrammeController::class, 'update'])->name('programmes.update');
    Route::delete('/programmes/{programme}', [ProgrammeController::class, 'destroy'])->name('programmes.destroy');

    Route::get('/attendances', [\App\Http\Controllers\Formateur\AttendanceController::class, 'index'])
        ->name('superadmin.attendances.index');
    Route::get('/presences', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'index'])->name('superadmin.attendance.index');
    Route::get('/attendance', [\App\Http\Controllers\SuperAdmin\AttendanceOverviewController::class, 'index'])
        ->name('attendance.overview');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.update');

    //admin gen
    Route::get('admin-gen', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'index'])->name('admin_gen.index');
    Route::get('admin-gen/create', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'create'])->name('admin_gen.create');
    Route::post('admin-gen/store', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'store'])->name('admin_gen.store');
    Route::get('admin_gen/{admin}/edit', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'edit'])->name('admin_gen.edit');
    Route::put('admin_gen/{admin}', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'update'])->name('admin_gen.update');
    Route::delete('admin_gen/{admin}', [\App\Http\Controllers\SuperAdmin\AdminGenController::class, 'destroy'])->name('admin_gen.destroy');

    Route::resource('fees', \App\Http\Controllers\SuperAdmin\FeeController::class);
    Route::get('student_fees', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'index'])->name('student_fees.index');

    // ⚠️ Routes de téléchargement EN PREMIER (avant les autres routes student_fees)
    Route::get('student_fees/recettes/{id}/download', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'downloadRecette'])
        ->name('student_fees.recettes.download');
    Route::get('student_fees/depenses/{id}/download', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'downloadDepense'])
        ->name('student_fees.depenses.download');

    // Ensuite les autres routes student_fees
    Route::get('student_fees/recettes', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'recettes'])->name('student_fees.recettes');
    Route::get('student_fees/depenses', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'depenses'])->name('student_fees.depenses');
    Route::get('student_fees/etudiants', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'etudiants'])->name('student_fees.etudiants');
    Route::get('student_fees/{studentFee}/history', [\App\Http\Controllers\SuperAdmin\StudentFeeController::class, 'history'])->name('student_fees.history');

    // Routes resource
    Route::resource('recettes', App\Http\Controllers\SuperAdmin\RecetteController::class);
    Route::resource('depenses', App\Http\Controllers\SuperAdmin\DepenseController::class);

    // ✅ NOUVELLE ROUTE À AJOUTER
    Route::post('students/bulk-update-statut', [StudentController::class, 'bulkUpdateStatut'])
        ->name('students.bulkUpdateStatut');

    Route::get('/sync-payments-to-recettes', function () {
        $payments = \App\Models\Payment::with('studentFee.student')->get();

        foreach ($payments as $payment) {
            // Vérifier si une recette existe déjà pour ce paiement
            $exists = \App\Models\Recette::where('student_fee_id', $payment->student_fee_id)
                ->where('montant', $payment->montant)
                ->where('date_recette', $payment->date_paiement)
                ->exists();

            if (!$exists) {
                \App\Models\Recette::create([
                    'motif' => 'Paiement scolarité - ' . ($payment->studentFee->student->nom_prenom ?? 'Étudiant'),
                    'montant' => $payment->montant,
                    'date_recette' => $payment->date_paiement,
                    'site_id' => $payment->studentFee->site_id,
                    'student_fee_id' => $payment->student_fee_id,
                    'is_automatique' => true,
                    'created_by' => $payment->created_by ?? 1,
                ]);
            }
        }

        return "Synchronisation terminée ! " . $payments->count() . " paiements traités.";
    });

    Route::get('/fix-missing-fees', function () {
        $feeInscription = \App\Models\Fee::where('type', 'inscription')->first();
        $feeSoutenance = \App\Models\Fee::where('type', 'soutenance')->first();

        $filieres = \App\Models\Filiere::all();

        $fixed = 0;

        foreach ($filieres as $filiere) {
            // Vérifier si l'inscription existe
            if ($feeInscription && !$feeInscription->filieres()->where('filiere_id', $filiere->id)->exists()) {
                $feeInscription->filieres()->attach($filiere->id, ['montant' => 20000]);
                $fixed++;
            }

            // Vérifier si la soutenance existe
            if ($feeSoutenance && !$feeSoutenance->filieres()->where('filiere_id', $filiere->id)->exists()) {
                $feeSoutenance->filieres()->attach($filiere->id, ['montant' => 50000]);
                $fixed++;
            }
        }

        return "Correction terminée ! $fixed associations créées.";
    })->middleware(['auth:web', 'isSuperAdmin']); // ← Important : protégé par auth + superadmin
});

// ==================== ROUTES ADMIN GENERAL ====================
Route::prefix('admingen')->name('admingen.')->middleware(['auth:web', 'isAdminGen'])->group(function () {
    Route::get('/dashboard', [AdminGenController::class, 'dashboard'])->name('dashboard');

    Route::resource('admins', \App\Http\Controllers\AdminGen\AdminController::class);
    Route::resource('sites', \App\Http\Controllers\AdminGen\SiteController::class);
    Route::resource('filieres', \App\Http\Controllers\AdminGen\FiliereController::class);
    Route::resource('promotions', \App\Http\Controllers\AdminGen\PromotionController::class);
    Route::resource('students', \App\Http\Controllers\AdminGen\StudentController::class);
    Route::put('students/{id}/statut', [\App\Http\Controllers\AdminGen\StudentController::class, 'updateStatut'])
        ->name('students.updateStatut');
    Route::resource('formateurs', \App\Http\Controllers\AdminGen\FormateurController::class);
    Route::delete('/assignations/destroy-multiple', [AssignationController::class, 'destroyMultiple'])->name('assignations.destroy.multiple');
    Route::resource('assignations', \App\Http\Controllers\AdminGen\AssignationController::class)->names('assignations');
    Route::resource('subjects', \App\Http\Controllers\AdminGen\SubjectController::class);

    // Programmes
    Route::prefix('programmes')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'index'])->name('programmes.index');
        Route::get('/create', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'create'])->name('programmes.create');
        Route::post('/', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'store'])->name('programmes.store');
    });

    Route::get('/programmes/{programme}/edit', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'edit'])->name('programmes.edit');
    Route::put('/programmes/{programme}', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'update'])->name('programmes.update');
    Route::delete('/programmes/{programme}', [\App\Http\Controllers\AdminGen\ProgrammeController::class, 'destroy'])->name('programmes.destroy');

    // Présences
    Route::get('/attendances', [\App\Http\Controllers\Formateur\AttendanceController::class, 'index'])->name('superadmin.attendances.index');
    Route::get('/presences', [\App\Http\Controllers\AdminGen\AttendanceController::class, 'index'])->name('admingen.attendance.index');
    Route::get('/attendance', [\App\Http\Controllers\AdminGen\AttendanceOverviewController::class, 'index'])->name('attendance.overview');
    Route::get('/attendance/create', [\App\Http\Controllers\AdminGen\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/show-students', [\App\Http\Controllers\AdminGen\AttendanceController::class, 'showStudents'])->name('attendance.showStudents');
    Route::post('/attendance/store', [\App\Http\Controllers\AdminGen\AttendanceController::class, 'store'])->name('attendance.store');

    //Notes
    Route::get('/grades', [App\Http\Controllers\AdminGen\GradeController::class, 'index'])->name('grades.index');
});

// ==================== ROUTES ADMIN ====================
Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::resource('fees', \App\Http\Controllers\Admin\FeeController::class); // superadmin


    Route::get('accounting_dashboard', [\App\Http\Controllers\Admin\AccountingDashboardController::class, 'index'])->name('accounting_dashboard.index');
    Route::get('student_fees', [\App\Http\Controllers\Admin\StudentFeeController::class, 'index'])->name('student_fees.index');
    Route::post('student_fees', [\App\Http\Controllers\Admin\StudentFeeController::class, 'store'])->name('student_fees.store');
    Route::resource('payments', controller: \App\Http\Controllers\Admin\PaymentController::class)->except(['index']);

    // Remplacer l'ancienne route store
    Route::post('/student_fees/store', [\App\Http\Controllers\Admin\StudentFeeController::class, 'store'])
        ->name('admin.student_fees.store');

    // Nouvelle route pour les réductions
    Route::get('student-fees/{id}/reduction', [\App\Http\Controllers\Admin\StudentFeeController::class, 'showReductionForm'])->name('student_fees.reduction.form');

    // Soumission réduction
    Route::post('student-fees/{id}/reduction', [\App\Http\Controllers\Admin\StudentFeeController::class, 'applyReduction'])->name('student_fees.reduction.apply');

    Route::get('/student-fees/{id}/history', [App\Http\Controllers\Admin\StudentFeeController::class, 'history'])
        ->name('student_fees.history');

    Route::get('/student_fees/{studentFee}/manage-payments', [App\Http\Controllers\Admin\PaymentController::class, 'managePayments'])
        ->name('student_fees.manage');

    // Mise à jour
    Route::put('/student_fees/{studentFee}/update-payments', [App\Http\Controllers\Admin\PaymentController::class, 'updatePayments'])
        ->name('student_fees.update_payments');

    // Pour la génération facture PDF
    Route::get('payments/{student_id}/pdf', [\App\Http\Controllers\Admin\PaymentController::class, 'generateInvoice'])->name('payments.invoice');

    // Recettes
    Route::get('/recettes', [App\Http\Controllers\Admin\RecetteController::class, 'index'])->name('recettes.index');
    Route::get('/recettes/create', [App\Http\Controllers\Admin\RecetteController::class, 'create'])->name('recettes.create');
    Route::post('/recettes', [App\Http\Controllers\Admin\RecetteController::class, 'store'])->name('recettes.store');
    Route::get('/recettes/{id}/edit', [App\Http\Controllers\Admin\RecetteController::class, 'edit'])->name('recettes.edit');
    Route::put('/recettes/{id}', [App\Http\Controllers\Admin\RecetteController::class, 'update'])->name('recettes.update');
    Route::delete('/recettes/{id}', [App\Http\Controllers\Admin\RecetteController::class, 'destroy'])->name('recettes.destroy');
    Route::get('/recettes/{id}/download', [App\Http\Controllers\Admin\RecetteController::class, 'downloadJustificatif'])->name('recettes.download');

    // Routes Admin - Dépenses
    Route::get('/depenses', [App\Http\Controllers\Admin\DepenseController::class, 'index'])->name('depenses.index');
    Route::get('/depenses/create', [App\Http\Controllers\Admin\DepenseController::class, 'create'])->name('depenses.create');
    Route::post('/depenses', [App\Http\Controllers\Admin\DepenseController::class, 'store'])->name('depenses.store');
    Route::get('/depenses/{id}/edit', [App\Http\Controllers\Admin\DepenseController::class, 'edit'])->name('depenses.edit');
    Route::put('/depenses/{id}', [App\Http\Controllers\Admin\DepenseController::class, 'update'])->name('depenses.update');
    Route::delete('/depenses/{id}', [App\Http\Controllers\Admin\DepenseController::class, 'destroy'])->name('depenses.destroy');
    Route::get('/depenses/{id}/download', [App\Http\Controllers\Admin\DepenseController::class, 'downloadJustificatif'])->name('depenses.download');
});
// ==================== ROUTES FORMATEUR ====================
Route::middleware(['auth:web', 'formateur'])->prefix('formateur')->name('formateur.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Formateur\FormateurController::class, 'dashboard'])->name('dashboard');

    // Gestion des notes
    Route::get('/grades', [\App\Http\Controllers\Formateur\GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{assignation}', [\App\Http\Controllers\Formateur\GradeController::class, 'show'])->name('grades.show');
    Route::post('/grades', [\App\Http\Controllers\Formateur\GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{assignation}/bulletin/{term}', [\App\Http\Controllers\Formateur\GradeController::class, 'generateBulletin'])->name('grades.bulletin');

    // Programmes
    Route::get('/programmes', [\App\Http\Controllers\Formateur\ProgrammeController::class, 'index'])->name('programmes.index');

    // Étudiants
    Route::get('/students', [\App\Http\Controllers\Formateur\StudentController::class, 'index'])->name('students.index');

    // Présence/Attendance
    Route::get('/attendance', [\App\Http\Controllers\Formateur\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Formateur\AttendanceController::class, 'store'])->name('attendance.store');

    // Cours
    Route::get('/videos', [\App\Http\Controllers\Formateur\CoursController::class, 'indexVideos'])->name('videos.index');
    Route::resource('cours', \App\Http\Controllers\Formateur\CoursController::class);

    // ⚠️ ROUTE DUPLIQUÉE SUPPRIMÉE
    // Cette route est identique à '/grades/{assignation}' ci-dessus
    // Route::get('/assignations/{assignation}', [...]) -> SUPPRIMÉE
});
// ==================== AUTHENTIFICATION ETUDIANT ====================
Route::prefix('etudiant')->name('etudiant.')->group(function () {
    // Connexion/déconnexion (publique)
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login');
});

// ==================== ROUTES ETUDIANT (protégées) ====================
Route::middleware(['auth:student'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/bulletin/{term?}', [StudentDashboardController::class, 'bulletin'])->name('bulletin');
    Route::get('/programmes', [\App\Http\Controllers\Etudiant\ProgrammeController::class, 'index'])->name('programmes.index');
    Route::get('/cours', [\App\Http\Controllers\Etudiant\CoursController::class, 'index'])->name('etudiant.cours.index');
    Route::get('/cours', [\App\Http\Controllers\Etudiant\CoursController::class, 'index'])->name('cours.index');
    Route::get('student_fees', [\App\Http\Controllers\Etudiant\StudentFeeController::class, 'index'])->name('student_fees.index');

    // Visionneuse unifiée (gère tous les formats)
    Route::get('/cours/{cour}/viewer', [\App\Http\Controllers\Etudiant\CoursController::class, 'viewer'])
        ->name('cours.viewer');
    Route::get('/file/proxy/{id}', [\App\Http\Controllers\Etudiant\CoursController::class, 'fileProxy'])
        ->name('file.proxy')
        ->middleware('auth:student');
    Route::get('/cours/{id}/stream', [\App\Http\Controllers\Etudiant\CoursController::class, 'fileProxy'])->name('cours.fileProxy');


    /* Route::get('/student_fees/receipt/{paymentId}', [\App\Http\Controllers\Etudiant\StudentFeeController::class, 'generateReceipt'])
    ->name('student_fees.receipt');*/
    Route::get('/student-fees/receipt/{paymentId}', [\App\Http\Controllers\Etudiant\StudentFeeController::class, 'showReceiptHTML'])
        ->name('student_fees.receipt');
    Route::get('/student-fees/full-history', [\App\Http\Controllers\Etudiant\StudentFeeController::class, 'fullHistory'])
        ->name('student_fees.full_history')
        ->middleware('auth:student');

    // Ajoute ici les autres routes étudiantes (notes, emploi du temps, etc.)
    // Exemple:
    // Route::get('/notes', [StudentDashboardController::class, 'notes'])->name('notes');

    // ACCÈS LIVES ÉTUDIANT
    Route::get('/lives', [\App\Http\Controllers\BetaLiveController::class, 'index'])->name('lives.index');
    Route::get('/lives/{live}', [\App\Http\Controllers\BetaLiveController::class, 'join'])->name('lives.join');
});
// ==================== STREAMING BETA (ISOLE) ====================
Route::prefix('beta')->name('beta.')->middleware(['auth:web'])->group(function () {

    // Espace Créateur (Formateur)
    Route::get('/', [App\Http\Controllers\BetaLiveController::class, 'index'])->name('index'); // Liste des lives
    Route::get('/create', [App\Http\Controllers\BetaLiveController::class, 'create'])->name('create');
    Route::post('/create', [App\Http\Controllers\BetaLiveController::class, 'store'])->name('store');
    Route::get('/room/{live}', [App\Http\Controllers\BetaLiveController::class, 'host'])->name('host'); // Salle Admin
    Route::post('/stop/{live}', [App\Http\Controllers\BetaLiveController::class, 'stop'])->name('stop');

    // Pour tester join en tant que User via /beta
    Route::get('/join/{live}', [App\Http\Controllers\BetaLiveController::class, 'join'])->name('join');
});
