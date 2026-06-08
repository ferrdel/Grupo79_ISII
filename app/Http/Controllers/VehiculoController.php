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
            'nro_patente' => 'required|unique:vehiculos,nro_patente',
            'marca'       => 'required',
            'modelo'      => 'required',
            'precio'      => 'required|numeric',
            // Asegúrate de agregar aquí todos los campos que envías desde el modal
            'anio'        => 'required|integer',
            'kilometros'  => 'required|integer',
            'estado'      => 'required',
            'litros_combustible' => 'required|integer',
        ], [
            // Personalizamos el mensaje exacto en español para tu entrega
            'nro_patente.unique' => 'El número de patente ya se encuentra registrado!',
            'nro_patente.required' => 'La patente es campo obligatorio.',
        ]);

        try {
            Vehiculos::create($datos);
            session()->flash('success', 'Vehículo agregado correctamente.');
            return redirect()->back();

        } catch (\Exception $e) {
            return redirect()->back()
            ->withInput()
            ->withErrors(['error_vehiculo' => 'Error al guardar un Vehiculo: ' . $e->getMessage()]);
        }
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
        // Buscamos el auto por su patente (fuerza el ModelNotFoundException si no existe)
        $vehiculo = Vehiculos::where('nro_patente', $nro_patente)->firstOrFail();
        
        // Cambiamos el estado tal como lo pide tu contrato de operación
        $vehiculo->delete();
                
        return redirect()->back()->with('success', 'Vehículo eliminado.');
    }
}
