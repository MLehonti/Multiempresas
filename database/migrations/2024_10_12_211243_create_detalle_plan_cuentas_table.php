<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalle_plan_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_cuenta_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->timestamps();

            // Clave foránea con la tabla plan_cuentas
            $table->foreign('plan_cuenta_id')->references('id')->on('plan_cuentas')->onDelete('cascade');
            // Clave foránea con la tabla cuentas
            $table->foreign('cuenta_id')->references('id')->on('cuentas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_plan_cuentas');
    }
};
