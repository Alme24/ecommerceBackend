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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
    
            //Llaves foráneas
            $table->foreignId('typeuser_id')->constrained('typeusers')->onDelete('cascade');
    
            // Campos de datos
            $table->string('nombre_user',40);
            $table->string('apellido_user',50);
            $table->string('email_user'); 
            $table->string('contrasena_user');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
