@extends('layouts.admin') {{-- Usamos la plantilla base --}}

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Gestión de Flota</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus"></i> Registrar Vehiculo
            </button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Patente</th>
                        <th>Vehículo</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculos as $v)
                    <tr>
                        <td><strong>{{ $v->nro_patente }}</strong></td>
                        <td>{{ $v->marca }} {{ $v->modelo }} ({{ $v->anio }})</td>
                        <td>${{ number_format($v->precio, 2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $v->nro_patente }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('vehiculos.EliminarVehiculo', $v->nro_patente) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desea Eliminar?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    {{-- Incluimos el modal de edición para CADA vehículo --}}
                    @include('admin.modal_edit') 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Incluimos el modal de creación UNA sola vez --}}
@include('admin.modal_create')

@endsection