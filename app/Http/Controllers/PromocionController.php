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
        
        $inicio = Carbon::parse($request->input('fecha_inicio'));
        $fin = Carbon::parse($request->input('fecha_fin'));

        // CONTROL CRONOLÓGICO
        if ($inicio->greaterThan($fin)) {
            // Guardamos el error de forma persistente para la próxima pantalla
            session()->flash('error_critico', 'La fecha inicio no puede ser mayor a la fecha fin');
            return redirect()->back()->withInput();
        }

        // CONTROL DE MES
        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            session()->flash('error_critico', 'Las fechas deben corresponder estrictamente al mismo mes y año.');
            return redirect()->back()->withInput();
        }

        // Validamos estrictamente los datos que vienen del frontend
        $request->validate([
            'nombre_promo' => ['required','string','max:255',
            // Valida que sea único, pero ignora los registros que ya tengan deleted_at (bajas lógicas)
            \Illuminate\Validation\Rule::unique('promociones')->whereNull('deleted_at')
        ],
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'descuento'    => 'required|numeric|min:0|max:100',
        ], [
            'fecha_fin.after_or_equal' => '¡Error! La fecha de fin no puede ser menor a la fecha de inicio.',
            ]);
        
                 

        try {
            // En tu método de creación, asegurate de no mapear el ID manualmente
            $datos = $request->except('id_promocion');
            
            // Si pasa la validación, procedés a crearla a través de la Fachada
            $this->promocionFacade->crearYActivar($datos);

            //  LA SOLUCIÓN QUIRÚRGICA: Forzamos el mensaje en la sesión flash global
            session()->flash('success', '¡Promoción creada con éxito!');

            // Redireccionamos de vuelta al Dashboard con un mensaje de éxito para Bootstrap
            return redirect()->route('admin.dashboard');

        } catch (\Exception $e) {

            // Volvemos atrás manteniendo los inputs llenos
            return redirect()->back()->withInput();
        }         
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
        $inicio = Carbon::parse($request->input('fecha_inicio'));
        $fin = Carbon::parse($request->input('fecha_fin'));

        // CONTROL CRONOLÓGICO
        if ($inicio->greaterThan($fin)) {
            // Guardamos el error de forma persistente para la próxima pantalla
            session()->flash('error_critico', 'La fecha inicio no puede ser mayor a la fecha fin');
            return redirect()->back()->withInput();
        }

        // CONTROL DE MES
        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            session()->flash('error_critico', 'Las fechas deben corresponder estrictamente al mismo mes y año.');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'descuento'    => 'required|numeric|min:0|max:100',
        ]);

        try {
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

            // En tu función update(), reemplazá el return por estas dos líneas:
            session()->flash('success', '¡Promoción modificada con éxito!');

            return redirect()->route('admin.dashboard');

        } catch (\Exception $e) {
            // Si algo falla, vuelve al formulario de edición mostrando el error arriba
            return redirect()->back()
                ->withInput()
                ->withErrors(['error_promocion' => $e->getMessage()]);
        }

        
    }
  

    public function EliminarPromocion($id_promocion)
    {
        try {
            $this->promocionFacade->eliminarPromocion($id_promocion);

            // 3. Forzamos el mensaje de éxito en la sesión flash global para el Dashboard
            session()->flash('success', 'Promoción Eliminada.');
                
            return redirect()->route('admin.dashboard');
        
        } catch (\Exception $e) {
            // Si algo falla, volvemos atrás mostrando el motivo técnico
            return redirect()->back()->withErrors(['error_promocion' => $e->getMessage()]);
        }
        
    }

}