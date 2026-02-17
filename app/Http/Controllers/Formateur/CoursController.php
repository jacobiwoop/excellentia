<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Assignation;
use App\Models\Filiere;
use App\Models\Promotion;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{

    public function index()
    {
        $cours = Cours::where('formateur_id', Auth::id())
            ->whereNotNull('fichier_path')
            ->with(['assignation.site', 'assignation.filiere', 'promotion'])
            ->get();

        return view('formateur.cours.index', compact('cours'));
    }

    public function indexVideos()
    {
        $cours = Cours::where('formateur_id', Auth::id())
            ->whereNotNull('video_path')
            ->with(['assignation.site', 'assignation.filiere', 'promotion'])
            ->get();

        return view('formateur.cours.videos', compact('cours'));
    }


    public function create(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'file'); // 'file' par défaut

        if ($user->site_id) {
            $sites = \App\Models\Site::where('id', $user->site_id)->get();
        } else {
            $sites = \App\Models\Site::all();
        }

        $filieres = \App\Models\Filiere::all();
        $promotions = Promotion::all();

        return view('formateur.cours.create', compact('sites', 'filieres', 'promotions', 'type'));
    }


    public function store(Request $request)
    {
        // On récupère le type depuis l'URL (query param) car en cas de fichier trop lourd, $_POST est vide
        $type = $request->query('type', 'file');

        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sites' => 'required|array',
            'sites.*' => 'exists:sites,id',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
        ];

        // Validation conditionnelle
        if ($type === 'video') {
            $rules['video'] = 'required|file|mimes:mp4,mov,avi,wmv|max:102400';
            $rules['fichier'] = 'nullable';
        } else {
            $rules['fichier'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,mov,avi|max:40960';
            $rules['video'] = 'nullable';
        }

        $request->validate($rules);

        // Upload fichier
        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichier = $request->file('fichier');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $chemin = 'uploads/cours/';
            $fichier->move(public_path($chemin), $nomFichier);
            $fichierPath = $chemin . $nomFichier;
        }


        // Upload Vidéo (Nouveau)
        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $nomVideo = time() . '_video_' . $video->getClientOriginalName();
            $cheminVideo = 'uploads/cours/videos/';
            $video->move(public_path($cheminVideo), $nomVideo);
            $videoPath = $cheminVideo . $nomVideo;
        }

        // Boucle sur chaque combinaison site + filière
        foreach ($request->sites as $siteId) {
            foreach ($request->filieres as $filiereId) {
                $assignation = Assignation::where('formateur_id', Auth::id())
                    ->where('site_id', $siteId)
                    ->where('filiere_id', $filiereId)
                    ->first();

                if ($assignation) {
                    Cours::create([
                        'titre' => $request->titre,
                        'description' => $request->description,
                        'fichier_path' => $fichierPath,
                        'video_path' => $videoPath,
                        'assignation_id' => $assignation->id,
                        'promotion_id' => $request->promotion_id,
                        'formateur_id' => Auth::id(),
                    ]);
                }
            }
        }


        return redirect()->route('formateur.cours.index')
            ->with('success', 'Cours ajouté avec succès.');
    }

    public function edit(Cours $cour)
    {
        $sites = Site::all();
        $filieres = Filiere::all();
        $promotions = Promotion::all();

        // Déterminer le type (video ou file)
        $type = $cour->video_path ? 'video' : 'file';

        // récupérer tous les cours liés au même fichier ou vidéo
        $query = Cours::where('formateur_id', Auth::id());

        if ($type === 'video') {
            $query->where('video_path', $cour->video_path);
        } else {
            $query->where('fichier_path', $cour->fichier_path);
        }

        $coursGroup = $query->get();

        // extraire les sites et filières sélectionnés
        $selectedSites = $coursGroup->map(fn($c) => $c->assignation->site_id)->unique()->toArray();
        $selectedFilieres = $coursGroup->map(fn($c) => $c->assignation->filiere_id)->unique()->toArray();

        return view('formateur.cours.edit', compact('cour', 'sites', 'filieres', 'promotions', 'selectedSites', 'selectedFilieres', 'type'));
    }

    public function update(Request $request, Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        // On privilégie le type passé en URL (transmis par le formulaire) pour la validation
        // Sinon on fallback sur le type actuel du cours
        $type = $request->query('type', $cour->video_path ? 'video' : 'file');

        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sites' => 'required|array',
            'sites.*' => 'exists:sites,id',
            'filieres' => 'required|array',
            'filieres.*' => 'exists:filieres,id',
            'promotion_id' => 'required|exists:promotions,id',
        ];

        if ($type === 'video') {
            $rules['video'] = 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400';
        } else {
            $rules['fichier'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,mov,avi|max:40960';
        }

        $request->validate($rules);

        // --- GESTION FICHIER / VIDEO ---
        $fichierPath = $cour->fichier_path;
        $videoPath = $cour->video_path;

        if ($type === 'file' && $request->hasFile('fichier')) {
            if ($fichierPath && file_exists(public_path($fichierPath))) {
                unlink(public_path($fichierPath));
            }
            $fichier = $request->file('fichier');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $chemin = 'uploads/cours/';
            $fichier->move(public_path($chemin), $nomFichier);
            $fichierPath = $chemin . $nomFichier;
        }

        if ($type === 'video' && $request->hasFile('video')) {
            if ($videoPath && file_exists(public_path($videoPath))) {
                unlink(public_path($videoPath));
            }
            $video = $request->file('video');
            $nomVideo = time() . '_video_' . $video->getClientOriginalName();
            $cheminVideo = 'uploads/cours/videos/';
            $video->move(public_path($cheminVideo), $nomVideo);
            $videoPath = $cheminVideo . $nomVideo;
        }

        // Supprimer anciennes entrées
        $query = Cours::where('formateur_id', Auth::id());
        if ($type === 'video') {
            $query->where('video_path', $cour->video_path);
        } else {
            $query->where('fichier_path', $cour->fichier_path);
        }
        $query->delete();

        // Recréer
        foreach ($request->sites as $siteId) {
            foreach ($request->filieres as $filiereId) {
                $assignation = Assignation::where('formateur_id', Auth::id())
                    ->where('site_id', $siteId)
                    ->where('filiere_id', $filiereId)
                    ->first();

                if ($assignation) {
                    Cours::create([
                        'titre' => $request->titre,
                        'description' => $request->description,
                        'fichier_path' => $fichierPath,
                        'video_path' => $videoPath,
                        'assignation_id' => $assignation->id,
                        'promotion_id' => $request->promotion_id,
                        'formateur_id' => Auth::id(),
                    ]);
                }
            }
        }

        $redirectRoute = ($type === 'video') ? 'formateur.videos.index' : 'formateur.cours.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        $type = $cour->video_path ? 'video' : 'file';
        $query = Cours::where('formateur_id', Auth::id());

        if ($type === 'video') {
            $query->where('video_path', $cour->video_path);
        } else {
            $query->where('fichier_path', $cour->fichier_path);
        }
        $coursASupprimer = $query->get();

        // Suppression fichiers sur disque
        if ($type === 'file' && $cour->fichier_path && file_exists(public_path($cour->fichier_path))) {
            unlink(public_path($cour->fichier_path));
        }
        if ($type === 'video' && $cour->video_path && file_exists(public_path($cour->video_path))) {
            unlink(public_path($cour->video_path));
        }

        $coursASupprimer->each->delete();

        $redirectRoute = ($type === 'video') ? 'formateur.videos.index' : 'formateur.cours.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Cours supprimé avec succès.');
    }

    public function show(Cours $cour)
    {
        if ($cour->formateur_id !== Auth::id()) {
            abort(403);
        }

        return view('formateur.cours.show', compact('cour'));
    }
}
