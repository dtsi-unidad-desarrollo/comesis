<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Http\Requests\StoreEntradaRequest;
use App\Http\Requests\UpdateEntradaRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Servicio;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ReporteComedorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntradaController extends Controller
{
    public $data;
    public $tiposDeComensales = [
        "ESTUDIANTE",
        "ESTUDIANTE FORANEO",
        "EMPLEADO",
        "EVENTUAL",
        "APOYO"
    ];

    public function __construct()
    {
        $this->data = new DataDev;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $entradas = [];
            $servicios = Servicio::all();
            $tipos = $this->tiposDeComensales;

            $query = Entrada::with(['atm', 'user']);

            if ($request->activo == true) {
                if ($request->servicio && $request->servicio !== '0') {
                    $query->where('comida', $request->servicio);
                }

                if ($request->fecha) {
                    $query->whereDate('fecha', $request->fecha);
                }

                if ($request->filtro) {
                    $filtro = trim($request->filtro);
                    $query->where(function ($q) use ($filtro) {
                        $q->where('cedula', 'LIKE', "%{$filtro}%")
                          ->orWhere('nombres', 'LIKE', "%{$filtro}%")
                          ->orWhere('apellidos', 'LIKE', "%{$filtro}%");
                    });
                }

                $entradas = $query->orderBy('fecha', 'DESC')->orderBy('id', 'DESC')->paginate(12);
            } else {
                $entradas = $query->orderBy('created_at', 'DESC')->paginate(12);
            }

            // Estadísticas rápidas de hoy
            $hoy = Carbon::now()->format('Y-m-d');
            $statsHoy = [
                'total'       => Entrada::whereDate('fecha', $hoy)->count(),
                'estudiantes' => Entrada::whereDate('fecha', $hoy)->whereIn('tipo_comensal', ['ESTUDIANTE', 'ESTUDIANTE FORANEO'])->count(),
                'empleados'   => Entrada::whereDate('fecha', $hoy)->where('tipo_comensal', 'EMPLEADO')->count(),
                'apoyos'      => Entrada::whereDate('fecha', $hoy)->where('tipo_comensal', 'APOYO')->count(),
            ];

            $respuesta = $this->data->respuesta;

            return view('admin.entradas.index', compact('servicios', 'entradas', 'tipos', 'respuesta', 'request', 'statsHoy'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar las entradas!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function store(StoreEntradaRequest $request)
    {
        try {
            $comida = "";
            $date = Carbon::now();
            $ahora = Carbon::now();
            /** Configuracion de rango de tiempo de comida ALMUERZO */
            $almuerzoInferior = Carbon::now()
                ->startOfDay()
                ->addHours(10);

            $almuerzoSuperior = $almuerzoInferior->copy()
                ->addHours(3)
                ->addMinute(30);

            /** Configuracion de rango de tiempo de comida CENA */
            $cenaInferior = Carbon::now()
                ->startOfDay()
                ->addHours(18);

            $cenaSuperior = $cenaInferior->copy()
                ->addHours(1)
                ->addMinute(15);

            if ($ahora->lessThan($almuerzoSuperior) == true && $ahora->greaterThan($almuerzoInferior) == true) {
                $comida = "ALMUERZO";
            } elseif ($ahora->lessThan($cenaSuperior) == true && $ahora->greaterThan($cenaInferior) == true) {
                $comida = "CENA";
            } else {
                $mensaje = "Comedor inactivo, está fuera del horario de servicio.";
                $estatus = Response::HTTP_UNAUTHORIZED;
                return back()->with(compact('mensaje', 'estatus'));
            }

            $apoyo = \App\Models\Apoyo::where('codigo', $request->cedula)->first();

            if ($apoyo) {
                if (!$apoyo->estatus) {
                    $mensaje = "Este código de apoyo está inactivo.";
                    $estatus = Response::HTTP_UNAUTHORIZED;
                    return back()->with(compact('mensaje', 'estatus'));
                }
                if ($apoyo->cantidad_consumida >= $apoyo->cantidad_habilitada) {
                    $mensaje = "Este código de apoyo ya ha consumido todas las comidas habilitadas.";
                    $estatus = Response::HTTP_UNAUTHORIZED;
                    return back()->with(compact('mensaje', 'estatus'));
                }

                $apoyo->increment('cantidad_consumida');

                Entrada::create([
                    "cedula" => $apoyo->codigo,
                    "nombres" => $apoyo->institucion,
                    "apellidos" => "APOYO INSTITUCIONAL",
                    "nacionalidad" => "V",
                    "sexo" => "N/A",
                    "tipo_comensal" => "APOYO",
                    "comida" => $comida,
                    'fecha' => $date->format('Y-m-d'),
                    'hora' => $date->format('h:ia'),
                    'atm_id' => $request->input('atm_id'),
                    'allowed_by_user_id' => $request->input('allowed_by_user_id'),
                ]);

                $mensaje = "Apoyo registrado exitosamente. Quedan " . ($apoyo->cantidad_habilitada - $apoyo->cantidad_consumida) . " comidas.";
                $estatus = Response::HTTP_OK;
                return back()->with(compact('mensaje', 'estatus'));
            }

            Entrada::create([
                "cedula" => $request->cedula,
                "marcado" => $request->marcar,
                "comida" => $comida,
                'fecha' => $date->format('Y-m-d'),
                'hora' => $date->format('h:ia'),
                'atm_id' => $request->input('atm_id'),
                'allowed_by_user_id' => $request->input('allowed_by_user_id'),
            ]);

            $mensaje = "Comensal marcado como: " . $request->marcar;
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar sincronizar los estudiante!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function destroy(Entrada $entrada)
    {
        try {

            $entrada->delete();
            $mensaje = "Entrada eliminada correctamente.";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {

            $mensaje = Helpers::getMensajeError($th, "Error interno");
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function getReporte(Request $request, ReporteComedorService $reporteService)
    {
        $fecha = $request->input('fecha') ?: now()->format('Y-m-d');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));
        $servicio = $request->input('servicio');

        $data = $reporteService->getReporteDiarioData($fecha, $servicio, $tipo);
        $pdf = Pdf::loadView('admin.entradas.reporte', $data);

        return $pdf->stream('reporte_de_entradas_' . $fecha . '.pdf');
    }

    public function getReporteSemanal(Request $request, ReporteComedorService $reporteService)
    {
        $anio = (int) $request->input('anio', date('Y'));
        $mes = (int) $request->input('mes', date('n'));
        $semana = (int) $request->input('semana', 1);
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        if ($semana < 1) {
            return back()->with('error', 'Semana no válida');
        }

        $data = $reporteService->getReporteSemanalData($anio, $mes, $semana, $servicio, $tipo);

        if (!$data) {
            return back()->with('error', 'La semana seleccionada no existe en este mes');
        }

        $pdf = Pdf::loadView('admin.entradas.reporte_semanal', $data);

        return $pdf->stream('reporte_semanal_' . $anio . '_' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '_sem' . $semana . '.pdf');
    }

    public function getReporteMensual(Request $request, ReporteComedorService $reporteService)
    {
        $mes = (int) $request->input('mes', date('n'));
        $anio = (int) $request->input('anio', date('Y'));
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        $data = $reporteService->getReporteMensualData($anio, $mes, $servicio, $tipo);
        $pdf = Pdf::loadView('admin.entradas.reporte_mensual', $data);

        return $pdf->stream('reporte_mensual_' . $anio . '_' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function getReporteSemanasMes(Request $request, ReporteComedorService $reporteService)
    {
        $mes = (int) $request->input('mes', date('n'));
        $anio = (int) $request->input('anio', date('Y'));
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        $data = $reporteService->getReporteSemanasMesData($anio, $mes, $servicio, $tipo);
        $pdf = Pdf::loadView('admin.entradas.reporte_semanas_mes', $data);

        return $pdf->stream('reporte_semanas_mes_' . $anio . '_' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '.pdf');
    }
}
