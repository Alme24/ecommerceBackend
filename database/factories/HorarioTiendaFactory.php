<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tienda;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HorarioTienda>
 */
class HorarioTiendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        return [
            'tienda_id' => Tienda::inRandomOrder()->first()->id ?? Tienda::factory(),
            'dia_semana' => $this->faker->randomElement($dias),
            'hora_apertura' => $this->faker->time('H:i'),
            'hora_cierre' => $this->faker->time('H:i'),
            'cerrado' => $this->faker->boolean(20),
        ];
    }
}
