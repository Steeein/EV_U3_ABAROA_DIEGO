<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla proyectos. Campos definidos en la Evaluacion U2.
     * Todos NOT NULL porque la Evaluacion U3 exige que todos los campos
     * sean requeridos y no esten vacios.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->date('fecha_inicio');
            $table->string('estado', 50);
            $table->string('responsable', 255);
            $table->decimal('monto', 12, 2);

            // Usuario que creo el proyecto (relacion con la tabla usuarios).
            $table->foreignId('created_by')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Indices para optimizar los filtros de busqueda (Apunte 3, punto 4).
            $table->index('estado');
            $table->index('responsable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
