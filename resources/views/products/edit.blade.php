
@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')

<div class="content-card p-4">

    <h2 class="fw-bold">Editar producto</h2>
    <p class="text-muted mb-4">Modifica los datos del producto seleccionado.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Categoría</label>

                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Código</label>

                <input type="text" name="code" class="form-control"
                       value="{{ old('code', $product->code) }}"
                       maxlength="50" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Nombre del producto</label>

                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $product->name) }}"
                       maxlength="150" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción</label>

                <textarea name="description" class="form-control"
                          rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio de compra (S/)</label>

                <input type="number" name="purchase_price"
                       class="form-control" step="0.01" min="0"
                       value="{{ old('purchase_price', $product->purchase_price) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio de venta (S/)</label>

                <input type="number" name="sale_price"
                       class="form-control" step="0.01" min="0.01"
                       value="{{ old('sale_price', $product->sale_price) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Stock</label>

                <input type="number" name="stock" class="form-control"
                       min="0" value="{{ old('stock', $product->stock) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Stock mínimo</label>

                <input type="number" name="minimum_stock" class="form-control"
                       min="0" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Unidad</label>

                <select name="unit" class="form-select" required>
                    @foreach(['unidad', 'paquete', 'caja', 'botella', 'litro', 'kilo', 'gramo', 'bolsa'] as $unidad)
                        <option value="{{ $unidad }}"
                            {{ old('unit', $product->unit) == $unidad ? 'selected' : '' }}>
                            {{ ucfirst($unidad) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Estado</label>

                <select name="status" class="form-select" required>
                    <option value="1" {{ old('status', (string)$product->status) == '1' ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="0" {{ old('status', (string)$product->status) == '0' ? 'selected' : '' }}>
                        Inactivo
                    </option>
                </select>
            </div>

        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Actualizar producto
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>

</div>

@endsection
