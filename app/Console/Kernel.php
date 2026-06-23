<?php

namespace App\Console;

use App\Jobs\ProcessSyncEmpleados;
use App\Jobs\ProcessSyncEstudiantes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Empleados
        $schedule->job(new ProcessSyncEmpleados)->dailyAt('14:45');
        
        // Estudiantes
        $schedule->job(new ProcessSyncEstudiantes)->dailyAt('15:45');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
