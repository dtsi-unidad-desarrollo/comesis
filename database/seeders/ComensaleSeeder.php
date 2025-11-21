<?php

namespace Database\Seeders;

use App\Models\Comensale;
use Illuminate\Database\Seeder;

class ComensaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comensal = new Comensale();
        $comensal->nombres = "Enrrique Jose";
        $comensal->apellidos = "Perez jimenes";
        $comensal->nacionalidad = "V";
        $comensal->cedula = "24753788";
        $comensal->sexo = "M";
        $comensal->tipo_comensal = "EVENTUAL";
        $comensal->save();
    }
}
