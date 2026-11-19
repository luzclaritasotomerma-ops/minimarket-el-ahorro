
@extends('layouts.app')

@section('title', 'Categorías')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Categorías</h2>
        <p class="text-muted">Administración de categorías de productos.</p>
    </div>

    <a href="{{ route('admin.categories.create') }}"
       class="btn btn-primary">
        + Nueva categoría
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="content-card p-4">

    <form method="GET"
          action="{{ route('admin.categories.index') }}"
          class="row g-2 mb-4">

        <div class="col-md-8">
            <input type="text"
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar categoría..."
                   value="{{ $buscar }}">
        </div>

        <div class="col-md-4">
            <button class="btn btn-dark">
                Buscar
            </button>

            <a href="{{ route('admin.categories.index') }}"
               class="btn btn-secondary">
                Limpiar
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr>
                        <td>{{ $category->id }}</td>

                        <td>{{ $category->name }}</td>

                        <td>{{ $category->description ?? 'Sin descripción' }}</td>

                        <td>
                            @if($category->status)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $category) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay categorías registradas.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <div class="mt-3">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>

</div>

<a href="{{ route('dashboard') }}"
   class="btn btn-secondary mt-3">
    Volver al panel principal
</a>

@endsection
