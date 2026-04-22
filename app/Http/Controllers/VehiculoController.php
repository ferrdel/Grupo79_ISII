<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Vehiculos;
use App\Models\Sucursales;

class VehiculoController extends Controller
{
    // Vista principal del Admin
    public function index()
    {
        $vehiculos = Vehiculos::all();
        $sucursales = Sucursales::all(); // Para el select de sucursales
        return view('admin.vehiculos', compact('vehiculos', 'sucursales'));
    }

    // Guardar nuevo vehículo
    public function RegistrarVehiculo(Request $request)
    {
        $datos = $request->validate([
            'nro_patente' => 'required|unique:vehiculos',
            'marca'       => 'required',
            'modelo'      => 'required',
            'precio'      => 'required|numeric',
            // Asegúrate de agregar aquí todos los campos que envías desde el modal
            'anio'        => 'required|integer',
            'kilometros'  => 'required|integer',
            'estado'      => 'required',
            'litros_combustible' => 'required|integer',
        ]);

        Vehiculos::create($datos);
        return redirect()->back()->with('success', 'Vehículo agregado correctamente.');
    }

    // Actualizar vehículo
    public function ActualizarVehiculo(Request $request, $nro_patente)
    {
        $vehiculo = Vehiculos::findOrFail($nro_patente);
        $vehiculo->update($request->all());
        return redirect()->back()->with('success', 'Vehículo actualizado.');
    }

    // Eliminar vehículo
    public function EliminarVehiculo($nro_patente)
    {
        Vehiculos::findOrFail($nro_patente)->delete();
        return redirect()->back()->with('success', 'Vehículo eliminado.');
    }
}
