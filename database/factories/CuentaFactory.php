<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuenta>
 */
class CuentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => Usuario::inRandomOrder()->first()->id ?? Usuario::factory(),
            'nombreTitular_cuenta'=>fake()-> name,
            'numero_cuenta' => fake()->bankAccountNumber,
            'nombreBanco_cuenta'=> fake()->randomElement(['Banco Union','Banco BCP','Banco Ganadero','Banco Facil']),
            'nit_cuenta' => fake()->numerify('########'),
            'ci_cuenta' => fake()->numerify('########'),
            'tipo_cuenta'=> fake()->randomElement(['Ahorro','Corriente']),
        ];
    }
}
