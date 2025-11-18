<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TypeuserSeeder;
use Database\Seeders\UsurioSeeder;
use Database\Seeders\CuentaSeeder;
use Database\Seeders\TiendaSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\ProductoSeeder;
use Database\Seeders\EtiquetaSeeder;
use Database\Seeders\CarritoSeeder;
use Database\Seeders\PedidoSeeder;
use Database\Seeders\ResenaSeeder;
use Database\Seeders\HorarioTiendaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            TypeuserSeeder::class,
            UsuarioSeeder::class,
            CuentaSeeder::class,
            TiendaSeeder::class,
            CategoriaSeeder::class,
        ]);
        $tiendas = \App\Models\Tienda::all();
        $categorias = \App\Models\Categoria::all();

        foreach ($tiendas as $tienda) {
            $tienda->categorias()->attach(
                $categorias->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
        $this->call([
            ProductoSeeder::class,
            EtiquetaSeeder::class,
        ]);

        $productos = \App\Models\Producto::all();
        $etiquetas = \App\Models\Etiqueta::all();

        foreach($productos as $producto){
            $producto->etiquetas()->attach(
                $etiquetas->random(rand(1,min(5,10)))->pluck('id')->toArray()
            );
        }

        $this->call([
            CarritoSeeder::class,
            PedidoSeeder::class,
            ResenaSeeder::class,
            HorarioTiendaSeeder::class,
        ]);
    }
}
