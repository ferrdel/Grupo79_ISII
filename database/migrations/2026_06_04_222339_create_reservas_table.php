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
            $table->id('nro_reserva'); // PK autoincremental
            $table->date('fecha_inicio');
            $table->time('hora_inicio');
            $table->date('fecha_devolucion');
            $table->time('hora_devolucion');
            $table->decimal('precio_total', 10, 2);
            $table->string('estado');

            // FKs obligatorias de tu gráfico:
            $table->foreignId('dni_cliente')->constrained('clientes', 'dni_cliente');
            $table->string('nro_patente'); // Al ser string en tu DER, se referencia de forma manual
            $table->foreign('nro_patente')->references('nro_patente')->on('vehiculos');
            
            // FK opcional (Nullable) si no se aplica promoción
            $table->foreignId('id_promocion')->nullable()->constrained('promociones', 'id_promocion');
            
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
