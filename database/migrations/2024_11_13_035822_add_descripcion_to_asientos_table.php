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
        Schema::table('asientos', function (Blueprint $table) {
            $table->string('descripcion')->nullable()->after('haber'); // Agrega el campo 'descripcion'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asientos', function (Blueprint $table) {
            $table->dropColumn('descripcion'); // Elimina el campo 'descripcion' si se revierte la migración
        });
    }
};
