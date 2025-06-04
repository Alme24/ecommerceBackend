<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Carrito;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'producto_id' => Producto::inRandomOrder()->first()->id ??Producto::factory(), 
            'carrito_id' => Carrito::inRandomOrder()->first()->id ??Carrito::factory(),  
            'user_id' => Usuario::inRandomOrder()->first()->id ??Usuario::factory(),     
            'unidad_pedido' => fake()->numberBetween(1, 5),
            'preciosub_pedido' => fake()->randomFloat(2, 10, 500),
            'estado_pedido' => fake()->numberBetween(0, 2),
            'fecha_pedido' => now(),
            'fechaFin_pedido' => now(),
        ];
    }
}
