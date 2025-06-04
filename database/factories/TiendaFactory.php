<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tienda>
 */
class TiendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::inRandomOrder()->first()->id ?? Usuario::factory(),
            'nombre_tienda' => fake()->company,
            'descripcion_tienda' => fake()->paragraph,
            'telefono_tienda' => fake()->phoneNumber,
            'direccion_tienda' => fake()->streetAddress,
            'ubicacion_tienda' => fake()->address,
            'ciudad_tienda' => fake()->city,
            'provincia_tienda' => fake()->state,
            'lugarEntregas_tienda' => fake()->sentence(3),
            'logo_tienda' => Str::random(10).'.jpg',
            'banner_tienda' => Str::random(10).'.jpg',
            'calificacion_tienda' => fake()->randomFloat(1, 1, 5),
        ];
    }
}
