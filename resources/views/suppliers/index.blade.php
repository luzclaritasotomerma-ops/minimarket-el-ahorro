
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de proveedores</h2>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            + Nuevo proveedor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.suppliers.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por empresa o documento..."
                   value="{{ $buscar }}">
            <button class="btn btn-dark">Buscar</button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Documento</th>
                        <th>Empresa</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->document_number }}</td>
                            <td>{{ $supplier->business_name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>
                                @if($supplier->status)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                   class="btn btn-warning btn-sm">Editar</a>

                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay proveedores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
