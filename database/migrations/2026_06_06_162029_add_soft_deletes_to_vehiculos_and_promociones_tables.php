<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Añadimos a la tabla promociones
        Schema::table('promociones', function (Blueprint $table) {
            $table->softDeletes();
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