<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ComensalesImport;

class TestComensalesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:import {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta la importacion de comensales desde un archivo de prueba';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file') ?? storage_path('app/test_comensales.csv');

        if (!file_exists($file)) {
            $this->error("Archivo no encontrado: {$file}");
            return 1;
        }

        try {
            Excel::import(new ComensalesImport, $file);
            $this->info('Importación ejecutada correctamente.');
            return 0;
        } catch (\Throwable $th) {
            $this->error('Error durante la importación: ' . $th->getMessage());
            return 2;
        }
    }
}
