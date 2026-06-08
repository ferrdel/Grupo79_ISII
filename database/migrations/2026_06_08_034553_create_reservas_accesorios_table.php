<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservas_accesorios')) {
            Schema::create('reservas_accesorios', function (Blueprint $table) {
                // Si usas claves foráneas compuestas o simples según tu DER:
                $table->foreignId('nro_reserva')->constrained('reservas', 'nro_reserva')->onDelete('cascade');
                $table->foreignId('id_accesorio')->constrained('accesorios', 'id_accesorio')->onDelete('cascade');
                $table->integer('cantidad');
                $table->decimal('precio_applied', 10, 2);
                $table->timestamps();
                
                // Definición de clave primaria compuesta si corresponde a tu modelo
                $table->primary(['nro_reserva', 'id_accesorio']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_accesorios');
    }
};
