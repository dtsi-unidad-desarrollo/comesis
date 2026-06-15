<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSyncEstudiantes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = Carbon::now();
        Log::info('Sincronización de empleados iniciada en segundo plano.', ['started_at' => $start->toDateTimeString()]);

        try {
            $comensale = new \App\Models\Comensale;
            $comensale->executeSincronizarEstudiantes();
        } catch (\Throwable $th) {
            Log::error('Error al sincronizar estudiantes.', ['exception' => $th]);
        }
    }
}
