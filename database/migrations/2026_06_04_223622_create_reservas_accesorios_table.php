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
        Schema::create('reservas_accesorios', function (Blueprint $table) {
            $table->foreignId('nro_reserva')->constrained('reservas', 'nro_reserva')->onDelete('cascade');
            $table->foreignId('id_accesorio')->constrained('accesorios', 'id_accesorio')->onDelete('cascade');
            
            // Atributos específicos agregados en tu nuevo DER:
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_applied', 10, 2); 

            // PK compuesta para evitar duplicados idénticos en la misma reserva
            $table->primary(['nro_reserva', 'id_accesorio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_accesorios');
    }
};
