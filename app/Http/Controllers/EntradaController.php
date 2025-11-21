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

class EntradaController extends Controller
{
    public $data;

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
                "PROFESOR",
                "ADMINISTRATIVO",
                "EVENTUAL",
                "OBRERO",
            ];

            if ($request->activo == true) {

                if ($request->servicio && $request->fecha && $request->filtro) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('comida', $request->servicio)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'ASC')->paginate(12);

                        if (!count($entradas)) {
                            $entradas = Entrada::whereDate('created_at', $request->fecha)
                            ->where('comida', $request->servicio)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'ASC')->paginate(12);
                        }

                } else if ($request->servicio && $request->fecha) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('comida', $request->servicio)
                        ->orderBy('fecha', 'ASC')->paginate(12);
                        
                } else if ($request->fecha && $request->filtro) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'ASC')->paginate(12);

                        if (!count($entradas)) {
                            $entradas = Entrada::whereDate('created_at', $request->fecha)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'ASC')->paginate(12);
                        }
                } else if ($request->servicio && $request->filtro) {
                    $entradas = Entrada::where('comida', $request->servicio)
                        ->where('cedula', $request->filtro)
                        ->orderBy('fecha', 'ASC')->paginate(12);

                        if (!count($entradas)) {
                            $entradas = Entrada::where('comida', $request->servicio)
                            ->where('nombres', 'LIKE', "%{$request->filtro}%")
                            ->orderBy('fecha', 'ASC')->paginate(12);
                        }

                } else if ($request->filtro) {
                    $entradas = Entrada::where('cedula', $request->filtro)
                        ->orWhere('nombres', 'LIKE', "%{$request->filtro}%")
                        ->orWhere('apellidos', 'LIKE', "%{$request->filtro}%")
                        ->orderBy('fecha', 'ASC')->paginate(12);
                } else if ($request->servicio) {
                   $entradas = Entrada::where('comida', $request->servicio)
                        ->orderBy('fecha', 'ASC')->paginate(12);
                } else if ($request->fecha) {
                    $entradas = Entrada::whereDate('created_at', $request->fecha)->orderBy('nombres', 'ASC')->paginate(12);
                }
            } else {
                $entradas = Entrada::orderBy('fecha', 'ASC')->paginate(12);
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
        $fecha = Carbon::parse($request->input('fecha'))->startOfDay();

        // Obtener el total de almuerzos y cenas en la fecha especificada
        $totalAlmuerzos = Entrada::whereDate('created_at', $fecha)
            ->where('comida', 'ALMUERZO')
            ->count();

        $totalCenas = Entrada::whereDate('created_at', $fecha)
            ->where('comida', 'CENA')
            ->count();

        // Obtener el total de cada tipo de comensal
        $totalEstudiantes = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'ESTUDIANTE')
            ->count();


        $totalEstudiantesForaneos = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'ESTUDIANTE FORANEO')
            ->count();

        $totalObreros = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'OBRERO')
            ->count();

        $totalAdministrativos = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'ADMINISTRATIVO')
            ->count();


        $totalProfesor = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'PROFESOR')
            ->count();

        $totalEventual = Entrada::whereDate('created_at', $fecha)
            ->where('tipo_comensal', 'EVENTUAL')
            ->count();

        // Generar el PDF
        $pdf = Pdf::loadView('admin.entradas.reporte', [
            'fecha' => $fecha,
            'totalAlmuerzos' => $totalAlmuerzos,
            'totalCenas' => $totalCenas,
            'totalEstudiantes' => $totalEstudiantes,
            'totalEstudiantesForaneos' => $totalEstudiantesForaneos,
            'totalObreros' => $totalObreros,
            'totalAdministrativos' => $totalAdministrativos,
            'totalProfesor' => $totalProfesor,
            'totalEventual' => $totalEventual
        ]);

        // Descargar el PDF
        return $pdf->stream('reporte_comidas_' . $fecha . '.pdf');
    }
}
