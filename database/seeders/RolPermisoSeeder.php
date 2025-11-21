<?php

namespace Database\Seeders;

use App\Models\RolPermiso;
use Illuminate\Database\Seeder;

class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "administrador" => 2,
            "cajero" => 3
        ];

        $permososDeAdminitrador = [
            "panel",
            "comensales",
            "users",
            "recepcion",
            "reportes",
            "entradas",
            "sincronizarData",
            "servicios",
        ];

        $permososDeCajero = [
            "recepcion",
            "reportes",
            "entradas",
        ];

        foreach ($permososDeAdminitrador as $key => $value) {
            $permiso = new RolPermiso();
            $permiso->id_rol = $roles['administrador'];
            $permiso->id_permiso = $value;
            $permiso->save();
        }

        foreach ($permososDeCajero as $key => $value) {
            $permiso = new RolPermiso();
            $permiso->id_rol = $roles['cajero'];
            $permiso->id_permiso = $value;
            $permiso->save();
        }
    }
}
