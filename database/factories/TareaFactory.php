<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Titulo' => 'Tarea ' . $this->faker->numberBetween(1, 10),
            'Descripcion' => 'Descripcion ' . $this->faker->sentence(),
            'Completada' => $this->faker->boolean,
            'user_id' => 1,
        ];
    }
}
