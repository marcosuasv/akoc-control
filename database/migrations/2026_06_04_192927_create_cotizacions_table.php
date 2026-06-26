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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // vendedor que cotiza
        
            $table->decimal('precio_departamento', 12, 2);
            $table->decimal('porcentaje_enganche', 5, 2)->default(20.00);
            $table->decimal('monto_enganche', 12, 2);
            $table->integer('numero_pagos');
            $table->string('frecuencia_pagos')->default('mensual'); // mensual, trimestral, etc.
            $table->decimal('monto_pago_periodico', 12, 2);
            $table->decimal('intereses_porcentaje', 5, 2)->default(0.00);
            $table->text('notas')->nullable();
            
            $table->string('estatus')->default('borrador'); // borrador, enviada, aceptada, vencida
            $table->date('fecha_vencimiento');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
