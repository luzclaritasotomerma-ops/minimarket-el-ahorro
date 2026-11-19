@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Reportes del Minimarket El Ahorro</h2>
        <p class="text-muted">
            Consulta las ventas, ingresos, productos más vendidos y stock bajo.
        </p>
    </div>

    {{-- FILTRO DE FECHAS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Filtrar reportes por fechas</h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports.index') }}"
                  class="row align-items-end">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha desde</label>
                    <input type="date" name="desde"
                           class="form-control"
                           value="{{ $desde }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha hasta</label>
                    <input type="date" name="hasta"
                           class="form-control"
                           value="{{ $hasta }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary">
                        Consultar
                    </button>

                    <a href="{{ route('admin.reports.index') }}"
                       class="btn btn-secondary">
                        Limpiar
                    </a>
                </div>

            </form>

        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total de ventas</h6>
                    <h2 class="text-primary">
                        {{ $totalVentas }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Ingresos por ventas</h6>
                    <h2 class="text-success">
                        S/ {{ number_format($totalIngresos, 2) }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- REPORTE DE VENTAS --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Reporte de ventas</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Forma de pago</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($ventas as $venta)

                            <tr>
                                <td>{{ $venta->sale_number }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($venta->sale_date)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $venta->customer->names ?? 'Cliente general' }}
                                </td>

                                <td>
                                    {{ $venta->user->name ?? 'No disponible' }}
                                </td>

                                <td>{{ $venta->payment_method }}</td>

                                <td>
                                    S/ {{ number_format($venta->total, 2) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    No existen ventas en este período.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{ $ventas->links() }}

        </div>
    </div>

    {{-- PRODUCTOS MAS VENDIDOS --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Productos más vendidos</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Cantidad vendida</th>
                            <th>Total vendido</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($productosMasVendidos as $item)

                            <tr>
                                <td>
                                    {{ $item->product->name ?? 'Producto no disponible' }}
                                </td>

                                <td>
                                    {{ $item->product->code ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->cantidad_vendida }}
                                </td>

                                <td>
                                    S/ {{ number_format($item->total_vendido, 2) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    No existen productos vendidos en este período.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- PRODUCTOS CON STOCK BAJO --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Productos con stock bajo</h5>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <h5 class="text-danger">
                    Total de productos con stock bajo:
                    {{ $cantidadStockBajo }}
                </h5>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock actual</th>
                            <th>Stock mínimo</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($productosStockBajo as $producto)

                            <tr>
                                <td>{{ $producto->code }}</td>

                                <td>{{ $producto->name }}</td>

                                <td>
                                    {{ $producto->category->name ?? 'Sin categoría' }}
                                </td>

                                <td>
                                    <strong class="text-danger">
                                        {{ $producto->stock }}
                                    </strong>
                                </td>

                                <td>{{ $producto->minimum_stock }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    No hay productos con stock bajo.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection
