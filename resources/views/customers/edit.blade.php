
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar cliente</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">DNI o documento</label>
                    <input type="text" name="document_number"
                           value="{{ old('document_number', $customer->document_number) }}"
                           class="form-control @error('document_number') is-invalid @enderror"
                           maxlength="20">
                    @error('document_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres *</label>
                        <input type="text" name="names"
                               value="{{ old('names', $customer->names) }}"
                               class="form-control @error('names') is-invalid @enderror"
                               required maxlength="100">
                        @error('names')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="last_names"
                               value="{{ old('last_names', $customer->last_names) }}"
                               class="form-control" maxlength="100">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $customer->phone) }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email"
                               value="{{ old('email', $customer->email) }}"
                               class="form-control">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="address"
                           value="{{ old('address', $customer->address) }}"
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar cliente</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
