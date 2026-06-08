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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id('id_promocion');
            $table->string('nombre_promo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('descuento', 5, 2);
            $table->string('estado');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('promociones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
