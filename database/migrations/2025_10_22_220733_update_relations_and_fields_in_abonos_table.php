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
        Schema::table('abonos', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->dropColumn('departamento_id');
            $table->foreignId('venta_id')
                ->after('monto') // <-- Opcional: ponlo donde quieras
                ->constrained('ventas')
                ->cascadeOnDelete();
            $table->date('fecha_abono')->nullable()->after('venta_id');
            $table->text('comentarios')->nullable()->after('fecha_abono');
            $table->foreignId('user_id')
                ->after('venta_id') // <-- Opcional
                ->nullable() // Permitir nulos (abonos antiguos o de sistema)
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abonos', function (Blueprint $table) {
            $table->dropColumn('comentarios');
            $table->dropColumn('fecha_abono');

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['venta_id']);
            $table->dropColumn('venta_id');
            $table->foreignId('departamento_id')->constrained('departamentos');
        });
    }
};
