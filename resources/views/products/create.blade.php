
@extends('layouts.app')

@section('title', 'Registrar producto')

@section('content')

<div class="content-card p-4">

    <h2 class="fw-bold">Registrar producto</h2>
    <p class="text-muted mb-4">Ingresa los datos del nuevo producto.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Categoría</label>

                <select name="category_id" class="form-select" required>
                    <option value="">Seleccionar categoría</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Código del producto</label>

                <input type="text" name="code" class="form-control"
                       value="{{ old('code') }}"
                       maxlength="50" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Nombre del producto</label>

                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}"
                       maxlength="150" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción</label>

                <textarea name="description" class="form-control"
                          rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio de compra (S/)</label>

                <input type="number" name="purchase_price"
                       class="form-control" step="0.01" min="0"
                       value="{{ old('purchase_price', '0.00') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio de venta (S/)</label>

                <input type="number" name="sale_price"
                       class="form-control" step="0.01" min="0.01"
                       value="{{ old('sale_price') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Stock inicial</label>

                <input type="number" name="stock" class="form-control"
                       min="0" value="{{ old('stock', 0) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Stock mínimo</label>

                <input type="number" name="minimum_stock" class="form-control"
                       min="0" value="{{ old('minimum_stock', 5) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Unidad de medida</label>

                <select name="unit" class="form-select" required>
                    @foreach(['unidad', 'paquete', 'caja', 'botella', 'litro', 'kilo', 'gramo', 'bolsa'] as $unidad)
                        <option value="{{ $unidad }}"
                            {{ old('unit', 'unidad') == $unidad ? 'selected' : '' }}>
                            {{ ucfirst($unidad) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
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

        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Guardar producto
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>

</div>

@endsection
