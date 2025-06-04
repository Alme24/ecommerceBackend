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
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            
            $table->string('descripcion_resena',100);
            $table->integer('calificacion_resena');
            $table->timestamp('fecha_resena');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
