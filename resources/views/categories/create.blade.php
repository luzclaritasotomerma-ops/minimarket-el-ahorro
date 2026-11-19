
@extends('layouts.app')

@section('title', 'Registrar categoría')

@section('content')

<div class="content-card p-4">

    <h2 class="fw-bold mb-2">Registrar categoría</h2>

    <p class="text-muted mb-4">
        Completa los datos de la nueva categoría.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre de la categoría</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   maxlength="100"
                   placeholder="Ejemplo: Bebidas"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>

            <textarea name="description"
                      class="form-control"
                      rows="3"
                      placeholder="Descripción de la categoría">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Estado</label>

            <select name="status" class="form-select" required>
                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                    Activo
                </option>

                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                    Inactivo
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Guardar categoría
        </button>

        <a href="{{ route('admin.categories.index') }}"
           class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

@endsection
