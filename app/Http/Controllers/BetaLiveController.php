<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\VideoGrant;

class BetaLiveController extends Controller
{
    public function create()
    {
        $promotions = \App\Models\Promotion::all();
        return view('beta.create', compact('promotions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'promotion_id' => 'required|exists:promotions,id',
            'date_debut' => 'required|date',
        ]);

        $live = \App\Models\CourseLive::create([
            'titre' => $request->titre,
            'meeting_id' => 'excellentia-live-' . uniqid(),
            'date_debut' => $request->date_debut,
            'formateur_id' => Auth::id(),
            'promotion_id' => $request->promotion_id,
            'is_active' => true,
        ]);

        return redirect()->route('beta.host', ['live' => $live->id]);
    }

    public function host(\App\Models\CourseLive $live)
    {
        if ($live->formateur_id !== Auth::id()) {
            abort(403, "Seul le créateur peut lancer ce live.");
        }

        $token = $this->generateToken($live->meeting_id, Auth::user(), true);

        return view('beta.room', [
            'live' => $live,
            'isHost' => true,
            'user' => Auth::user(),
            'token' => $token,
            'livekitUrl' => env('LIVEKIT_URL')
        ]);
    }

    public function join(\App\Models\CourseLive $live)
    {
        $token = $this->generateToken($live->meeting_id, Auth::user(), false);

        return view('beta.room', [
            'live' => $live,
            'isHost' => false,
            'user' => Auth::user(),
            'token' => $token,
            'livekitUrl' => env('LIVEKIT_URL')
        ]);
    }

    private function generateToken($roomName, $user, $isHost)
    {
        $apiKey = env('LIVEKIT_API_KEY');
        $apiSecret = env('LIVEKIT_API_SECRET');

        $tokenOptions = (new \Agence104\LiveKit\AccessTokenOptions())
            ->setIdentity((string) $user->id)
            ->setName($user->prenom . ' ' . $user->nom);

        $token = new AccessToken($apiKey, $apiSecret, $tokenOptions);

        $grant = new VideoGrant();
        $grant->setRoomJoin(true);
        $grant->setRoomName($roomName);

        if ($isHost) {
            $grant->setCanPublish(true);
            $grant->setCanSubscribe(true);
            $grant->setCanPublishData(true);
        } else {
            $grant->setCanPublish(false); // Étudiants ne publient pas (pour l'instant)
            $grant->setCanSubscribe(true);
            $grant->setCanPublishData(true); // Pour le chat éventuel
        }

        $token->setGrant($grant);

        return $token->toJwt();
    }
}
