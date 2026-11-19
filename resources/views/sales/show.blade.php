
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle de venta</h2>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Volver a ventas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            Información general
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Número de venta:</strong>
                    {{ $sale->sale_number }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Fecha:</strong>
                    {{ $sale->sale_date }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Cliente:</strong>

                    @if($sale->customer)
                        {{ $sale->customer->names }}
                        {{ $sale->customer->last_names }}
                    @else
                        Cliente general
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Vendedor:</strong>
                    {{ $sale->user->name }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Forma de pago:</strong>
                    {{ $sale->payment_method }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Estado:</strong>
                    <span class="badge bg-success">{{ $sale->status }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Productos vendidos
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sale->details as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>S/ {{ number_format($detail->unit_price, 2) }}</td>
                            <td>S/ {{ number_format($detail->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotal:</th>
                        <th>S/ {{ number_format($sale->subtotal, 2) }}</th>
                    </tr>

                    <tr>
                        <th colspan="3" class="text-end">Impuesto:</th>
                        <th>S/ {{ number_format($sale->tax, 2) }}</th>
                    </tr>

                    <tr>
                        <th colspan="3" class="text-end">TOTAL:</th>
                        <th class="text-success">
                            S/ {{ number_format($sale->total, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
