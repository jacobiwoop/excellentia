@extends('layouts.for')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 100px); background-color: #000;">

    {{-- Info bar --}}
    <div class="bg-dark text-white p-2 d-flex justify-content-between align-items-center">
        <div>
            <strong>🔴 LIVE (LiveKit) : {{ $live->titre }}</strong>
            <span class="badge bg-danger ms-2" id="connection-status">Déconnecté</span>
        </div>
        <div>
            @if($isHost)
            <div class="btn-group">
                <button id="toggle-mic" class="btn btn-secondary btn-sm"><i class="fas fa-microphone"></i></button>
                <button id="toggle-cam" class="btn btn-secondary btn-sm"><i class="fas fa-video"></i></button>
                <button id="share-screen" class="btn btn-secondary btn-sm"><i class="fas fa-desktop"></i></button>
            </div>
            <span class="text-warning small mx-3">Lien participant : </span>
            <a href="{{ route('beta.join', $live->id) }}" target="_blank" class="text-white text-decoration-underline">
                Ouvrir
            </a>
            @endif
        </div>
    </div>

    {{-- Video Grid --}}
    <div id="video-grid" class="d-flex flex-wrap justify-content-center align-items-center h-100 w-100" style="gap: 10px; padding: 10px;">
        <!-- Les vidéos seront injectées ici -->
        <div id="local-video-container" style="position: absolute; bottom: 20px; right: 20px; width: 200px; height: 150px; border: 2px solid white; display: none;">
            <video id="local-video" autoplay muted style="width: 100%; height: 100%; object-fit: cover;"></video>
        </div>
    </div>

</div>

{{-- LiveKit SDK --}}
<script src="https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.umd.min.js"></script>

<script>
    const LIVEKIT_URL = "{{ $livekitUrl }}";
    const TOKEN = "{{ $token }}";
    const IS_HOST = {
        {
            $isHost ? 'true' : 'false'
        }
    };

    let room;

    async function startLiveKit() {
        console.log("Démarrage LiveKit...");

        // Initialisation de la Room
        room = new LivekitClient.Room({
            adaptiveStream: true,
            dynacast: true,
        });

        // Gestion des événements
        room.on(LivekitClient.RoomEvent.Connected, () => {
            console.log('Connecté à la LiveKit Room !');
            document.getElementById('connection-status').innerText = "En ligne";
            document.getElementById('connection-status').className = "badge bg-success ms-2";

            // Si on est Hôte, on publie notre caméra/micro
            if (IS_HOST) {
                publishLocalTracks();
            }
        });

        room.on(LivekitClient.RoomEvent.Disconnected, () => {
            console.log('Déconnecté');
            document.getElementById('connection-status').innerText = "Déconnecté";
            document.getElementById('connection-status').className = "badge bg-danger ms-2";
        });

        // QUAND UNE VOIE (Track) EST AJOUTÉE (Ex: Un prof partage sa cam)
        room.on(LivekitClient.RoomEvent.TrackSubscribed, (track, publication, participant) => {
            console.log('Nouvelle vidéo reçue de', participant.identity);
            attachTrack(track, participant);
        });

        // QUAND UNE VOIE EST RETIRÉE
        room.on(LivekitClient.RoomEvent.TrackUnsubscribed, (track, publication, participant) => {
            console.log('Vidéo retirée de', participant.identity);
            detachTrack(track, participant);
        });

        // Connexion effective
        try {
            await room.connect(LIVEKIT_URL, TOKEN);
        } catch (error) {
            console.error('Erreur de connexion:', error);
            alert("Impossible de se connecter au serveur LiveKit. Vérifiez la console.");
        }
    }

    async function publishLocalTracks() {
        try {
            // Création des tracks locaux
            const localTracks = await LivekitClient.createLocalTracks({
                audio: true,
                video: true,
            });

            // Publication dans la room
            // Affichage local
            const videoTrack = localTracks.find(t => t.kind === 'video');
            if (videoTrack && document.getElementById('local-video')) {
                videoTrack.attach(document.getElementById('local-video'));
                document.getElementById('local-video-container').style.display = 'block';
            }

            await Promise.all(localTracks.map(t => room.localParticipant.publishTrack(t)));
            console.log("Caméra et Micro publiés !");

        } catch (e) {
            console.error("Erreur lors de la publication :", e);
            alert("Erreur accès caméra/micro : " + e.message);
        }
    }

    function attachTrack(track, participant) {
        if (track.kind === 'video' || track.kind === 'audio') {
            const element = track.attach();
            element.id = `participant-${participant.identity}-${track.kind}`;
            element.style.maxWidth = "100%";
            element.style.maxHeight = "100%";

            // Création d'un container
            const wrapper = document.createElement('div');
            wrapper.id = `wrapper-${participant.identity}-${track.kind}`;
            wrapper.style.flex = "1 1 45%"; // Responsive basic
            wrapper.style.maxWidth = "800px";
            wrapper.style.border = "1px solid #333";
            wrapper.appendChild(element);

            document.getElementById('video-grid').appendChild(wrapper);
        }
    }

    function detachTrack(track, participant) {
        track.detach().forEach(el => el.remove());
        const wrapper = document.getElementById(`wrapper-${participant.identity}-${track.kind}`);
        if (wrapper) wrapper.remove();
    }

    // Boutons de contrôle (Hôte)
    if (IS_HOST) {
        document.getElementById('toggle-cam').addEventListener('click', () => {
            const videoTrack = Array.from(room.localParticipant.videoTracks.values())[0]?.track;
            if (videoTrack) {
                if (videoTrack.isMuted) {
                    videoTrack.unmute();
                    console.log("Caméra activée");
                } else {
                    videoTrack.mute();
                    console.log("Caméra désactivée");
                }
            }
        });

        document.getElementById('toggle-mic').addEventListener('click', () => {
            const audioTrack = Array.from(room.localParticipant.audioTracks.values())[0]?.track;
            if (audioTrack) {
                if (audioTrack.isMuted) {
                    audioTrack.unmute();
                } else {
                    audioTrack.mute();
                }
            }
        });
    }

    // Lancement au chargement
    document.addEventListener('DOMContentLoaded', startLiveKit);
</script>
@endsection