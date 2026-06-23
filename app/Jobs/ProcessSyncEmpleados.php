<?php

namespace App\Jobs;

use App\Models\Comensale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
            // Contar registros remotos antes de sincronizar
            $excludeNames = ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'];
            $total = DB::connection('mysql_third')
                ->table('personal')
                ->whereNotIn('nom_nombre', $excludeNames)
                ->count();

            $active = DB::connection('mysql_third')
                ->table('personal')
                ->whereNotIn('nom_nombre', $excludeNames)
                ->where('nom_status', 1)
                ->count();

            $inactive = $total - $active;

            Log::info('Resumen previo a sincronización de empleados.', [
                'total_remote_records' => $total,
                'active_remote_records' => $active,
                'inactive_remote_records' => $inactive,
            ]);

            // Lógica para sincronizar empleados            
            Comensale::executeSincronizarEmpleados();

            $end = Carbon::now();
            $duration = $end->diffInSeconds($start);
            Log::info('Sincronización de empleados finalizada.', [
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
