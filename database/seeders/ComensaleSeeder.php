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
       // agregar el factory
        Comensale::factory()->count(10)->create();
    }
}
