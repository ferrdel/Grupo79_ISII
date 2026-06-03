<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromocionController extends Controller
{
    /**
     * Muestra el formulario de creación precargado dinámicamente
     */
    public function create(Request $request)
    {
        // Capturamos el mes crítico que nos manda el botón del Dashboard (ej: 5 para Mayo)
        $mesCritico = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));

        // Creamos las fechas por defecto para el primer y último día de ese mes
        $fechaInicio = Carbon::create($anio, $mesCritico, 1)->format('Y-m-d');
        $fechaFin = Carbon::create($anio, $mesCritico, 1)->endOfMonth()->format('Y-m-d');
        
        // Mapeamos el número de mes a un nombre para el título del formulario
        $mesesNombres = [1=>'Enero', 2=>'Febrero', 3=>'Marzo', 4=>'Abril', 5=>'Mayo', 6=>'Junio', 7=>'Julio', 8=>'Agosto', 9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre'];
        $nombreMes = $mesesNombres[$mesCritico] ?? 'Temporada Baja';

        return view('admin.promociones.create', [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'nombreMes' => $nombreMes,
            'descuentoSugerido' => 20 // -20% metodológico de tu prototipo
        ]);
    }

    /**
     * Procesa el formulario y persiste los datos en la base de datos
     */
    public function store(Request $request)
    {
        // Validamos estrictamente los datos que vienen del frontend
        $dataValida = $request->validate([
            'nombre_promo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'descuento'    => 'required|numeric|min:0|max:100',
        ]);

        // Persistimos en la tabla utilizando Eloquent ORM
        Promocion::create([
            'nombre_promo' => $dataValida['nombre_promo'],
            'fecha_inicio' => $dataValida['fecha_inicio'],
            'fecha_fin'    => $dataValida['fecha_fin'],
            'descuento'    => $dataValida['descuento'] / 100, // Lo guardamos como float (0.20)
            'gps_gratis'   => $request->has('gps_gratis'),
            'silla_bebe_descuento' => $request->has('silla_bebe_descuento'),
            'conductor_gratis' => $request->has('conductor_gratis'),
            'estado'       => 'Activo'
        ]);

        // Redireccionamos de vuelta al Dashboard con un mensaje de éxito para Bootstrap
        return redirect()->route('admin.dashboard')->with('exito', '¡Promoción activada e impulsada con éxito en el sistema!');
    }
}