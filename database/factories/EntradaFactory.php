<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntradaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'nacionalidad' => 'V',
            'cedula' => $this->faker->unique()->numberBetween(1000000, 99999999),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'comida' => $this->faker->randomElement(['ALMUERZO']),
            'fecha' => Carbon::now()->format('Y-m-d'),
            'hora' => Carbon::now()->format('H:i:s'),
            'codigo_carrera' => $this->faker->randomElement([011, 010, 012, 013]),
            'carrera' => $this->faker->jobTitle,
            'codigo_sede' => $this->faker->randomElement([1, 2]),
            'sede' => 'BARINAS',
            'tipo_sede' => 'PRINCIPAL',
            'estado' =>'BARINAS',
            'municipio' => 'BARINAS',
            'direccion' => 'BARINAS',
            'tipo_comensal' => $this->faker->randomElement(['ESTUDIANTE', 'EMPLEADO']),
        ];
    }
}
