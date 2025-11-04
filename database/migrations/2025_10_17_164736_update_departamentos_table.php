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
        Schema::table('departamentos', function (Blueprint $table) {
            $table->foreignId('desarrollo_id')->constrained('desarrollos')->after('id');
            $table->decimal('m2_construccion', 8, 2)->after('precio');
            $table->decimal('m2_terraza', 8, 2)->default(0)->after('m2_construccion');
            $table->integer('recamaras')->after('m2_terraza');
            $table->integer('banos')->after('recamaras');
            $table->integer('estacionamientos')->after('banos');
            $table->string('estatus')->default('disponible')->after('estacionamientos'); 
            $table->json('galeria')->nullable()->after('estatus'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            //
        });
    }
};
