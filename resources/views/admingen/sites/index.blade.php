@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des sites</h1>

    <a href="{{ route('admingen.sites.create') }}" class="btn btn-success mb-3">Créer un nouveau site</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($sites->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->nom }}</td>
                    <td>{{ $site->code }}</td>
                    <td>
                        <a href="{{ route('admingen.sites.edit', $site->id) }}" class="btn btn-primary btn-sm">Modifier</a>

                        <form action="{{ route('admingen.sites.destroy', $site->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun site trouvé.</p>
    @endif
</div>
@endsection
