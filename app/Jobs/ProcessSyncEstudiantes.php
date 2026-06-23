<?php

namespace App\Jobs;

use App\Models\Comensale;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        Log::info('Sincronización de estudiantes iniciada en segundo plano.', ['started_at' => $start->toDateTimeString()]);

        try {
            // Contar registros remotos antes de sincronizar
            $total = DB::connection('mysql_second')
                ->table('estudiantes')
                ->count();

            $active = DB::connection('mysql_second')
                ->table('estudiantes as e')
                ->join('carreras_est as c', 'c.ConexEst', '=', 'e.Cedula')
                ->where('c.Status', 'A')
                ->distinct()
                ->count('e.Cedula');

            $inactive = $total - $active;

            Log::info('Resumen previo a sincronización de estudiantes.', [
                'total_remote_records' => $total,
                'active_remote_records' => $active,
                'inactive_remote_records' => $inactive,
            ]);

            // Lógica para sincronizar estudiantes
            Comensale::executeSincronizarEstudiantes();

            $end = Carbon::now();
            $duration = $end->diffInSeconds($start);
            Log::info('Sincronización de estudiantes finalizada.', [
                'started_at' => $start->toDateTimeString(),
                'finished_at' => $end->toDateTimeString(),
                'duration_seconds' => $duration,
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al sincronizar estudiantes.', ['exception' => $th]);
        }
    }
}
