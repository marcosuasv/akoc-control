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
       Schema::table('abonos', function (Blueprint $table) {
            $table->foreignId('plan_pago_id')
                  ->nullable()
                  ->constrained('plan_pagos')
                  ->after('pago_id');

            // Asegúrate que el nombre 'abonos_venta_id_foreign' sea correcto
            $table->dropForeign(['venta_id']); 
            $table->dropColumn('venta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('abonos', function (Blueprint $table) {
            $table->foreignId('venta_id')
                  ->nullable()
                  ->constrained('ventas')
                  ->after('pago_id');

            $table->dropForeign(['plan_pago_id']);
            $table->dropColumn('plan_pago_id');
        });
    }
};
