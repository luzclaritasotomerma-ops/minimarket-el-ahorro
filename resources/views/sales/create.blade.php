
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Registrar nueva venta</h2>

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

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                Datos generales de la venta
            </div>

            <div class="card-body">
                <div class="row">
<!-- Sección de pago con Yape -->
<div id="seccionYape" class="mt-3 p-3 border rounded"
     style="display: none;">

    <h5 class="text-center text-primary">
        Pago mediante Yape
    </h5>

    <p class="text-center">
        Escanea el siguiente QR desde tu aplicación Yape.
    </p>

    <div class="text-center">
        <img src="{{ asset('images/yape-qr.png') }}"
             alt="QR de Yape del negocio"
             style="width: 220px; max-width: 100%;">

        <h3 class="text-success mt-3">
            Importe: S/ <span id="montoYape">0.00</span>
        </h3>
    </div>

    <div class="alert alert-info mt-3">
        El responsable debe verificar el ingreso del dinero
        en la aplicación Yape antes de confirmar la venta.
    </div>

    <div class="form-check">
        <input type="checkbox"
               class="form-check-input"
               name="payment_verified"
               value="1"
               id="payment_verified">

        <label class="form-check-label" for="payment_verified">
            He verificado que el pago llegó correctamente a la cuenta del negocio.
        </label>
    </div>
</div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="customer_id" class="form-select">
                            <option value="">Cliente general</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->names }} {{ $customer->last_names }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Puedes registrar la venta sin seleccionar un cliente.
                        </small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de venta *</label>
                        <input type="date" name="sale_date" class="form-control"
                               value="{{ old('sale_date', date('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Forma de pago *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="Efectivo"
                                @selected(old('payment_method') == 'Efectivo')>
                                Efectivo
                            </option>
                            <option value="Transferencia"
                                @selected(old('payment_method') == 'Transferencia')>
                                Transferencia
                            </option>
                            <option value="Tarjeta"
                                @selected(old('payment_method') == 'Tarjeta')>
                                Tarjeta
                            </option>
                            <option value="Yape"
    @selected(old('payment_method') == 'Yape')>
    Yape
</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Productos de la venta</span>

                <button type="button" class="btn btn-success btn-sm"
                        id="agregarFila">
                    + Agregar producto
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px">Producto</th>
                            <th style="min-width:100px">Stock disponible</th>
                            <th style="min-width:100px">Cantidad</th>
                            <th style="min-width:130px">Precio unitario</th>
                            <th style="min-width:130px">Subtotal</th>
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
                                        <option value="{{ $product->id }}"
                                                data-stock="{{ $product->stock }}"
                                                data-price="{{ $product->sale_price }}">
                                            {{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" class="form-control stock"
                                       value="0" readonly>
                            </td>

                            <td>
                                <input type="number" name="details[0][quantity]"
                                       class="form-control cantidad"
                                       min="1" value="1" required>
                            </td>

                            <td>
                                <input type="text" class="form-control precio"
                                       value="0.00" readonly>
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
                    <h4>Subtotal: S/ <span id="subtotalGeneral">0.00</span></h4>
                    <h4>Impuesto: S/ 0.00</h4>
                    <h3 class="text-success">
                        TOTAL: S/ <span id="totalGeneral">0.00</span>
                    </h3>
                </div>

                <small class="text-muted">
                    Los precios se obtienen del registro de productos.
                    El stock se verifica nuevamente al guardar la venta.
                </small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar venta
        </button>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('productos');

    const opcionesProductos = `
        <option value="">Seleccione</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}"
                    data-stock="{{ $product->stock }}"
                    data-price="{{ $product->sale_price }}">
                {{ $product->code }} - {{ $product->name }}
            </option>
        @endforeach
    `;

    function calcularTotal() {
        let total = 0;

        contenedor.querySelectorAll('.fila-producto').forEach(fila => {
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(fila.querySelector('.precio').value) || 0;
            const subtotal = cantidad * precio;

            fila.querySelector('.subtotal').value = subtotal.toFixed(2);
            total += subtotal;
        });

        document.getElementById('subtotalGeneral').textContent = total.toFixed(2);
        document.getElementById('totalGeneral').textContent = total.toFixed(2);
        document.getElementById('montoYape').textContent = total.toFixed(2);
    }

    function actualizarIndices() {
        contenedor.querySelectorAll('.fila-producto').forEach((fila, index) => {
            fila.querySelector('.producto').name = `details[${index}][product_id]`;
            fila.querySelector('.cantidad').name = `details[${index}][quantity]`;
        });

        const metodoPago = document.querySelector('[name="payment_method"]');
const seccionYape = document.getElementById('seccionYape');
const confirmacionYape = document.getElementById('payment_verified');

function actualizarMetodoPago() {
    const esYape = metodoPago.value === 'Yape';

    seccionYape.style.display = esYape ? 'block' : 'none';
    confirmacionYape.required = esYape;

    if (!esYape) {
        confirmacionYape.checked = false;
    }
}

metodoPago.addEventListener('change', actualizarMetodoPago);
actualizarMetodoPago();
    }

    function actualizarProducto(select) {
        const fila = select.closest('tr');
        const opcion = select.selectedOptions[0];

        fila.querySelector('.stock').value = opcion.dataset.stock || 0;
        fila.querySelector('.precio').value =
            parseFloat(opcion.dataset.price || 0).toFixed(2);

        const cantidad = fila.querySelector('.cantidad');
        cantidad.max = opcion.dataset.stock || 1;
        cantidad.value = 1;

        calcularTotal();
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
                <input type="text" class="form-control stock" value="0" readonly>
            </td>

            <td>
                <input type="number" class="form-control cantidad"
                       min="1" value="1" required>
            </td>

            <td>
                <input type="text" class="form-control precio"
                       value="0.00" readonly>
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

    contenedor.addEventListener('change', function (event) {
        if (event.target.matches('.producto')) {
            const seleccionados = [...contenedor.querySelectorAll('.producto')]
                .map(select => select.value)
                .filter(value => value !== '');

            if (new Set(seleccionados).size !== seleccionados.length) {
                alert('Este producto ya está agregado. Modifica su cantidad en la fila existente.');
                event.target.value = '';
                actualizarProducto(event.target);
                return;
            }

            actualizarProducto(event.target);
        }
    });

    contenedor.addEventListener('input', function (event) {
        if (event.target.matches('.cantidad')) {
            const fila = event.target.closest('tr');
            const stock = parseInt(fila.querySelector('.stock').value) || 0;

            if (parseInt(event.target.value) > stock) {
                alert('La cantidad no puede superar el stock disponible.');
                event.target.value = stock;
            }

            calcularTotal();
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

    document.getElementById('agregarFila')
        .addEventListener('click', agregarFila);

    actualizarIndices();
    calcularTotal();
});
</script>
@endsection
