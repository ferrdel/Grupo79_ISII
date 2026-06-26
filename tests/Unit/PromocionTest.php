<?php

use Tests\TestCase;

use App\Facades\PromocionFacade;
use App\Models\Promociones;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromocionTest extends TestCase
{
    // Este Trait limpia la base de datos de XAMPP/Pruebas en cada ejecución para que no se mezclen los datos
    use RefreshDatabase; 

    protected $promocionFacade;

    protected function setUp(): void
    {
        parent::setUp();
        // Instanciamos tu fachada antes de cada caso de prueba
        $this->promocionFacade = app(PromocionFacade::class);
    }

    /**
     * CASO 1: Registro normal (Camino Feliz)
     */
    public function test_registro_normal_de_promocion_valida()
    {

        $datos = [
            'nombre_promo' => 'Impulso Noviembre - 2026',
            'fecha_inicio' => '2026-11-01',
            'fecha_fin'    => '2026-11-30',
            'descuento'    => 20,
        ];

        // Invocamos el método crítico de tu Fachada
        $resultado = $this->promocionFacade->crearYActivar($datos);

        // Afirmar (Assert): Verificamos los cambios de estado 
        $this->assertInstanceOf(Promociones::class, $resultado);
        $this->assertEquals('Activa', $resultado->estado); // Valida la inicialización

        // Verificamos que impactó físicamente en la base de datos
        $this->assertDatabaseHas('promociones', [
            'nombre_promo' => 'Impulso Noviembre - 2026',
            'fecha_inicio' => '2026-11-01',
            'estado'       => 'Activa'
        ]);
    }

    /**
     * CASO 2: Registro con fecha de inicio mayor a fecha de fin
     */
    public function test_registro_falla_si_fecha_inicio_es_mayor_a_fecha_fin()
    {
        // Arreglar: Ponemos las fechas al revés de forma intencional
        $datosErroneos = [
            'nombre_promo' => 'Promo Inversa',
            'fecha_inicio' => '2026-11-20',
            'fecha_fin'    => '2026-11-10', // Menor al inicio
            'descuento'    => 15,
        ];

        $this->expectException(\Exception::class);

        $this->expectExceptionMessage('La fecha inicio no puede ser mayor a la fecha fin');

        $this->promocionFacade->crearYActivar($datosErroneos);
    }

    /**
     * CASO 3: Registro con una promoción activa ya registrada en el mismo mes (Mes Duplicado)
     */
    public function test_registro_falla_si_ya_existe_promocion_activa_en_el_mismo_mes()
    {
        // Arreglar: Creamos físicamente una promoción "Activa" en Noviembre usando el Factory o directamente en la BD
        Promociones::create([
            'nombre_promo' => 'Primera Promo Existente',
            'fecha_inicio' => '2026-11-01',
            'fecha_fin'    => '2026-11-15',
            'descuento'    => 20,
            'estado'       => 'Activa',
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Preparamos una segunda promoción que intenta colarse en el mismo mes (Noviembre)
        $segundaPromoMismoMes = [
            'nombre_promo' => 'Intento Duplicado Noviembre',
            'fecha_inicio' => '2026-11-16',
            'fecha_fin'    => '2026-11-30',
            'descuento'    => 25,
        ];

        // Afirmar: Esperamos explícitamente el bloqueo por Regla de Negocio
        $this->expectException(\Exception::class);
        // Si tu fachada tira el mensaje "¡Control de Regla de Negocio: Ya existe una promoción activa...", podés verificar el texto exacto así:
        $this->expectExceptionMessage('¡Ya existe una promoción para este mes !');

        // Actuar: Intentamos forzar la inserción de la segunda promoción
        $this->promocionFacade->crearYActivar($segundaPromoMismoMes);
    }
}
