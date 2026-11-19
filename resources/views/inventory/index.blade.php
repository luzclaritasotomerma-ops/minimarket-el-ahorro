
@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Control de Inventario</h2>
            <p class="text-muted">Consulta las existencias y movimientos de productos.</p>
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total de productos</h6>
                    <h2 class="text-primary">{{ $totalProductos }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Productos con stock bajo</h6>
                    <h2 class="text-danger">{{ $productosStockBajo }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- LISTADO DE PRODUCTOS --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Existencias de productos</h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('inventory.index') }}" class="row mb-3">

                <div class="col-md-6 mb-2">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Buscar por nombre o código..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-2">
                    <select name="stock_status" class="form-select">
                        <option value="">Todos los productos</option>
                        <option value="bajo" {{ request('stock_status') == 'bajo' ? 'selected' : '' }}>
                            Solo stock bajo
                        </option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary">
                        Buscar
                    </button>

                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        Limpiar
                    </a>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock actual</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($productos as $producto)

                            <tr>
                                <td>{{ $producto->code }}</td>

                                <td>{{ $producto->name }}</td>

                                <td>
                                    {{ $producto->category->name ?? 'Sin categoría' }}
                                </td>

                                <td>
                                    <strong>{{ $producto->stock }}</strong>
                                </td>

                                <td>{{ $producto->minimum_stock }}</td>

                                <td>
                                    @if($producto->stock <= $producto->minimum_stock)
                                        <span class="badge bg-danger">
                                            Stock bajo
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Disponible
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    No se encontraron productos.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $productos->links() }}

        </div>
    </div>

    {{-- HISTORIAL DE MOVIMIENTOS --}}
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Historial de movimientos de inventario</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Stock anterior</th>
                            <th>Stock nuevo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($movimientos as $movimiento)

                            <tr>
                                <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>

                                <td>
                                    {{ $movimiento->product->name ?? 'Producto eliminado' }}
                                </td>

                                <td>
                                    @if($movimiento->type == 'entrada')
                                        <span class="badge bg-success">Entrada</span>
                                    @elseif($movimiento->type == 'salida')
                                        <span class="badge bg-danger">Salida</span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ ucfirst($movimiento->type) }}
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $movimiento->quantity }}</td>

                                <td>{{ $movimiento->stock_before }}</td>

                                <td>{{ $movimiento->stock_after }}</td>

                                <td>{{ $movimiento->user->name ?? 'No disponible' }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    Todavía no existen movimientos de inventario.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $movimientos->links() }}

        </div>
    </div>

</div>

@endsection
