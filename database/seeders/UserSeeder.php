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
        $user->nombre = env('ROOT_NAME', 'Super usuario');
        $user->rol = env('ROOT_ROLE', 1);
        $user->email = env('ROOT_EMAIL', 'root@example.com');
        $user->password = Hash::make(env('ROOT_PASSWORD', 'change_me'));
        $user->save();

        $userDos = new User();
        $userDos->nombre = "Cajero";
        $userDos->rol = 3;
        $userDos->email = "@cajero";
        $userDos->password = Hash::make(12345678);
        $userDos->save();
    }
}
