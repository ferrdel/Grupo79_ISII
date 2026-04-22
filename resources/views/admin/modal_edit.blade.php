<div class="modal fade" id="modalEditar{{ str_replace(' ', '', $v->nro_patente) }}" tabindex="-1"aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modificar Vehículo: {{ $v->nro_patente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('vehiculos.ActualizarVehiculo', $v->nro_patente) }}" method="POST">
                @csrf
                @method('PUT') {{-- Esto es vital para que Laravel sepa que es una actualización --}}
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Nro. Patente (No editable)</label>
                                <input type="text" class="form-control" value="{{ $v->nro_patente }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca" class="form-control" value="{{ $v->marca }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo" class="form-control" value="{{ $v->modelo }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio por Día ($)</label>
                                <input type="number" name="precio" class="form-control" step="0.01" value="{{ $v->precio }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kilómetros</label>
                                <input type="number" name="kilometros" class="form-control" value="{{ $v->kilometros }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="Disponible" {{ $v->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="Alquilado" {{ $v->estado == 'Alquilado' ? 'selected' : '' }}>Alquilado</option>
                                    <option value="Mantenimiento" {{ $v->estado == 'Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Litros de Combustible</label>
                                <input type="number" name="litros_combustible" class="form-control" value="{{ $v->litros_combustible }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">URL Imagen</label>
                                <input type="text" name="imagen" class="form-control" value="{{ $v->imagen }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3">{{ $v->descripcion }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>