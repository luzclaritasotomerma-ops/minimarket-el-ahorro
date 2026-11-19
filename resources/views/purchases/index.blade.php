
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de compras</h2>
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
            + Nueva compra
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.purchases.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por número de compra o proveedor..."
                   value="{{ $buscar }}">
            <button class="btn btn-dark">Buscar</button>
            <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
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
                        <th>Proveedor</th>
                        <th>Registrado por</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->purchase_number }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                            <td>{{ $purchase->supplier->business_name }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>S/ {{ number_format($purchase->total, 2) }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $purchase->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.purchases.show', $purchase) }}"
                                   class="btn btn-info btn-sm text-white">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Todavía no hay compras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $purchases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
