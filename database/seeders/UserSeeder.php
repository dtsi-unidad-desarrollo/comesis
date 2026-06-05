<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->nombre = env('ADMIN_NAME', 'Administrador');
        $user->rol = env('ADMIN_ROLE', 2);
        $user->email = env('ADMIN_EMAIL', 'admin');
        $user->password = Hash::make(env('ADMIN_PASSWORD', '12345678'));
        $user->save();

        $user = new User();
        $user->nombre = env('ROOT_NAME', 'Super usuario');
        $user->rol = env('ROOT_ROLE', 1);
        $user->email = env('ROOT_EMAIL', 'root');
        $user->password = Hash::make(env('ROOT_PASSWORD', '12345678'));
        $user->save();

        $userDos = new User();
        $userDos->nombre = env('CAJERO_NAME', 'Cajero');
        $userDos->rol = env('CAJERO_ROLE', 3);
        $userDos->email = env('CAJERO_EMAIL', 'cajero');
        $userDos->password = Hash::make(env('CAJERO_PASSWORD', '12345678'));
        $userDos->save();
    }
}
