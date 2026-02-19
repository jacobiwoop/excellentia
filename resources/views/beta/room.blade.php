@extends(Auth::guard('web')->check() ? 'layouts.for' : 'layouts.stu')

@section('content')
<div class="container-fluid p-0 position-relative" style="height: calc(100vh - 100px);">

    <!-- Bouton Quitter (Pour tout le monde) -->
    <div class="position-absolute" style="top: 20px; left: 20px; z-index: 1000;">
        <a href="{{ isset($isHost) && $isHost ? route('beta.index') : route('etudiant.lives.index') }}" class="btn btn-secondary shadow-lg">
            <i class="fas fa-arrow-left"></i> Quitter la salle
        </a>
    </div>

    @if(isset($isHost) && $isHost)
    <!-- Formulaire caché pour stopper le live -->
    <div class="d-none">
        <form id="stop-live-form" action="{{ route('beta.stop', $live->id) }}" method="POST">
            @csrf
        </form>
    </div>

    <div class="position-absolute" style="top: 20px; right: 20px; z-index: 1000;">
        <form action="{{ route('beta.stop', $live->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment terminer ce live pour tout le monde ?');">
            @csrf
            <button type="submit" class="btn btn-danger shadow-lg">
                <i class="fas fa-stop-circle"></i> Terminer le Live
            </button>
        </form>
    </div>
    @endif

    <div id="jitsi-meet-conf-container" style="height: 100%; width: 100%;"></div>
</div>

<script src='https://{{ env("JITSI_DOMAIN", "meet.jit.si") }}/external_api.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = '{{ env("JITSI_DOMAIN", "meet.jit.si") }}';
        const options = {
            roomName: '{{ $roomName }}',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-meet-conf-container'),
            userInfo: {
                email: '{{ $user->email ?? "student@excellentia.com" }}',
                displayName: '{{ $displayName }}'
            },
            configOverwrite: {
                startWithAudioMuted: true,
                startWithVideoMuted: true,
                enableWelcomePage: false // Désactive la page de fin Jitsi
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                DEFAULT_REMOTE_DISPLAY_NAME: 'Excellentia User'
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);

        // Fonction de redirection unifiée
        const exitMeeting = () => {
            api.dispose(); // Nettoie l'iframe

            // Redirection selon le rôle
            @if(isset($isHost) && $isHost)
            // Pour le formateur, on force l'arrêt du live en soumettant le formulaire caché
            document.getElementById('stop-live-form').submit();
            @else
            window.location.href = "{{ route('etudiant.lives.index') }}";
            @endif
        };

        // Écouteurs d'événements pour la sortie
        api.addEventListeners({
            videoConferenceLeft: function() {
                exitMeeting();
            },
            readyToClose: function() {
                exitMeeting();
            },
            suspend: function() {
                exitMeeting();
            }
        });
    });
</script>
@endsection