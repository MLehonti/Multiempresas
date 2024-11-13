<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plan_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('user_id');
            $table->date('fecha');
            $table->timestamps();

            // Clave foránea con la tabla empresas
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            // Clave foránea con la tabla usuarios
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_cuentas');
    }
};

