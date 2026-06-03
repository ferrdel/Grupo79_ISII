@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    
    <!-- Título dinámico según el botón que presionaste -->
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0">Nueva Promoción: {{ $nombreMes }} - Impulso de Demanda</h2>
    </div>

    <!-- El formulario apunta a la ruta 'store' para guardar los datos en MySQL -->
    <form action="{{ route('promociones.store') }}" method="POST" class="bg-white p-4 rounded-3 shadow-sm border">
        @csrf <!-- Token de seguridad obligatorio de Laravel para formularios POST -->
        
        <!-- Nombre automático de la promoción oculto o visible -->
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Nombre de la Promoción</label>
            <input type="text" name="nombre_promo" value="Impulso Demanda {{ $nombreMes }} - 2026" class="form-control fw-bold text-primary" readonly style="background-color: #f8f9fa;">
        </div>

        <!-- 1. Período de la Promoción -->
        <div class="row mb-4">
            <h5 class="fw-bold text-primary mb-3">1. Período de la Promoción</h5>
            <div class="col-md-6 mb-3">
                <label class="form-label small text-muted fw-bold">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control p-2">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small text-muted fw-bold">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control p-2">
            </div>
        </div>

        <!-- 2. Ajuste de Tarifa Base -->
        <div class="mb-4">
            <h5 class="fw-bold text-primary mb-3">2. Ajuste de Tarifa Base</h5>
            <div class="row align-items-center">
                <div class="col-md-9 mb-2">
                    <!-- Control deslizante (Range) de Bootstrap -->
                    <input type="range" class="form-range" min="5" max="50" step="5" id="descuentoRange" value="{{ $descuentoSugerido }}" oninput="actualizarDescuento(this.value)">
                    <span class="small text-muted d-block mt-1">Aplicar descuento sobre el precio base diario de todos los vehículos.</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="input-group">
                        <!-- Input real que se envía al servidor -->
                        <input type="number" id="descuentoInput" name="descuento" value="{{ $descuentoSugerido }}" class="form-control text-center fw-bold text-danger fs-5" readonly style="background-color: #f8f9fa;">
                        <span class="input-group-text fw-bold text-danger fs-5">%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Bonus de Accesorios -->
        <div class="mb-4">
            <h5 class="fw-bold text-primary mb-3">3. Bonus de Accesorios</h5>
            
            <div class="card p-3 bg-light border-0">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="gps_gratis" id="gps" checked value="1">
                    <label class="form-check-label d-flex justify-content-between w-100" for="gps">
                        <span class="fw-bold text-dark">GPS Sin Cargo</span>
                        <span class="text-muted small">si alquiler > 3 días</span>
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="silla_bebe_descuento" id="silla" value="1">
                    <label class="form-check-label d-flex justify-content-between w-100" for="silla">
                        <span class="fw-bold text-dark">Silla de Bebé (50% OFF)</span>
                        <span class="text-muted small">si alquiler > 3 días</span>
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="conductor_gratis" id="conductor" checked value="1">
                    <label class="form-check-label d-flex justify-content-between w-100" for="conductor">
                        <span class="fw-bold text-dark">Conductor Adicional Gratis</span>
                        <span class="text-muted small">si alquiler > 3 días</span>
                    </label>
                </div>
            </div>
        </div>

        <hr class="my-4 text-muted">

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4 borderfw-bold">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Guardar y Activar Promoción</button>
        </div>
    </form>
</div>

<!-- JavaScript simple para sincronizar el deslizador con el número del porcentaje -->
<script>
    function actualizarDescuento(valor) {
        document.getElementById('descuentoInput').value = valor;
    }
</script>
@endsection