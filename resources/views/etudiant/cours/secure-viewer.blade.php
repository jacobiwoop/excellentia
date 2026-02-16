@extends('layouts.stu-simple')

@section('content')
<div class="container-fluid p-0 vh-100 bg-dark">
    <a href="{{ route('etudiant.cours.index') }}" 
       class="position-absolute top-0 end-0 m-3 btn btn-light btn-sm">
       <i class="fas fa-times"></i>
    </a>

    @switch($extension)
        @case('pdf')
            <iframe src="{{ route('etudiant.cours.fileProxy', $cour->id) }}#toolbar=0"
                    class="w-100 h-100" style="border:none;"></iframe>
            @break

        @case('doc')
        @case('docx')
        @case('xls')
        @case('xlsx')
        @case('ppt')
        @case('pptx')
            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(route('etudiant.cours.fileProxy', $cour->id)) }}"
                    class="w-100 h-100" frameborder="0"></iframe>
            @break

        @case('jpg')
        @case('jpeg')
        @case('png')
        @case('gif')
            <div class="d-flex justify-content-center align-items-center h-100">
                <img src="{{ route('etudiant.cours.fileProxy', $cour->id) }}"
                     style="max-height:90vh; max-width:100%;" oncontextmenu="return false;">
            </div>
            @break

        @case('mp4')
        @case('mov')
        @case('avi')
            <video controls controlsList="nodownload" class="w-100 h-100">
                <source src="{{ route('etudiant.cours.fileProxy', $cour->id) }}" type="video/{{ $extension }}">
            </video>
            @break

        @default
            <div class="d-flex justify-content-center align-items-center h-100 text-white">
                <h4>Format .{{ $extension }} non supporté</h4>
                <a href="{{ route('etudiant.cours.index') }}" class="btn btn-primary mt-3">Retour</a>
            </div>
    @endswitch
</div>

<script>
// Blocage clic droit et touches Ctrl+S/P
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
    if (e.ctrlKey && ['s','p','u'].includes(e.key.toLowerCase())) {
        e.preventDefault();
        alert('Téléchargement désactivé');
    }
});
</script>
@endsection
