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
       Schema::table('pagos', function (Blueprint $table) {
            // 1. IMPORTANTE: Eliminar la llave foránea ANTES de eliminar la columna.
            // Laravel 10+ puede inferir el nombre de la restricción.
            $table->dropForeign(['venta_id']);

            // 2. Eliminar la columna antigua
            $table->dropColumn('venta_id');

            // 3. Añadir la nueva columna
            // Usamos 'after' para ponerla donde estaba la otra, es opcional pero ordenado.
            $table->foreignId('cliente_id')
                  ->after('validacion') // <-- Opcional: ponlo donde quieras
                  ->constrained('clientes')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // 1. Eliminar la nueva llave y columna
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');

            // 2. Volver a añadir la columna y llave antiguas
            $table->foreignId('venta_id')->constrained('ventas');
        });
    
    }
};
