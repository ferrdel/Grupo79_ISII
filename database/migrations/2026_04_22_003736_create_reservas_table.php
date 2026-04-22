<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->string('nro_reserva', 10)->primary();
            $table->date('fecha_inicio');
            $table->date('fecha_devolucion');
            $table->date('hra_inicio');
            $table->date('hra_devolucion');
            $table->decimal('precio_total', 10, 2);
            $table->string('estado');

            // Claves Foráneas (FK)
            $table->string('nro_patente', 10)->nullable(); // Nro Patente, nullable si aplica
    
            // Restricciones de Clave Foránea
            $table->foreign('nro_patente')->references('nro_patente')->on('vehiculos')->onDelete('set null');
                        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
