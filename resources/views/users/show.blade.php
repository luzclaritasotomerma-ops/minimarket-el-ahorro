
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Información del Usuario</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nombre:</strong> {{ $user->name }}</p>
            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Rol:</strong> {{ $user->role->name ?? 'Sin rol' }}</p>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="btn btn-warning">
                Editar
            </a>

        </div>
    </div>
</div>
@endsection
