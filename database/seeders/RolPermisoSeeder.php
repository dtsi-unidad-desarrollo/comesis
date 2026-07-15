<?php

namespace Database\Seeders;

use App\Models\RolPermiso;
use App\Models\Role;
use App\Models\Permiso;
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
            'root' => Role::where('nombre', 'ROOT')->value('id'),
            'administrador' => Role::where('nombre', 'ADMINISTRADOR')->value('id'),
            'cajero' => Role::where('nombre', 'CAJERO')->value('id'),
        ];

        $permisosRoot = Permiso::pluck('id')->toArray();

        $permisosAdministrador = [
            'panel',
            'comensales',
            'users',
            'roles',
            'permisos',
            'recepcion',
            'entradas',
            'reportes',
            'reportes-semanal',
            'reportes-mensual',
            'reportes-semanas-mes',
            'sincronizarData',
            'servicios',
            'torniquetes',
            'select', // permiso para seleccionar el ATM en recepción
            "configuracion",
            'atms',
            'perfil'
        ];

        $permisosCajero = [
            'recepcion',
            'reportes',
            'entradas',
            'select', // permiso para seleccionar el ATM en recepción
            'perfil'
        ];

        if ($roles['root']) {
            foreach ($permisosRoot as $permisoId) {
                RolPermiso::create(['id_rol' => $roles['root'], 'id_permiso' => $permisoId]);
            }
        }

        if ($roles['administrador']) {
            foreach ($permisosAdministrador as $permisoNombre) {
                $permisoId = Permiso::where('nombre', $permisoNombre)->value('id');
                if ($permisoId) {
                    RolPermiso::create(['id_rol' => $roles['administrador'], 'id_permiso' => $permisoId]);
                }
            }
        }

        if ($roles['cajero']) {
            foreach ($permisosCajero as $permisoNombre) {
                $permisoId = Permiso::where('nombre', $permisoNombre)->value('id');
                if ($permisoId) {
                    RolPermiso::create(['id_rol' => $roles['cajero'], 'id_permiso' => $permisoId]);
                }
            }
        }
    }
}
