<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            // 1. Clave Primaria (Manual y tipo String por la patente)
            $table->string('nro_patente', 10)->primary();

            // 2. Atributos del Vehículo
            $table->decimal('precio', 10, 2);
            $table->string('modelo');
            $table->year('anio');
            $table->string('marca');
            $table->string('imagen')->nullable();
            $table->integer('kilometros')->unsigned();
            $table->string('estado');
            $table->text('descripcion')->nullable();
            $table->integer('litros_combustible');

            // 3. Claves Foráneas (Sintaxis explícita para evitar Error 150)
            
            // Relación con Sucursales
            $table->unsignedBigInteger('id_sucursal')->nullable();
            $table->foreign('id_sucursal')
                  ->references('id_sucursal') // <--- Apunta a tu PK personalizada
                  ->on('sucursales')
                  ->onDelete('set null');
           

            // Agrega la línea para el borrado lógico
            $table->softDeletes();

            // 4. Auditoría
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};