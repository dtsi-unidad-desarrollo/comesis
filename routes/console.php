<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Tareas Programadas (Scheduled Tasks)
|--------------------------------------------------------------------------
*/
use App\Jobs\ProcessSyncEmpleados;
use App\Jobs\ProcessSyncEstudiantes;
use Illuminate\Support\Facades\Schedule;

// Sincronización diaria
Schedule::job(new ProcessSyncEmpleados)->dailyAt('14:45');
Schedule::job(new ProcessSyncEstudiantes)->dailyAt('15:45');

// Respaldo de Base de Datos cada 3 meses
Schedule::command('db:backup')->quarterly();
