<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Crear la tabla de depreciaciones
        Schema::create('depreciaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');  // Nombre de la depreciación (puede ser algo como "Depreciación por maquinaria", etc.)
            $table->decimal('porcentaje_depreciacion', 5, 2);  // Porcentaje de depreciación (por ejemplo, 10.00 para un 10%)
            $table->text('descripcion')->nullable();  // Descripción opcional de la depreciación
            $table->timestamps();
        });

        // Tabla intermedia para la relación muchos a muchos
        Schema::create('cuenta_depreciacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id')->constrained('cuentas')->onDelete('cascade');  // Relación con la tabla 'cuentas'
            $table->foreignId('depreciacion_id')->constrained('depreciaciones')->onDelete('cascade');  // Relación con la tabla 'depreciaciones'
            $table->timestamps();
        });
    }

    public function down()
    {
        // Eliminar las tablas si es necesario revertir la migración
        Schema::dropIfExists('cuenta_depreciacion');
        Schema::dropIfExists('depreciaciones');
    }
};
