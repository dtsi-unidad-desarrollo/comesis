<?php

namespace Database\Seeders;

use App\Models\Atm;
use Illuminate\Database\Seeder;

class AtmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $atms = [
            ['nombre' => 'ATM BARINAS 1', 'torniquete_id' => 1],
            ['nombre' => 'ATM BARINAS 2', 'torniquete_id' => 2],
        ];

        foreach ($atms as $atm) {
          Atm::create($atm);
        }
    }
}
