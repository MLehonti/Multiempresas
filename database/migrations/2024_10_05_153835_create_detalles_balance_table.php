<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detalles_balance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balance_id')->constrained('balance_apertura')->onDelete('cascade'); // Relación con balance de apertura
            $table->foreignId('cuenta_id')->constrained()->onDelete('cascade'); // Relación con cuentas
            $table->decimal('debe', 15, 2)->default(0); // Valor en el debe
            $table->decimal('haber', 15, 2)->default(0); // Valor en el haber
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_balance');
    }
};
