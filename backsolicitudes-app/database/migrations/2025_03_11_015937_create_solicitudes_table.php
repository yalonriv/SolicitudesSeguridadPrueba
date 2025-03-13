<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ejecuta la migración para crear la tabla 'solicitudes'.
     *
     * La tabla almacena las solicitudes realizadas por los candidatos.
     */
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained('candidatos')->onDelete('cascade');
            $table->foreignId('tipo_estudio_id')->constrained('tipos_estudio')->onDelete('cascade');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_solicitud');
            $table->date('fecha_completado')->nullable();
            $table->timestamps();

        });

         // Agregar la restricción CHECK manualmente
         DB::statement("ALTER TABLE solicitudes ADD CONSTRAINT chk_estado CHECK (estado IN ('pendiente', 'en_proceso', 'completada'))");
   
         
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
