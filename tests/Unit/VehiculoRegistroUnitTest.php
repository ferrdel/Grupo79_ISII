<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase; // Usamos el TestCase puro de PHPUnit (aislado, no toca la base de datos)
use App\Models\Vehiculos;

class VehiculoRegistroUnitTest extends TestCase
{

    /**
     * PRUEBA UNITARIA 1 (Asociada a CP_Ag1):
     * Verifica que un vehículo con todos los campos vacíos falle la validación interna de negocio.
     */
    public function test_vehiculo_con_campos_vacios_no_es_valido_para_registro()
    {
        // 1. Datos de entrada (Arrange): Instanciamos un objeto con atributos vacíos según el CP_Ag1
        $vehiculoVacio = new Vehiculos([
            'nro_patente'        => '',
            'marca'              => '',
            'modelo'             => '',
            'anio'               => null,
            'precio'             => null,
            'estado'             => ''
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
        // 1. Datos de entrada (Arrange): Cargamos el Peugeot 206 idéntico a tu caso CP_Ag3
        $vehiculoValido = new Vehiculos([
            'nro_patente'        => 'EEE333',
            'marca'              => 'Peugeot',
            'modelo'             => '206',
            'anio'               => 2010,
            'precio'             => 56000.00,
            'estado'             => 'disponible'
        ]);

        // 2. Ejecución (Act)
        $resultadoObtenido = $vehiculoValido->esValidoParaRegistro();

        // 3. Verificación (Assert): Debe devolver TRUE porque cumple con todas las reglas obligatorias
        $this->assertTrue($resultadoObtenido);
    }

    /**
     * PRUEBA UNITARIA 3 (Asociada a CP_Ag5):
     * Verifica que el sistema rechace el registro si la patente ingresada ya existe en el listado del sistema.
     */
    public function test_vehiculo_con_patente_existente_no_es_valido_para_registro()
    {
        // 1. Datos de entrada (Arrange): Simulamos un listado de patentes que YA están registradas en CorrientesRent
        $patentesExistentes = ['EEE333', 'AAA111', 'BBB222'];

        // Instanciamos el Peugeot 206 de tu caso CP_Ag5 con la patente duplicada 'EEE333'
        $vehiculoDuplicado = new Vehiculos([
            'nro_patente'        => 'EEE333', // <-- Patente repetida
            'marca'              => 'Peugeot',
            'modelo'             => '206',
            'anio'               => 2010,
            'precio'             => 56000.00,
            'estado'             => 'disponible'
        ]);

        // 2. Ejecución (Act): Le pasamos el listado de exclusión al método validador
        $resultadoObtenido = $vehiculoDuplicado->esValidoParaRegistro($patentesExistentes);

        // 3. Verificación (Assert): Debe devolver FALSE porque 'EEE333' ya existe en el arreglo
        $this->assertFalse($resultadoObtenido);
    }
}