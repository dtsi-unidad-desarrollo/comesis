<?php

namespace App\Http\Controllers;

use App\Models\{
    Apoyo,
    Atm,
    Comensale,
    DataDev,
    Entrada,
    Helpers,
    Servicio
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * Muestra el panel de control administrativo principal (Cpanel / Dashboard).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $respuesta = $this->data->respuesta;
        $stats = $this->getFormattedStats();

        $ultimasEntradas = Entrada::with(['atm', 'allowedBy'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $atms = Atm::with('torniquete')->get();
        $servicioActual = Helpers::getServicio(Carbon::now());
        $servicios = Servicio::all();

        return view('admin.dashboard', compact(
            'respuesta',
            'stats',
            'ultimasEntradas',
            'atms',
            'servicioActual',
            'servicios'
        ));
    }

    /**
     * Endpoint API para actualizar estadísticas en tiempo real vía AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            $stats = $this->getFormattedStats();
            return response()->json($stats);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error al obtener estadísticas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Genera la estructura consolidada de métricas y estadísticas.
     *
     * @return array
     */
    private function getFormattedStats()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $dailyStats = $this->getTodayStats($today);
        $weeklyStats = $this->getWeeklyStats($startOfWeek, $endOfWeek);
        $monthlyStats = $this->getMonthlyStats($startOfMonth, $endOfMonth);
        $globalStats = $this->getGlobalStats();

        return [
            'hoy' => $dailyStats,
            'semanal' => $weeklyStats,
            'mensual' => $monthlyStats,
            'monthly' => $monthlyStats, // Alias retrocompatible
            'globales' => $globalStats,
        ];
    }

    /**
     * Obtiene estadísticas de entradas para el día actual.
     *
     * @param Carbon|string $today
     * @return array
     */
    public function getTodayStats($today)
    {
        $todayDate = $today instanceof Carbon ? $today->format('Y-m-d') : $today;

        $stats = DB::table('entradas')
            ->select('tipo_comensal', DB::raw('count(*) as cantidad'))
            ->whereDate('fecha', $todayDate)
            ->groupBy('tipo_comensal')
            ->pluck('cantidad', 'tipo_comensal')
            ->toArray();

        $estudiantes = (int)($stats['ESTUDIANTE'] ?? 0);
        $empleados = (int)($stats['EMPLEADO'] ?? 0);
        $apoyos = (int)($stats['APOYO'] ?? 0);
        $eventuales = (int)($stats['EVENTUAL'] ?? 0);
        $otros = (int)array_sum(array_diff_key($stats, array_flip(['ESTUDIANTE', 'EMPLEADO', 'APOYO', 'EVENTUAL'])));

        $totalEntregadas = $estudiantes + $empleados + $apoyos + $eventuales + $otros;

        $servicioActual = Helpers::getServicio(Carbon::now()) ?: Servicio::where('nombre', 'ALMUERZO')->first() ?: Servicio::first();
        $disponibles = $servicioActual ? (int)$servicioActual->disponibilidad : 2000;
        $noEntregadas = max(0, $disponibles - $totalEntregadas);
        $porcentajeConsumo = $disponibles > 0 ? round(($totalEntregadas / $disponibles) * 100, 1) : 0;

        return [
            'ESTUDIANTE' => $estudiantes,
            'EMPLEADO' => $empleados,
            'APOYO' => $apoyos,
            'EVENTUAL' => $eventuales,
            'OTROS' => $otros,
            'total' => $totalEntregadas,
            'disponibles' => $disponibles,
            'no_entregadas' => $noEntregadas,
            'porcentaje' => $porcentajeConsumo,
            'servicio_nombre' => $servicioActual ? $servicioActual->nombre : 'ALMUERZO',
            'servicio' => $servicioActual,
            'desglose' => $stats,
        ];
    }

    /**
     * Obtiene estadísticas semanales agrupadas por día y tipo de comensal.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getWeeklyStats($startDate, $endDate)
    {
        $dateRange = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateRange[] = $currentDate->copy();
            $currentDate->addDay();
        }

        $entradasSemana = DB::table('entradas')
            ->select('fecha', 'tipo_comensal', DB::raw('count(*) as cantidad'))
            ->whereBetween('fecha', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('fecha', 'tipo_comensal')
            ->get();

        $entradasPorFecha = [];
        foreach ($entradasSemana as $item) {
            $entradasPorFecha[$item->fecha][$item->tipo_comensal] = (int)$item->cantidad;
        }

        $diasKey = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        $diasNombres = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        $result = [];
        $totalEstudiantes = 0;
        $totalEmpleados = 0;
        $totalApoyos = 0;
        $totalEventuales = 0;
        $totalGeneral = 0;

        foreach ($dateRange as $index => $date) {
            $fechaStr = $date->format('Y-m-d');
            $diaKey = $diasKey[$index] ?? strtolower($date->format('l'));
            $diaNombre = $diasNombres[$index] ?? $date->format('l');

            $dataDia = $entradasPorFecha[$fechaStr] ?? [];
            $est = (int)($dataDia['ESTUDIANTE'] ?? 0);
            $emp = (int)($dataDia['EMPLEADO'] ?? 0);
            $apo = (int)($dataDia['APOYO'] ?? 0);
            $eve = (int)($dataDia['EVENTUAL'] ?? 0);
            $tot = array_sum($dataDia);

            $totalEstudiantes += $est;
            $totalEmpleados += $emp;
            $totalApoyos += $apo;
            $totalEventuales += $eve;
            $totalGeneral += $tot;

            $result[$diaKey] = [
                'fecha' => $fechaStr,
                'dia' => $diaNombre,
                'ESTUDIANTE' => $est,
                'EMPLEADO' => $emp,
                'APOYO' => $apo,
                'EVENTUAL' => $eve,
                'total' => $tot,
            ];
        }

        $result['total'] = [
            'ESTUDIANTE' => $totalEstudiantes,
            'EMPLEADO' => $totalEmpleados,
            'APOYO' => $totalApoyos,
            'EVENTUAL' => $totalEventuales,
            'TODOS' => $totalGeneral,
        ];

        return $result;
    }

    /**
     * Obtiene estadísticas mensuales por día y totales consolidados.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getMonthlyStats($startDate, $endDate)
    {
        $entradasMes = DB::table('entradas')
            ->select('fecha', 'tipo_comensal', DB::raw('count(*) as cantidad'))
            ->whereBetween('fecha', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('fecha', 'tipo_comensal')
            ->get();

        $entradasPorFecha = [];
        foreach ($entradasMes as $item) {
            $entradasPorFecha[$item->fecha][$item->tipo_comensal] = (int)$item->cantidad;
        }

        $dias = [];
        $currentDate = $startDate->copy();
        $totalMes = 0;
        $diasConServicio = 0;

        while ($currentDate->lte($endDate)) {
            $fechaStr = $currentDate->format('Y-m-d');
            $dataDia = $entradasPorFecha[$fechaStr] ?? [];

            $est = (int)($dataDia['ESTUDIANTE'] ?? 0);
            $emp = (int)($dataDia['EMPLEADO'] ?? 0);
            $apo = (int)($dataDia['APOYO'] ?? 0);
            $tot = array_sum($dataDia);

            $totalMes += $tot;
            if ($tot > 0) {
                $diasConServicio++;
            }

            $dias[] = [
                'fecha' => $fechaStr,
                'dia' => $currentDate->format('d/m'),
                'dia_numero' => $currentDate->format('d'),
                'ESTUDIANTE' => $est,
                'EMPLEADO' => $emp,
                'APOYO' => $apo,
                'total' => $tot,
            ];

            $currentDate->addDay();
        }

        $promedioDiario = $diasConServicio > 0 ? round($totalMes / $diasConServicio, 1) : 0;

        return [
            'dias' => $dias,
            'total_mes' => $totalMes,
            'dias_activos' => $diasConServicio,
            'promedio_diario' => $promedioDiario,
            'mes_nombre' => Carbon::now()->locale('es')->monthName,
            'anio' => Carbon::now()->year,
        ];
    }

    /**
     * Obtiene métricas globales de comensales, apoyos, ATMs y accesos.
     *
     * @return array
     */
    public function getGlobalStats()
    {
        $totalComensales = Comensale::count();
        $estudiantesActivos = Comensale::where('tipo_comensal', 'ESTUDIANTE')->where('estatus', 1)->count();
        $empleadosActivos = Comensale::where('tipo_comensal', 'EMPLEADO')->where('estatus', 1)->count();

        $apoyosCount = Apoyo::count();
        $apoyosActivos = Apoyo::where('estatus', 1)->count();
        $apoyosHabilitadas = (int)Apoyo::where('estatus', 1)->sum('cantidad_habilitada');
        $apoyosConsumidas = (int)Apoyo::where('estatus', 1)->sum('cantidad_consumida');

        $totalAtms = Atm::count();
        $atmsEnUso = Atm::where('en_uso', 1)->count();
        $totalEntradas = Entrada::count();

        return [
            'total_comensales' => $totalComensales,
            'estudiantes_activos' => $estudiantesActivos,
            'empleados_activos' => $empleadosActivos,
            'total_apoyos' => $apoyosCount,
            'apoyos_activos' => $apoyosActivos,
            'apoyos_habilitadas' => $apoyosHabilitadas,
            'apoyos_consumidas' => $apoyosConsumidas,
            'total_atms' => $totalAtms,
            'atms_en_uso' => $atmsEnUso,
            'total_entradas' => $totalEntradas,
        ];
    }
}
