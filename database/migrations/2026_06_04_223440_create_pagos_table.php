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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago'); // PK
            $table->date('fecha_pago');
            $table->decimal('monto_pago', 10, 2);
            $table->string('metodo_pago');
            $table->string('estado');
            // FK vinculada a Reservas
            $table->foreignId('nro_reserva')->constrained('reservas', 'nro_reserva')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
