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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            //Llaves foraneas
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            //Campos de datos
            $table->string('nombreTitular_cuenta',100);
            $table->string('numero_cuenta',20);
            $table->string('nombreBanco_cuenta',50);
            $table->string('nit_cuenta',25)->nullable();
            $table->string('ci_cuenta',20)->nullable();
            $table->string('tipo_cuenta',15);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
