<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CoursController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cours = Cours::where('promotion_id', $user->promotion_id)
            ->whereHas('assignation', function ($q) use ($user) {
                $q->where('filiere_id', $user->filiere_id);
            })
            ->with(['formateur', 'assignation.filiere'])
            ->orderBy('created_at', 'desc')
            ->distinct('fichier_path') // ✅ si tu veux éviter doublons fichier
            ->paginate(12);

        return view('etudiant.cours.index', compact('cours'));
    }

    /**
     * Stream sécurisé du fichier (PDF, DOCX, etc.)
     */
    public function streamFile(Cours $cour)
    {
        $user = Auth::user();
        
        if ($cour->promotion_id !== $user->promotion_id) {
            abort(403, "Accès non autorisé");
        }

        $filePath = public_path($cour->fichier_path);

        if (!file_exists($filePath)) {
            abort(404, "Fichier introuvable");
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'mp4'  => 'video/mp4',
            'mov'  => 'video/quicktime',
            'avi'  => 'video/x-msvideo',
        ];

        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        return Response::make(file_get_contents($filePath), 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="'.basename($filePath).'"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0'
        ]);
    }

    public function viewer(Cours $cour)
    {
        $user = Auth::user();
        
        if ($cour->promotion_id !== $user->promotion_id) {
            abort(403, "Accès non autorisé");
        }

        $extension = strtolower(pathinfo($cour->fichier_path, PATHINFO_EXTENSION));

        return view('etudiant.cours.secure-viewer', [
            'cour' => $cour,
            'extension' => $extension
        ]);
    }

    public function fileProxy($id)
    {
        $cour = Cours::findOrFail($id);
        $user = Auth::user();

        if ($cour->promotion_id !== $user->promotion_id) {
            abort(403, "Accès interdit");
        }

        $filePath = public_path($cour->fichier_path);
        if (!file_exists($filePath)) {
            abort(404, "Fichier introuvable");
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache'
        ]);
    }
}
