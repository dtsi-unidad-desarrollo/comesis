<?php

namespace Database\Seeders;

use App\Models\Torniquete;
use Illuminate\Database\Seeder;

class TorniqueteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $torniquetes = [
            [
                'nombre' => 'TORNIQUETE A',
                'endpoint_url' => 'http://example.com/torniquete1',
                'tipo' => 'A',
                'estatus' => 1,
                'descripcion' => 'Torniquete para barinas 1'
            ],
            [
                'nombre' => 'TORNIQUETE B',
                'endpoint_url' => 'http://example.com/torniquete2',
                'tipo' => 'B',
                'estatus' => 1,
                'descripcion' => 'Torniquete para barinas 2'
            ],
        ];

        foreach ($torniquetes as $torniquete) {
            Torniquete::create($torniquete);
        }
    }
}
