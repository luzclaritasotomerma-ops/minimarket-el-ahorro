
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de ventas</h2>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            + Nueva venta
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('sales.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por número de venta o cliente..."
                   value="{{ $buscar }}">
            <button class="btn btn-dark">Buscar</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                Limpiar
            </a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Número</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>
                            <td>{{ $sale->sale_date }}</td>
                            <td>
                                @if($sale->customer)
                                    {{ $sale->customer->names }}
                                    {{ $sale->customer->last_names }}
                                @else
                                    Cliente general
                                @endif
                            </td>
                            <td>{{ $sale->user->name }}</td>
                            <td>S/ {{ number_format($sale->total, 2) }}</td>
                            <td>{{ $sale->payment_method }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}"
                                   class="btn btn-info btn-sm text-white">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Todavía no hay ventas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $sales->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
