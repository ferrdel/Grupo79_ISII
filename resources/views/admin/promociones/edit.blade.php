@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h2 class="fw-bold text-dark mb-4">Modificar Promoción: {{ $nombreMes }}</h2>

    <form action="{{ route('promociones.update', $promocion->id_promocion) }}" method="POST" class="bg-white p-4 rounded-3 shadow-sm border">
        @csrf
        @method('PUT') <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Nombre de la Promoción</label>
            <input type="text" class="form-control fw-bold" value="{{ $promocion->nombre_promo }}" readonly style="background-color: #f8f9fa;">
        </div>

        <div class="row mb-4">
            <h5 class="fw-bold text-primary mb-3">1. Período de la Promoción</h5>
            <div class="col-md-6 mb-3">
                <label class="form-label small text-muted fw-bold">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $promocion->fecha_inicio }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small text-muted fw-bold">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $promocion->fecha_fin }}" class="form-control">
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold text-primary mb-3">2. Ajuste de Tarifa Base</h5>
            <div class="row align-items-center">
                <div class="col-md-9 mb-2">
                    <input type="range" class="form-range" min="5" max="50" step="5" id="descuentoRange" value="{{ $descuentoActual }}" oninput="document.getElementById('descuentoInput').value = this.value">
                </div>
                <div class="col-md-3 mb-2">
                    <div class="input-group">
                        <input type="number" id="descuentoInput" name="descuento" value="{{ $descuentoActual }}" class="form-control text-center fw-bold text-danger fs-5" readonly style="background-color: #f8f9fa;">
                        <span class="input-group-text fw-bold text-danger fs-5">%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold text-primary mb-3">3. Bonus de Accesorios</h5>
            <div class="card p-3 bg-light border-0">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="gps_gratis" id="gps" value="1" {{ $promocion->gps_gratis ? 'checked' : '' }}>
                    <label class="form-check-label d-flex justify-content-between w-100" for="gps">
                        <span class="fw-bold text-dark">GPS Sin Cargo</span>
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="silla_bebe_descuento" id="silla" value="1" {{ $promocion->silla_bebe_descuento ? 'checked' : '' }}>
                    <label class="form-check-label d-flex justify-content-between w-100" for="silla">
                        <span class="fw-bold text-dark">Silla de Bebé (50% OFF)</span>
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="conductor_gratis" id="conductor" value="1" {{ $promocion->conductor_gratis ? 'checked' : '' }}>
                    <label class="form-check-label d-flex justify-content-between w-100" for="conductor">
                        <span class="fw-bold text-dark">Conductor Adicional Gratis</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4 border fw-bold">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection