<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Typeuser;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'typeuser_id'=> Typeuser::inRandomOrder()->first()->id?? Typeuser::factory(),
            'nombre_user'=>fake()->firstName,
            'apellido_user'=>fake()->lastName,
            'email_user'=> fake()->unique()->safeEmail,
            'contrasena_user' => Hash::make('12345678'), 
        ];
    }
}
