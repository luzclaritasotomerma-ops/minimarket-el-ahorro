
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle de compra</h2>
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
            Volver a compras
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
                    <strong>Número de compra:</strong>
                    {{ $purchase->purchase_number }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Fecha:</strong>
                    {{ $purchase->purchase_date }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Proveedor:</strong>
                    {{ $purchase->supplier->business_name }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Registrado por:</strong>
                    {{ $purchase->user->name }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Estado:</strong>
                    <span class="badge bg-success">{{ $purchase->status }}</span>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Observaciones:</strong>
                    {{ $purchase->observations ?: 'Sin observaciones' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Productos comprados
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
                    @foreach($purchase->details as $detail)
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
                        <th colspan="3" class="text-end">TOTAL:</th>
                        <th>S/ {{ number_format($purchase->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
