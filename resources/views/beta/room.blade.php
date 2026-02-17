@extends('layouts.for')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 100px);">

    {{-- Info bar --}}
    <div class="bg-dark text-white p-2 d-flex justify-content-between align-items-center">
        <div>
            <strong>🔴 LIVE : {{ $live->titre }}</strong>
            <span class="badge bg-secondary ms-2">{{ $isHost ? 'Hôte' : 'Participant' }}</span>
        </div>
        <div>
            @if($isHost)
            <span class="text-warning small me-3">Lien participant (test) : </span>
            <a href="{{ route('beta.join', $live->id) }}" target="_blank" class="text-white text-decoration-underline">
                {{ route('beta.join', $live->id) }}
            </a>
            @endif
        </div>
    </div>

    {{-- Jist Frame Container --}}
    <div id="meet" style="width: 100%; height: 100%;"></div>

</div>

{{-- Jitsi Script --}}
<script src='https://meet.jit.si/external_api.js'></script>
<script>
    const domain = "meet.jit.si";
    const options = {
        roomName: "{{ $live->meeting_id }}",
        width: "100%",
        height: "100%",
        parentNode: document.querySelector('#meet'),
        lang: 'fr',
        userInfo: {
            email: "{{ $user->email }}",
            displayName: "{{ $user->prenom }} {{ $user->nom }}"
        },
        configOverwrite: {
            startWithAudioMuted: true,
            prejoinPageEnabled: false
        },
        interfaceConfigOverwrite: {
            SHOW_JITSI_WATERMARK: false,
            TOOLBAR_BUTTONS: [
                'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
                'e2ee'
            ]
        }
    };
    const api = new JitsiMeetExternalAPI(domain, options);

    // Event listeners
    api.addEventListeners({
        videoConferenceJoined: function(e) {
            console.log("Conference joined: ", e);
        },
        videoConferenceLeft: function(e) {
            // Redirection après fin d'appel qui pourrait être utile
            // window.location.href = '/beta/create';
            alert("Vous avez quitté la conférence.");
        }
    });

    // Commandes spécifiques Hôte
    @if($isHost)
    // api.executeCommand('toggleAudio');
    @endif
</script>
@endsection