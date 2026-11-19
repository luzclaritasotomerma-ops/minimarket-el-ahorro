
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Registrar proveedor</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">RUC o número de documento *</label>
                    <input type="text" name="document_number"
                           value="{{ old('document_number') }}"
                           class="form-control @error('document_number') is-invalid @enderror"
                           required maxlength="20">
                    @error('document_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Razón social *</label>
                    <input type="text" name="business_name"
                           value="{{ old('business_name') }}"
                           class="form-control @error('business_name') is-invalid @enderror"
                           required maxlength="150">
                    @error('business_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select" required>
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Guardar proveedor</button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
