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
        "EVENTUAL"
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
            $tipos = [
                "ESTUDIANTE",
                "ESTUDIANTE FORANEO",
                "EMPLEADO",
                "EVENTUAL",

            ];

            if ($request->activo == true) {

                if ($request->servicio && $request->fecha && $request->filtro) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('comida', $request->servicio)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'DESC')->paginate(12);

                    if (!count($entradas)) {
                        $entradas = Entrada::whereDate('created_at', $request->fecha)
                            ->where('comida', $request->servicio)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'DESC')->paginate(12);
                    }
                } else if ($request->servicio && $request->fecha) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('comida', $request->servicio)
                        ->orderBy('fecha', 'DESC')->paginate(12);
                } else if ($request->fecha && $request->filtro) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'DESC')->paginate(12);

                    if (!count($entradas)) {
                        $entradas = Entrada::whereDate('created_at', $request->fecha)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'DESC')->paginate(12);
                    }
                } else if ($request->servicio && $request->filtro) {
                    $entradas = Entrada::where('comida', $request->servicio)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'DESC')->paginate(12);

                    if (!count($entradas)) {
                        $entradas = Entrada::where('comida', $request->servicio)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'DESC')->paginate(12);
                    }
                } else if ($request->filtro) {
                    $entradas = Entrada::where('cedula', $request->filtro)
                        ->orWhere('nombres', 'LIKE', "%{$request->filtro}%")
                        ->orWhere('apellidos', 'LIKE', "%{$request->filtro}%")
                        ->orderBy('fecha', 'DESC')->paginate(12);
                } else if ($request->servicio) {
                    $entradas = Entrada::where('comida', $request->servicio)
                        ->orderBy('fecha', 'DESC')->paginate(12);
                } else if ($request->fecha) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)->orderBy('nombres', 'ASC')->paginate(12);
                }
            } else {
                $entradas = Entrada::orderBy('created_at', 'DESC')->paginate(12);
            }

            $respuesta =  $this->data->respuesta;
            return view('admin.entradas.index', compact('servicios', 'entradas', 'tipos', 'respuesta', 'request'));
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

            // var_dump( $ahora->format('d-m-Y h:ia')); echo "<br>";
            // var_dump( $almuerzoInferior->format('d-m-Y h:ia')); echo "<br>";
            // var_dump( $almuerzoSuperior->format('d-m-Y h:ia')); echo "<br>";

            // var_dump($ahora->greaterThan($almuerzoInferior)); // Determina si la instancia es mayor (después) que otra
            // var_dump($ahora->lessThan($almuerzoSuperior)); // Determina si la instancia es menor (antes) que otra

            if ($ahora->lessThan($almuerzoSuperior) == true && $ahora->greaterThan($almuerzoInferior) == true) {
                $comida = "ALMUERZO";
            } elseif ($ahora->lessThan($cenaSuperior) == true && $ahora->greaterThan($cenaInferior) == true) {
                $comida = "CENA";
            } else {
                $mensaje = "Comedor inactivo, está fuera del horario de servicio.";
                $estatus = Response::HTTP_UNAUTHORIZED;
                return back()->with(compact('mensaje', 'estatus'));
            }

            Entrada::create([
                "cedula" => $request->cedula,
                "marcado" => $request->marcar,
                "comida" => $comida,
                'fecha' => $date->format('d-m-Y'),
                'hora' => $date->format('h:ia'),
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

    public function getReporte(Request $request)
    {
        $reporte = [];
        $fecha = Carbon::parse($request->input('fecha'))->startOfDay();
        $tipo = strtoupper($request->input('tipo')); // puede ser 'TODOS' o un tipo específico
        $servicio = $request->input('servicio'); // opcional: filtrar por servicio (ALMUERZO/CENA)

        // Totales de comidas
        $totalComidas = Entrada::whereDate('created_at', $fecha)
            ->where('comida', $servicio)
            ->count();

        // Si se solicitó un tipo concreto, sólo ese tipo debe aparecer con su total; los demás serán 0
        if ($tipo && $tipo != 'TODOS') {
            foreach ($this->tiposDeComensales as $tipoComensal) {
                if ($tipoComensal == $tipo) {
                    $reporte[$tipoComensal] = Entrada::whereDate('created_at', $fecha)
                        ->when($servicio && $servicio != 0, function ($q) use ($servicio) {
                            return $q->where('comida', $servicio);
                        })
                        ->where('tipo_comensal', $tipoComensal)
                        ->count();
                }
            }
        } else {
            foreach ($this->tiposDeComensales as $tipoComensal) {
                $reporte[$tipoComensal] = Entrada::whereDate('created_at', $fecha)
                    ->when($servicio && $servicio != 0, function ($q) use ($servicio) {
                        return $q->where('comida', $servicio);
                    })
                    ->where('tipo_comensal', $tipoComensal)
                    ->count();
            }
        }


        // Generar el PDF
        $pdf = Pdf::loadView('admin.entradas.reporte', [
            'fecha' => Helpers::normalizarFecha($fecha),
            'totalComidas' => $totalComidas,
            'reporte' => $reporte,
            'tipoSeleccionado' => $tipo,
            'servicioSeleccionado' => $servicio,
        ]);

        // Descargar el PDF
        return $pdf->stream('reporte_comidas_' . $fecha->toDateString() . '.pdf');
    }

    public function getReporteSemanal(Request $request)
    {
        $anio = $request->input('anio');
        $mes = $request->input('mes');
        $semana = (int) $request->input('semana');
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        // Validar que la semana sea positiva
        if ($semana < 1) {
            return back()->with('error', 'Semana no válida');
        }

        // Calcular el primer lunes del mes
        $primerLunes = Carbon::createFromDate($anio, $mes, 1);
        while ($primerLunes->dayOfWeek != 1) { // 1 = Lunes
            $primerLunes->addDay();
        }

        // Calcular fecha de inicio (lunes) de la semana seleccionada
        $inicio = $primerLunes->copy()->addWeeks($semana - 1);

        // Validar que la fecha de inicio esté dentro del mes
        if ($inicio->month != $mes) {
            return back()->with('error', 'La semana seleccionada no existe en este mes');
        }

        // Calcular fecha de fin (sábado)
        $fin = $inicio->copy()->addDays(5);

        // NO ajustar al mes - la semana puede cruzar meses
        // Generar array de fechas en formato d-m-Y (como se guardan en BD)
        $fechasRango = [];
        $iter = $inicio->copy();
        while ($iter->lessThanOrEqualTo($fin)) {
            $fechasRango[] = $iter->format('d-m-Y');
            $iter->addDay();
        }

        $queryBase = Entrada::whereIn('fecha', $fechasRango)
            ->when($servicio && $servicio != 0, fn($q) => $q->where('comida', $servicio))
            ->when($tipo && $tipo != 'TODOS', fn($q) => $q->where('tipo_comensal', $tipo));

        // Obtener conteos por fecha específica
        $conteosPorFecha = (clone $queryBase)
            ->selectRaw('fecha, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->pluck('cantidad', 'fecha')
            ->toArray();

        $diario = collect();
        foreach ($fechasRango as $fecha) {
            $fechaCarbon = Carbon::createFromFormat('d-m-Y', $fecha);
            $diaNombre = $fechaCarbon->format('l');
            $diasEsp = [
                'Monday' => 'Lunes',
                'Tuesday' => 'Martes',
                'Wednesday' => 'Miercoles',
                'Thursday' => 'Jueves',
                'Friday' => 'Viernes',
                'Saturday' => 'Sabado',
                'Sunday' => 'Domingo',
            ];
            $dia = $diasEsp[$diaNombre] ?? $diaNombre;
            $bandejas = $conteosPorFecha[$fecha] ?? 0;

            $diario->push((object)[
                'dia' => $dia,
                'fecha' => $fechaCarbon->format('d/m/Y'),
                'bandejas' => $bandejas,
            ]);
        }

        $totalComidas = array_sum($conteosPorFecha);

        $pdf = Pdf::loadView('admin.entradas.reporte_semanal', [
            'fecha' => $inicio->format('d/m/Y'),
            'desde' => $inicio->format('d/m/Y'),
            'hasta' => $fin->format('d/m/Y'),
            'diario' => $diario,
            'totalComidas' => $totalComidas,
        ]);

        return $pdf->stream('reporte_semanal_' . $inicio->toDateString() . '.pdf');
    }

    public function getReporteMensual(Request $request)
    {
        $mes = $request->input('mes'); // 1-12
        $anio = $request->input('anio');
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        // Build date range for the entire month
        $inicioMes = Carbon::createFromDate($anio, $mes, 1)->startOfDay();
        $finMes = Carbon::createFromDate($anio, $mes, 1)->endOfMonth()->endOfDay();

        $inicioFormat = $inicioMes->format('d-m-Y');
        $finFormat = $finMes->format('d-m-Y');

        // Base query with filters
        $queryBase = Entrada::whereBetween('fecha', [$inicioFormat, $finFormat])
            ->when($servicio && $servicio != 0, fn($q) => $q->where('comida', $servicio))
            ->when($tipo && $tipo != 'TODOS', fn($q) => $q->where('tipo_comensal', $tipo));

        // Get daily counts grouped by fecha and tipo_comensal
        $registros = (clone $queryBase)
            ->selectRaw('fecha, tipo_comensal, COUNT(*) as cantidad')
            ->groupByRaw('fecha, tipo_comensal')
            ->orderByRaw('STR_TO_DATE(fecha, "%d-%m-%Y")')
            ->get()
            ->groupBy('fecha');

        // Generate all dates in the month (1 to number of days)
        $diasEnElMes = $finMes->day;
        $rangoFechas = collect();
        for ($dia = 1; $dia <= $diasEnElMes; $dia++) {
            $fecha = Carbon::createFromDate($anio, $mes, $dia)->format('d-m-Y');
            $rangoFechas->push($fecha);
        }

        // Calculate grand total
        $granTotal = 0;
        foreach ($rangoFechas as $fecha) {
            if (isset($registros[$fecha])) {
                foreach ($registros[$fecha] as $registro) {
                    $granTotal += $registro->cantidad;
                }
            }
        }

        $pdf = Pdf::loadView('admin.entradas.reporte_mensual', [
            'mes' => $this->getMesNombre($mes),
            'anio' => $anio,
            'rangoFechas' => $rangoFechas,
            'registros' => $registros,
            'granTotal' => $granTotal,
        ]);

        return $pdf->stream('reporte_mensual_' . $inicioMes->format('Y-m') . '.pdf');
    }

    public function getReporteSemanasMes(Request $request)
    {
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $servicio = $request->input('servicio');
        $tipo = strtoupper($request->input('tipo', 'TODOS'));

        $primerDiaMes = Carbon::createFromDate($anio, $mes, 1);
        $primerLunes = Carbon::createFromDate($anio, $mes, 1);
        while ($primerLunes->dayOfWeek != 1) {
            $primerLunes->addDay();
        }

        if ($primerLunes->month != $mes) {
            $semanas = collect();
            $granTotal = 0;
        } else {
            $semanas = collect();
            $lunes = $primerLunes->copy();
            $ultimoDiaMes = $primerDiaMes->copy()->endOfMonth();

             while ($lunes <= $ultimoDiaMes) {
                 $sabado = $lunes->copy()->addDays(5);

                 $fechasSemana = [];
                 $fechaIter = $lunes->copy();
                 while ($fechaIter <= $sabado) {
                     $fechasSemana[] = $fechaIter->format('d-m-Y');
                     $fechaIter->addDay();
                 }

                 $total = Entrada::whereIn('fecha', $fechasSemana)
                     ->when($servicio && $servicio != 0, fn($q) => $q->where('comida', $servicio))
                     ->when($tipo && $tipo != 'TODOS', fn($q) => $q->where('tipo_comensal', $tipo))
                     ->count();

                 $semanas->push((object)[
                     'desde' => $lunes->copy(),
                     'hasta' => $sabado->copy(),
                     'total' => $total,
                 ]);

                 $lunes->addDays(7);
             }

            $granTotal = $semanas->sum('total');
        }

        $pdf = Pdf::loadView('admin.entradas.reporte_semanas_mes', [
            'mes' => $this->getMesNombre($mes),
            'anio' => $anio,
            'semanas' => $semanas,
            'granTotal' => $granTotal,
        ]);

        return $pdf->stream('reporte_semanas_mes_' . $anio . '_' . $mes . '.pdf');
    }

    private function getMesNombre($mes)
    {
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE',
        ];
        return $meses[$mes] ?? '';
    }
}
