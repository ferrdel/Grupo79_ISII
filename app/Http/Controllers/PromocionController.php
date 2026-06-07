<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Facades\PromocionFacade;
use App\Models\Promociones;

class PromocionController extends Controller
{

    protected $promocionFacade;

    public function __construct()
    {
        $this->promocionFacade = new PromocionFacade();
    }
    

    /** 
     * Muestra el formulario de creación precargado dinámicamente
     */
    public function RegistrarPromocion(Request $request)
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
        $request->validate([
            'nombre_promo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'descuento'    => 'required|numeric|min:0|max:100',
        ]);

        // Uso de la fachada: Una sola línea limpia que orquesta todo el subsistema por detrás
        $this->promocionFacade->crearYActivar([
            'nombre_promo' => $request->nombre_promo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'descuento'    => $request->descuento / 100,
            'estado'       => 'Activo'
        ]);

        // Redireccionamos de vuelta al Dashboard con un mensaje de éxito para Bootstrap
        return redirect()->route('admin.dashboard')->with('exito', '¡Promoción creada y auditada con éxito!');  
    }

    /**
     * Muestra el formulario de edición con los datos actuales cargados
     */
    public function edit($id)
    {
        $promocion = Promociones::findOrFail($id);
        
        // Mapeamos el nombre del mes para el título visual
        $mesesNombres = [1=>'Enero', 2=>'Febrero', 3=>'Marzo', 4=>'Abril', 5=>'Mayo', 6=>'Junio', 7=>'Julio', 8=>'Agosto', 9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre'];
        $numMes = date('n', strtotime($promocion->fecha_inicio));
        $nombreMes = $mesesNombres[$numMes] ?? 'Temporada Baja';

        return view('admin.promociones.edit', [
            'promocion' => $promocion,
            'nombreMes' => $nombreMes,
            'descuentoActual' => $promocion->descuento * 100 // Convertimos el float (0.20) a entero (20)
        ]);
    }

    /**
     * Procesa la actualización de la promoción
     */
    public function ModificarPromocion(Request $request, $id)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'descuento'    => 'required|numeric|min:0|max:100',
        ]);

        $promocion = Promociones::findOrFail($id);
        
        // Actualizamos usando asignación masiva
        $promocion->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'descuento'    => $request->descuento / 100, // Volvemos a guardar como float (0.20)
            'gps_gratis'   => $request->has('gps_gratis'),
            'silla_bebe_descuento' => $request->has('silla_bebe_descuento'),
            'conductor_gratis' => $request->has('conductor_gratis'),
        ]);

        return redirect()->route('admin.dashboard')->with('exito', '¡Promoción modificada con éxito!');
    }
  

    public function EliminarPromocion($id_promocion)
    {
        $this->promocionFacade->eliminarPromocion($id_promocion);
                
        return redirect()->route('admin.dashboard')->with('exito', 'Promoción dada de baja.');
    }

}