<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComensaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'nacionalidad' => $this->faker->randomElement(['V', 'E']),
            'cedula' => $this->faker->unique()->numerify('########'),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'tipo_comensal' => $this->faker->randomElement(['EMPLEADO', 'ESTUDIANTE']),
            'sub_tipo' => $this->faker->randomElement(['SUB_TIPO_1', 'SUB_TIPO_2', 'SUB_TIPO_3']),
        ];
    }
}
