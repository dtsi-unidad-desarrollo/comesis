<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear servicios de prueba
        $servicios = [
            [
                'nombre' => 'ALMUERZO',
                'hora_inicio' => '11:30:00',
                'hora_cierre' => '18:30:00',
                'disponibilidad' => 2000,
                'estatus' => 1,
            ],
          
        ];

        foreach ($servicios as $data) {
            Servicio::updateOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}
