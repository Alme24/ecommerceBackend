<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * estado_pedido = 0 ; es pedido no guardado
     * estado_pedido = 1 ; es pedido en proceso
     * estado_pedido = 2 ; es pedido finalizado
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('carrito_id')->constrained('carritos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');

            $table->integer('unidad_pedido');
            $table->decimal('preciosub_pedido');
            $table->integer('estado_pedido')->nullable();
            $table->timestamp('fecha_pedido')->nullable();
            $table->timestamp('fechaFin_pedido')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
