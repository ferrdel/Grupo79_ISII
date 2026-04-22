<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCrearLabel">Registrar Nuevo Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('vehiculos.RegistrarVehiculo') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Nro. Patente</label>
                                <input type="text" name="nro_patente" class="form-control" placeholder="Ej: ABC 123" required maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca" class="form-control" placeholder="Ej: Toyota" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo" class="form-control" placeholder="Ej: Corolla" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Año</label>
                                <input type="number" name="anio" class="form-control" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio por Día ($)</label>
                                <input type="number" name="precio" class="form-control" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kilómetros</label>
                                <input type="number" name="kilometros" class="form-control" min="0" placeholder="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Litros de Combustible</label>
                                <input type="number" name="litros_combustible" class="form-control" placeholder="Capacidad tanque" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="" selected disabled>Seleccionar estado...</option>
                                    <option value="Disponible">Disponible</option>
                                    <option value="Alquilado">Alquilado</option>
                                    <option value="Mantenimiento">En Mantenimiento</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">URL Imagen (Opcional)</label>
                                <input type="text" name="imagen" class="form-control" placeholder="Ruta de la imagen">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Descripción (Opcional)</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalles adicionales..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Vehículo</button>
                </div>
            </form>
        </div>
    </div>
</div>