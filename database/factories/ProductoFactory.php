<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\Categoria;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tienda_id' => Tienda::inRandomOrder()->first()->id ?? Tienda::factory(),
            'categoria_id' => Categoria::inRandomOrder()->first()->id ?? Categoria::factory(),
            'nombre_producto' => fake()->words(2, true),
            'descripcion_producto' => fake()->text(200),
            'tamano_producto' => fake()->randomElement(['Pequeño', 'Mediano', 'Grande']),
            'peso_producto' => fake()->randomFloat(2, 0.1, 10), // kg
            'precio_producto' => fake()->randomFloat(2, 5, 500),
            'color_producto' => fake()->safeColorName(),
            'cantDisp_producto' => fake()->numberBetween(1, 100),
            'descuento_producto' => fake()->numberBetween(0, 30),
        ];
    }
}
