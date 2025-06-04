<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeuserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Typeuser::factory()->create([
            'nombre_type' => 'Administrador',
        ]);
        \App\Models\Typeuser::factory()->create([
            'nombre_type'=> 'Comprador',
        ]);
        \App\Models\Typeuser::factory()->create([
            'nombre_type'=> 'Vendedor',
        ]);
    }
    
}
