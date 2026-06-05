<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ComensaleController;

class SyncEmpleadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:empleados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta la sincronización de empleados en 3 pasos.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = app(ComensaleController::class);

        try {
            $controller->executeSincronizarEmpleadosCron();
            $this->info('Sincronización de empleados programada ejecutada correctamente.');
            return 0;
        } catch (\Throwable $th) {
            $this->error('Error al ejecutar la sincronización de empleados: ' . $th->getMessage());
            return 1;
        }
    }
}
