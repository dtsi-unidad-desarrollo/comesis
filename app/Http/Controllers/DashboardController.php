<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDashboardRequest;
use App\Http\Requests\UpdateDashboardRequest;
use App\Models\{
    Cuota,
    Dashboard,
    DataDev,
    Entrada,
    Estudiante,
    Grupo,
    GrupoEstudiante,
    Pago,
    Profesore,
    Servicio
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }


    /**
     * Get entrada statistics for a given date range
     *
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Support\Collection
     * 
     * $tipoDeConsulta can be 'diaria', 'semanal' or 'mensual' to determine the grouping of the results
     */
    public function getEntradaStats($startDate, $endDate = null, $tipoDeConsulta = 'diaria')
    {
        $stats = [];
        $dateRange = [];

        if ($endDate) {
            if ($tipoDeConsulta == "semanal") {
                return $this->getWeeklyStats($startDate, $endDate);
            } elseif ($tipoDeConsulta == "mensual") {
                // Aquí podrías implementar la lógica para obtener estadísticas mensuales
                // agrupando por día o por semana dentro del mes, dependiendo de tus necesidades.
                $this->getMonthlyStats($startDate, $endDate);
            }
        } else {
            $stats = DB::table('entradas')
                ->select('tipo_comensal', DB::raw('count(*) as cantidad'))
                ->whereDate('fecha', $startDate)
                ->groupBy('tipo_comensal')
                ->pluck('cantidad', 'tipo_comensal');
            // Si quieres asegurar que siempre tengas ambos tipos aunque no haya registros:
            $result = [
                'EMPLEADO' => $stats['EMPLEADO'] ?? 0,
                'ESTUDIANTE' => $stats['ESTUDIANTE'] ?? 0,
            ];

            // También puedes incluir el total si quieres:
            $result['total'] = $result['EMPLEADO'] + $result['ESTUDIANTE'];

            // consultamos el servicio para obtener el total de bandejas disponibles, por ejemplo, 2000
            $result['disponibles'] = Servicio::where('nombre', 'ALMUERZO')->first()->disponibilidad ?? 0;

            //bandejas no entregadas
            $result['no_entregadas'] = $result['disponibles'] - $result['EMPLEADO'] - $result['ESTUDIANTE'];

            // Retornar como respuesta JSON
            return $result;
        }
    }

    /**
     * Get formatted statistics array
     *
     * @return array
     */
    private function getFormattedStats()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $dailyStats = $this->getEntradaStats($today);
        $weeklyStats = $this->getEntradaStats($startOfWeek, Carbon::now()->endOfWeek(), 'semanal');
        $monthlyStats = $this->getEntradaStats($startOfMonth, Carbon::now()->endOfMonth(), 'mensual');
        return [
            'hoy' => $dailyStats,
            'semanal' => $weeklyStats,
            'monthly' => $monthlyStats,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $personal['ADMINISTRATIVO'] = DB::connection('mysql_third')
                ->table('personal')
                ->where('per_cedula', 24823972)
                ->get();
        $personal['ADMINISTRATIVO_JUBILADO'] = DB::connection('mysql_third')
                ->table('personal')
                ->where('per_cedula', 8141226)
                ->get();

        $personal['OBRERO'] = DB::connection('mysql_third')
                ->table('personal')
                ->where('per_cedula', 14002861)
                ->get();

        $personal['DOCENTE'] = DB::connection('mysql_third')
                ->table('personal')
                ->where('per_cedula', 16791197)
                ->get();

        return $personal;

        $respuesta = $this->data->respuesta;
        $stats = $this->getFormattedStats();
        return view('admin.dashboard', compact('respuesta', 'stats', 'stats'));
    }

    /**
     * Get statistics for AJAX requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            $stats = $this->getFormattedStats();
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving statistics'], 500);
        }
    }

    public function getMonthlyStats($startDate, $endDate)
    {
        // Aquí podrías implementar la lógica para obtener estadísticas mensuales
        // agrupando por día o por semana dentro del mes, dependiendo de tus necesidades.
    }

    public function getWeeklyStats($startDate, $endDate)
    {
        $stats = [];
        $dateRange = [];

        // crear un array de fecha para cada día entre startDate y endDate
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $dateRange[] = $currentDate->copy();
            $currentDate->addDay();
        }

        // inicializar un array con los días de la semana y los tipos de comensal
        $stats = collect($dateRange)->mapWithKeys(function ($date) {
            // obtener el nombre del día en minúsculas y en español
            $diaSemana = strtolower($date->locale('es')->dayName);
            // quitar los acentos para evitar problemas con los nombres de los días
            $diaSemana = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $diaSemana);
            // $diaSemana = strtolower($date->format('l'));
            return [$diaSemana => ['EMPLEADO' => 0, 'ESTUDIANTE' => 0]];
        });

        foreach ($dateRange as $date) {
            // obtener los datos del día de la fecha $date
            $dataDay = DB::table('entradas')
                ->select('tipo_comensal', DB::raw('count(*) as cantidad'))
                ->whereDate('fecha', $date)
                ->groupBy('tipo_comensal')
                ->pluck('cantidad', 'tipo_comensal');

            // combinar los datos del día con el array inicial
            foreach ($dataDay as $tipoComensal => $cantidad) {
                $diasDeLaSemana = strtolower($date->locale('es')->dayName);
                $diasDeLaSemana = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $diasDeLaSemana);
                $array = $stats->toArray();
                $array[$diasDeLaSemana][$tipoComensal] = doubleval($cantidad);
                $stats = collect($array);
            }

            // totalizar los datos del día para obtener el total de comensales
            $totalDia = $dataDay->sum();
            $diasDeLaSemana = strtolower($date->locale('es')->dayName);
            $diasDeLaSemana = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $diasDeLaSemana);
            $array = $stats->toArray();
            $array[$diasDeLaSemana]['total'] = doubleval($totalDia);
            $array[$diasDeLaSemana]['fecha'] = Carbon::create($date)->format('Y-m-d');
            $stats = collect($array);
        }

        // total de estudiantes y empleados en la semana

        $stats['total'] = [
            'EMPLEADO' => $stats->sum(function ($day) {
                return $day['EMPLEADO'] ?? 0;
            }),
            'ESTUDIANTE' => $stats->sum(function ($day) {
                return $day['ESTUDIANTE'] ?? 0;
            }),
            'TODOS' => $stats->sum(function ($day) {
                return $day['total'] ?? 0;
            }),
        ];


        return $stats;
    }
}
