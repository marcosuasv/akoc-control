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
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();

            $table->integer('numero_pago')->comment('Ej: 1, 2, 3... para el pago 1 de 24');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento');
            
            $table->enum('status', ['pendiente', 'pagado', 'vencido'])->default('pendiente');
            
            $table->date('fecha_pago')->nullable()->comment('La fecha real en que se liquidó el pago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_pagos');
    }
};
