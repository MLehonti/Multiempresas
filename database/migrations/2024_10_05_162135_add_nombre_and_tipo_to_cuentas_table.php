<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Verificar si las columnas 'nombre' y 'tipo' existen antes de agregarlas
            if (!Schema::hasColumn('cuentas', 'nombre')) {
                $table->string('nombre')->after('id');
            }

            if (!Schema::hasColumn('cuentas', 'tipo')) {
                $table->enum('tipo', ['activo_corriente', 'activo_no_corriente', 'pasivo_corriente', 'pasivo_no_corriente', 'patrimonio'])->after('nombre');
            }
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Verificar si las columnas 'nombre' y 'tipo' existen antes de eliminarlas
            if (Schema::hasColumn('cuentas', 'nombre')) {
                $table->dropColumn('nombre');
            }

            if (Schema::hasColumn('cuentas', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
};
