<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pedido;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resena>
 */
class ResenaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pedido_id'=> Pedido::inRandomOrder()->first()->id ?? Pedido::factory(),
            'descripcion_resena' => fake()->text(100),
            'calificacion_resena' => fake()->numberBetween(1, 5),
            'fecha_resena' => now(),
        ];
    }
}
