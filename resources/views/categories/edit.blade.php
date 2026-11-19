
@extends('layouts.app')

@section('title', 'Editar categoría')

@section('content')

<div class="content-card p-4">

    <h2 class="fw-bold mb-2">Editar categoría</h2>

    <p class="text-muted mb-4">
        Modifica los datos de la categoría seleccionada.
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

    <form action="{{ route('admin.categories.update', $category) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre de la categoría</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $category->name) }}"
                   maxlength="100"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>

            <textarea name="description"
                      class="form-control"
                      rows="3">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Estado</label>

            <select name="status" class="form-select" required>

                <option value="1"
                    {{ old('status', (string) $category->status) == '1' ? 'selected' : '' }}>
                    Activo
                </option>

                <option value="0"
                    {{ old('status', (string) $category->status) == '0' ? 'selected' : '' }}>
                    Inactivo
                </option>

            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Actualizar categoría
        </button>

        <a href="{{ route('admin.categories.index') }}"
           class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

@endsection
