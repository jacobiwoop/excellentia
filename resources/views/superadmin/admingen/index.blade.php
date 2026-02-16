@extends('layouts.dash')

@section('content')
<div class="container py-5">
    <h2>Liste des Admins Généraux</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('superadmin.admin_gen.create') }}" class="btn btn-success mb-3">Nouveau Admin Général</a>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    <a href="{{ route('superadmin.admin_gen.edit', $admin->id) }}" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-edit"></i> Editer
                    </a>

                    <form action="{{ route('superadmin.admin_gen.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet admin général ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $admins->links() }}
</div>
@endsection
