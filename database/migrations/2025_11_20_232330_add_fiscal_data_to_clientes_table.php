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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('tipo_persona')
                ->default('fisica')
                ->after('user_id');
            $table->string('rfc', 13)
                ->nullable()
                ->unique()
                ->after('tipo_persona');
            $table->string('razon_social')
                ->nullable()
                ->after('rfc');
            $table->string('constancia_fiscal')
                ->nullable()
                ->after('razon_social');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_persona',
                'rfc',
                'razon_social',
                'constancia_fiscal'
            ]);
        });
    }
};
