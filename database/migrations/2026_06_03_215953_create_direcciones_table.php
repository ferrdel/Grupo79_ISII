<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id('id_direccion'); // PK
            $table->string('calle');
            $table->integer('altura');
            $table->string('departamento')->nullable();
            // FK vinculada a Localidades
            $table->foreignId('id_localidad')->constrained('localidades', 'id_localidad')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
