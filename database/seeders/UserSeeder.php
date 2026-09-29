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
        $users = [
            [
                'email' => 'root',
                'nombre' => 'Super usuario',
                'rol' => 1,
                'password' => Hash::make('12345678'),
            ],
            [
                'email' => 'admin',
                'nombre' => 'Administrador',
                'rol' => 2,
                'password' => Hash::make('12345678'),
            ],
            [
                'email' => 'cajero',
                'nombre' => 'Cajero 1',
                'rol' => 3,
                'password' => Hash::make('12345678'),
            ],
            [
                'email' => 'cajero2',
                'nombre' => 'Cajero 2',
                'rol' => 3,
                'password' => Hash::make('12345678'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Condición de búsqueda
                $userData                        // Datos a actualizar o crear
            );
        }
    }
}
