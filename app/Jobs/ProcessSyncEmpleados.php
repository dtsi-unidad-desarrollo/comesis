<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessSyncEmpleados implements ShouldQueue
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
            // Lógica para sincronizar empleados
            $comensale = new \App\Models\Comensale;
            $comensale->executeSincronizarEmpleados();

            $end = Carbon::now();
            $duration = $end->diffInSeconds($start);
            Log::info('Sincronización de empleados completada correctamente.', [
                'started_at' => $start->toDateTimeString(),
                'finished_at' => $end->toDateTimeString(),
                'duration_seconds' => $duration,
            ]);
        } catch (\Throwable $e) {
            $failedAt = Carbon::now();
            Log::error('Error durante sincronización de empleados.', [
                'message' => $e->getMessage(),
                'started_at' => $start->toDateTimeString(),
                'failed_at' => $failedAt->toDateTimeString(),
                'exception' => get_class($e),
            ]);
            // Relanzar para permitir reintentos de la cola si están configurados
            throw $e;
        }
    }
}
