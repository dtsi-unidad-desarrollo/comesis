<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permisos = [
            "panel",
            "comensales",
            "users",
            "roles",
            "permisos",
            "entradas",
            "recepcion",
            "reportes",
            "reportes-semanal",
            "reportes-mensual",
            "reportes-semanas-mes",
            "sincronizarData",
            "servicios",
            "torniquetes",
            "atms",
        ];

        foreach ($permisos as $key => $value) {
            $permiso = new Permiso();
            $permiso->nombre = $value;
            $permiso->save();
        }

    }
}
