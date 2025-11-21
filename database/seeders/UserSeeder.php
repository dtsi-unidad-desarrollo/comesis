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
        $user->nombre = "Administrador";
        $user->rol = 2;
        $user->email = "@administrador";
        $user->password = Hash::make(12345678);
        $user->save();

        $user = new User();
        $user->nombre = "Super usuario";
        $user->rol = 1;
        $user->email = "@root";
        $user->password = Hash::make("Dtsi2024/*/*");
        $user->save();

        $userDos = new User();
        $userDos->nombre = "Cajero";
        $userDos->email = "@cajero";
        $userDos->password = Hash::make(12345678);
        $userDos->save();
    }
}
