
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Editar Usuario</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.users.update', $user->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Nueva contraseña (opcional)
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar nueva contraseña</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Actualizar Usuario
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>
</div>
@endsection
