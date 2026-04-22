<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5>Registrar Auto</h5></div>
                <div class="modal-body">
                    <input type="text" name="nro_patente" class="form-control mb-2" placeholder="Patente" required>
                    <input type="text" name="marca" class="form-control mb-2" placeholder="Marca" required>
                    <input type="text" name="modelo" class="form-control mb-2" placeholder="Modelo" required>
                    <input type="number" name="precio" class="form-control mb-2" placeholder="Precio por día" required>
                    <select name="id_sucursal" class="form-select">
                        @foreach($sucursales as $s)
                            <option value="{{ $s->id_sucursal }}">{{ $s->nombre_suc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>