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
        Schema::create('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('dni_cliente')->primary(); // PK (DNI como identificador único)
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('nro_licencia');
            // FK vinculada a Direcciones
            $table->foreignId('id_direccion')->constrained('direcciones', 'id_direccion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
