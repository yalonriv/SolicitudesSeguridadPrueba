<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Ejecuta la migración para renombrar la tabla 'tipo_estudio' a 'tipos_estudio'.
     *
     */
    public function up(): void
    {
        Schema::rename('tipo_estudio', 'tipos_estudio');
    }

    public function down(): void
    {
        Schema::rename('tipos_estudio', 'tipo_estudio');
    }
};

