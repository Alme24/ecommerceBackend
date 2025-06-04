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
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('nombre_tienda',40);
            $table->string('descripcion_tienda',500);
            $table->string('telefono_tienda',40);
            $table->string('direccion_tienda',50);
            $table->string('ubicacion_tienda');
            $table->string('ciudad_tienda',40);
            $table->string('provincia_tienda',40);
            $table->string('lugarEntregas_tienda',50);
            $table->string('logo_tienda');
            $table->string('banner_tienda');
            $table->integer('calificacion_tienda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiendas');
    }
};
