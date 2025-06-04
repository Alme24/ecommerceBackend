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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('tienda_id')->constrained('tiendas')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias');
            
            $table->string('nombre_producto',100);
            $table->string('descripcion_producto',200)->nullable();
            $table->string('tamano_producto')->nullable();
            $table->string('peso_producto')->nullable();
            $table->decimal('precio_producto',10,2);
            $table->string('color_producto')->nullable();
            $table->integer('cantDisp_producto');
            $table->decimal('descuento_producto',5,2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
