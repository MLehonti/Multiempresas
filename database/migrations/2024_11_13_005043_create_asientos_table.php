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
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('cuenta_origen_id')->constrained('cuentas')->onDelete('cascade');
            $table->foreignId('cuenta_destino_id')->constrained('cuentas')->onDelete('cascade');
            $table->decimal('monto', 15, 2);
            $table->date('fecha');
            $table->decimal('debe', 15, 2)->nullable();
            $table->decimal('haber', 15, 2)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
