
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Registrar nueva compra</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Revisa los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.purchases.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                Datos generales de la compra
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Proveedor *</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    @selected(old('supplier_id') == $supplier->id)>
                                    {{ $supplier->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de compra *</label>
                        <input type="date" name="purchase_date"
                               class="form-control"
                               value="{{ old('purchase_date', date('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observations" class="form-control"
                                  rows="2">{{ old('observations') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Productos de la compra</span>
                <button type="button" class="btn btn-success btn-sm" id="agregarFila">
                    + Agregar producto
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px">Producto</th>
                            <th style="min-width:100px">Cantidad</th>
                            <th style="min-width:130px">Precio unitario (S/)</th>
                            <th style="min-width:130px">Subtotal (S/)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="productos">
                        <tr class="fila-producto">
                            <td>
                                <select name="details[0][product_id]"
                                        class="form-select producto" required>
                                    <option value="">Seleccione</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="details[0][quantity]"
                                       class="form-control cantidad"
                                       min="1" value="1" required>
                            </td>

                            <td>
                                <input type="number" name="details[0][unit_price]"
                                       class="form-control precio"
                                       min="0.01" step="0.01" required>
                            </td>

                            <td>
                                <input type="text" class="form-control subtotal"
                                       value="0.00" readonly>
                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-danger btn-sm eliminarFila">
                                    Quitar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-end">
                    <h4>Total: S/ <span id="total">0.00</span></h4>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar compra
        </button>

        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('productos');
    const botonAgregar = document.getElementById('agregarFila');
    const totalGeneral = document.getElementById('total');

    const opcionesProductos = `
        <option value="">Seleccione</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}">
                {{ $product->code }} - {{ $product->name }}
            </option>
        @endforeach
    `;

    function calcularTotal() {
        let total = 0;

        contenedor.querySelectorAll('.fila-producto').forEach(fila => {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(fila.querySelector('.precio').value) || 0;
            const subtotal = cantidad * precio;

            fila.querySelector('.subtotal').value = subtotal.toFixed(2);
            total += subtotal;
        });

        totalGeneral.textContent = total.toFixed(2);
    }

    function actualizarIndices() {
        contenedor.querySelectorAll('.fila-producto').forEach((fila, index) => {
            fila.querySelector('.producto').name = `details[${index}][product_id]`;
            fila.querySelector('.cantidad').name = `details[${index}][quantity]`;
            fila.querySelector('.precio').name = `details[${index}][unit_price]`;
        });
    }

    function agregarFila() {
        const fila = document.createElement('tr');
        fila.classList.add('fila-producto');

        fila.innerHTML = `
            <td>
                <select class="form-select producto" required>
                    ${opcionesProductos}
                </select>
            </td>
            <td>
                <input type="number" class="form-control cantidad"
                       min="1" value="1" required>
            </td>
            <td>
                <input type="number" class="form-control precio"
                       min="0.01" step="0.01" required>
            </td>
            <td>
                <input type="text" class="form-control subtotal"
                       value="0.00" readonly>
            </td>
            <td>
                <button type="button"
                        class="btn btn-danger btn-sm eliminarFila">
                    Quitar
                </button>
            </td>
        `;

        contenedor.appendChild(fila);
        actualizarIndices();
    }

    botonAgregar.addEventListener('click', agregarFila);

    contenedor.addEventListener('input', function (event) {
        if (event.target.matches('.cantidad, .precio')) {
            calcularTotal();
        }
    });

    contenedor.addEventListener('change', function (event) {
        if (event.target.matches('.producto')) {
            const seleccionados = [...contenedor.querySelectorAll('.producto')]
                .map(select => select.value)
                .filter(value => value !== '');

            if (new Set(seleccionados).size !== seleccionados.length) {
                alert('No puedes seleccionar el mismo producto dos veces. Aumenta su cantidad en la fila existente.');
                event.target.value = '';
            }
        }
    });

    contenedor.addEventListener('click', function (event) {
        if (event.target.matches('.eliminarFila')) {
            const filas = contenedor.querySelectorAll('.fila-producto');

            if (filas.length > 1) {
                event.target.closest('tr').remove();
                actualizarIndices();
                calcularTotal();
            } else {
                alert('Debes mantener al menos un producto.');
            }
        }
    });

    actualizarIndices();
    calcularTotal();
});
</script>
@endsection
