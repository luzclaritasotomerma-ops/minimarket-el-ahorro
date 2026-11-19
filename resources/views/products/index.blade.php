
@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Productos</h2>
        <p class="text-muted">Administración de productos del minimarket.</p>
    </div>

    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        + Nuevo producto
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="content-card p-4">

    <form method="GET" action="{{ route('admin.products.index') }}"
          class="row g-3 mb-4">

        <div class="col-md-5">
            <input type="text"
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar por nombre o código..."
                   value="{{ $buscar }}">
        </div>

        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Todas las categorías</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string)$categoryId === (string)$category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-dark">Buscar</button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">
                Limpiar
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio compra</th>
                    <th>Precio venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->code }}</td>

                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>

                        <td>{{ $product->category->name }}</td>

                        <td>S/ {{ number_format($product->purchase_price, 2) }}</td>

                        <td>S/ {{ number_format($product->sale_price, 2) }}</td>

                        <td>
                            @if($product->stock <= $product->minimum_stock)
                                <span class="badge bg-danger">
                                    {{ $product->stock }} {{ $product->unit }}
                                </span>
                            @else
                                <span class="badge bg-success">
                                    {{ $product->stock }} {{ $product->unit }}
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($product->status)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.products.destroy', $product) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Deseas eliminar este producto?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No hay productos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>

<a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
    Volver al panel principal
</a>

@endsection
