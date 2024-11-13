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
        Schema::create('balance_apertura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade'); // Relación con empresa
            $table->date('fecha')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balance_apertura');
    }
};
