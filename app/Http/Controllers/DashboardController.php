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
    Profesore
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
     */
    private function getEntradaStats(Carbon $startDate, Carbon $endDate = null)
    {
        $query = Entrada::whereDate('created_at', '>=', $startDate);

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->selectRaw('tipo_comensal, COUNT(*) as count')
            ->groupBy('tipo_comensal')
            ->get()
            ->pluck('count', 'tipo_comensal');
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

        return Cache::remember('dashboard.stats', 60, function () use ($today, $startOfWeek, $startOfMonth) {
            $dailyStats = $this->getEntradaStats($today);
            $weeklyStats = $this->getEntradaStats($startOfWeek, Carbon::now()->endOfWeek());
            $monthlyStats = $this->getEntradaStats($startOfMonth, Carbon::now()->endOfMonth());

            return [
                'daily' => [
                    'estudiante' => $dailyStats->get('estudiante', 0),
                    'empleado' => $dailyStats->get('empleado', 0),
                    'total' => $dailyStats->sum()
                ],
                'weekly' => [
                    'estudiante' => $weeklyStats->get('estudiante', 0),
                    'empleado' => $weeklyStats->get('empleado', 0),
                    'total' => $weeklyStats->sum()
                ],
                'monthly' => [
                    'estudiante' => $monthlyStats->get('estudiante', 0),
                    'empleado' => $monthlyStats->get('empleado', 0),
                    'total' => $monthlyStats->sum()
                ]
            ];
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $respuesta = $this->data->respuesta;
       return  $stats = $this->getFormattedStats();

        return view('admin.dashboard', compact('respuesta', 'stats'));
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

    
}
