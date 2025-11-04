<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad_general', 10, 2);
            $table->date('fecha');
            $table->string('metodo_pago');
            $table->string('referencia')->nullable();
            $table->string('adjuntar_archivo')->nullable();
            $table->text('comentarios')->nullable();
            $table->boolean('validacion')->default(false);
            $table->foreignId('venta_id')->constrained('ventas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
