
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de clientes</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            + Nuevo cliente
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('customers.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por nombres, apellidos o documento..."
                   value="{{ $buscar }}">
            <button class="btn btn-dark">Buscar</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Documento</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->document_number ?? '—' }}</td>
                            <td>{{ $customer->names }}</td>
                            <td>{{ $customer->last_names }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="btn btn-warning btn-sm">Editar</a>

                                <form action="{{ route('customers.destroy', $customer) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este cliente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
