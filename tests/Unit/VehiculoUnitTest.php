<?php

namespace Tests\Unit;

use Tests\TestCase; // Usamos el TestCase puro de PHPUnit (aislado, no toca la base de datos)
use App\Models\Vehiculos;

use App\Http\Controllers\VehiculoController; // Asegurate de que este sea el namespace real de tu controlador
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehiculoUnitTest extends TestCase
{
    use RefreshDatabase; // Esto es para asegurarnos de que cada prueba tenga una base de datos limpia (si decides usar la base de datos en algún momento)

    /**
     * PRUEBA UNITARIA 1 (Asociada a CP_Ag1):
     * Verifica que un vehículo con todos los campos vacíos falle la validación interna de negocio.
     */
    public function test_vehiculo_con_campos_vacios_no_es_valido_para_registro()
    {
        // 1. Datos de entrada: Instanciamos un objeto con atributos vacíos según el CP_Ag1
        $vehiculoVacio = new Vehiculos([
            'nro_patente'        => '',
            'marca'              => '',
            'modelo'             => '',
            'precio'             => null,
            'anio'               => null,
            'kilometros'         => null,
            'estado'             => '',
            'litros_combustible' => null
        ]);


        // 2. Ejecución del método lógico a probar (Act)
        $resultadoObtenido = $vehiculoVacio->esValidoParaRegistro();

        // 3. Verificación (Assert): Esperamos que devuelva FALSE porque faltan los campos requeridos
        $this->assertFalse($resultadoObtenido);
    }

    /**
     * PRUEBA UNITARIA 2 (Asociada a CP_Ag3):
     * Verifica que un vehículo con todos los datos requeridos completos y válidos pase la validación de negocio.
     */
    public function test_vehiculo_con_datos_completos_es_valido_para_registro()
    {
        // 1. Datos de entrada 
        $vehiculoValido = new Vehiculos([
            'nro_patente'        => 'EEE333',
            'marca'              => 'Peugeot',
            'modelo'             => '206',
            'precio'             => 56000.00,
            'anio'               => 2010,
            'kilometros'         => 120000,
            'estado'             => 'disponible',
            'litros_combustible' => 50
        ]);


        // 2. Ejecución (Act)
        $resultadoObtenido = $vehiculoValido->esValidoParaRegistro();

        // 3. Verificación (Assert): Debe devolver TRUE porque cumple con todas las reglas obligatorias
        $this->assertTrue($resultadoObtenido);
    }

    /**
     * PRUEBA UNITARIA 3 (Asociada a CP_Ag5):
     * Verifica que el sistema rechaza el registro si la patente ingresada ya existe en el listado del sistema.
     */
    public function test_vehiculo_con_patente_existente_no_es_valido_para_registro()
    {
        // 1. Datos de entrada: Simulamos un listado de patentes que YA están registrada
        $patentesExistentes = ['EEE333', 'AAA111', 'BBB222'];

        // Instanciamos el Peugeot 206 de tu caso CP_Ag5 con la patente duplicada 'EEE333'
        $vehiculoDuplicado = new Vehiculos([
            'nro_patente'        => 'EEE333', // <-- Patente repetida
            'marca'              => 'Peugeot',
            'modelo'             => '206',
            'precio'             => 56000.00,
            'anio'               => 2010,
            'kilometros'         => 120000,
            'estado'             => 'disponible',
            'litros_combustible' => 50
        ]);
        
        
        // 2. Ejecución (Act): Le pasamos el listado de exclusión al método validador
        $resultadoObtenido = $vehiculoDuplicado->esValidoParaRegistro($patentesExistentes);

        // 3. Verificación (Assert): Debe devolver FALSE porque 'EEE333' ya existe en el arreglo
        $this->assertFalse($resultadoObtenido);
    }

    
    /** PRUEBAS UNITARIAS DAR DE BAJA VEHÍCULO*/
    public function unidad_se_puede_dar_de_baja_un_vehiculo_existente()
    {
        
        $vehiculo = Vehiculos::create([
            'nro_patente' => 'ABC321',
            'marca'       => 'Fiat',
            'modelo'      => 'Punto',
            'precio'  => 30000,
            'anio'        => 2015,
            'kilometros'  => 80000,
            'estado'      => 'Disponible',
            'litros_combustible' => 40
        ]);


        // Instanciamos el controlador directamente de forma unitaria
        $controlador = new VehiculoController();

        //Llamamos directamente a la función del controlador encargada de la eliminación
        $controlador->EliminarVehiculo($vehiculo->nro_patente);

        // El vehículo cambia a estado  "inactivo")
        $this->assertSoftDeleted('vehiculos', [
            'nro_patente' => 'ABC321'
        ]);
    }

    /** @test */
    public function unidad_lanzar_excepcion_si_se_intenta_dar_de_baja_un_vehiculo_inexistente()
    {
        // Excepción: El vehículo no existe
        $patenteInexistente = 'XYZ999';
        $controlador = new VehiculoController();

        $this->expectException(ModelNotFoundException::class);

        // acción que va a forzar el fallo
        $controlador->EliminarVehiculo($patenteInexistente);
    }


    /** PRUEBAS UNITARIAS: MODIFICAR DATOS DEL VEHÍCULO */
    public function unidad_se_pueden_modificar_los_datos_de_un_vehiculo_existente()
    {
        //El vehículo existe originalmente)
        $vehiculo = Vehiculos::create([
            'nro_patente' => 'DEF456',
            'marca'       => 'Fiat',
            'modelo'      => 'Cronos',
            'precio'  => 25000,
            'anio'        => 2022,
            'kilometros'  => 20000,
            'estado'      => 'Disponible',
            'litros_combustible' => 45
        ]);


        // Simulamos el objeto Request de Laravel que llega desde el formulario con los nuevos datos
        $request = new Request([
            'marca'      => 'Toyota',
            'modelo'     => 'Corolla',
            'precio' => 45000,
            'anio'       => 2024,
            'kilometros' => 15000,
            'estado'     => 'Disponible',
            'litros_combustible' => 50
        ]);

        $controlador = new VehiculoController();

        // Invocamos directo al método del controlador pasándole el Request y el ID)
        $controlador->ActualizarVehiculo($request, $vehiculo->nro_patente);

        //Postcondición: Los datos se actualizaron correctamente en el sistema
        $this->assertDatabaseHas('vehiculos', [
            'nro_patente' => 'DEF456',
            'marca'       => 'Toyota',
            'modelo'      => 'Corolla',
            'precio'  => 45000,
            'anio'        => 2024
        ]);
    }

    /** @test */
    public function unidad_lanzar_excepcion_si_se_intenta_modificar_un_vehiculo_inexistente()
    {
        
        $patenteInexistente = 'ERR777';
        $request = new Request(['marca' => 'Ford']);
        $controlador = new VehiculoController();

        // Esperamos que falle con la excepción al no encontrar la patente
        $this->expectException(ModelNotFoundException::class);

        $controlador->ActualizarVehiculo($request, $patenteInexistente);
    }

}