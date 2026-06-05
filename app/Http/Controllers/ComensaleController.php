<?php

namespace App\Http\Controllers;

use App\Models\Comensale;
use App\Http\Requests\StoreComensaleRequest;
use App\Http\Requests\UpdateComensaleRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ComensalesImport;
use App\Exports\ComensalesExport;
use App\Exports\ComensalesTemplateExport;

class ComensaleController extends Controller
{
    public $data;

    /**
     * Constructor
     */
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
            $comensales = [];

            if ($request->filtro) {
                $comensales = Comensale::where('cedula', 'like', "%{$request->filtro}%")
                    ->orWhere('nombres', 'like', "%{$request->filtro}%")
                    ->orWhere('apellidos', 'like', "%{$request->filtro}%")
                    ->orWhere('tipo', '=', $request->filtro)
                    ->orderBy('id', 'desc')->paginate(12);
            } else {
                $comensales = Comensale::paginate(12);
            }

            $respuesta =  $this->data->respuesta;

            return view('admin.comensales.index', compact('comensales', 'request', 'respuesta'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar los comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function sincronizarData(Request $request)
    {
        $type = $request->input('type', 'global');

        if ($type === 'estudiantes') {
            return $this->executeSincronizarEstudiantes($request);
        }

        if ($type === 'empleados') {
            return $this->executeSincronizarEmpleados($request);
        }

        return $this->executeSincronizarGlobal($request);
    }

    public function executeSincronizarEstudiantesCron()
    {
        for ($step = 1; $step <= 3; $step++) {
            $request = Request::create('/sincronizar-data', 'POST', [
                'type' => 'estudiantes',
                'step' => $step,
            ]);

            $this->executeSincronizarEstudiantes($request);
        }
    }

    public function executeSincronizarEmpleadosCron()
    {
        for ($step = 1; $step <= 3; $step++) {
            $request = Request::create('/sincronizar-data', 'POST', [
                'type' => 'empleados',
                'step' => $step,
            ]);

            $this->executeSincronizarEmpleados($request);
        }
    }

    private function executeSincronizarEstudiantes(Request $request)
    {
        $progressKey = $this->getSincronizarProgressKey('estudiantes');

        try {
            $totalRecords = DB::connection('mysql_second')
                ->table('estudiantes')
                ->count();
            $batchSize = max(1, intval(ceil($totalRecords / 3)));
            $step = intval($request->input('step', 0));

            if ($step < 1 || $step > 3) {
                $this->initSincronizarDataProgress($progressKey, $totalRecords, 'Preparado', 'Sincronización de estudiantes preparada en 3 pasos.');

                $mensaje = 'Sincronización de estudiantes preparada. Ejecuta cada tramo con los botones 1/3, 2/3 y 3/3.';
                $estatus = Response::HTTP_OK;

                if ($request->expectsJson()) {
                    return response()->json([
                        'mensaje' => $mensaje,
                        'estatus' => $estatus,
                        'total' => $totalRecords,
                        'batchSize' => $batchSize,
                        'steps' => 3,
                    ], $estatus);
                }

                return back()->with(compact('mensaje', 'estatus'));
            }

            $offset = ($step - 1) * $batchSize;
            $processedInChunk = 0;
            $this->initSincronizarDataProgress($progressKey, $totalRecords, 'Ejecutando', "Ejecutando paso {$step} de estudiantes...");

            $datosEstudiantes = DB::connection('mysql_second')
                ->table('estudiantes')
                ->select('nombres', 'apellidos', 'nacionalidad', 'Cedula as cedula', 'Sexo as sexo')
                ->offset($offset)
                ->limit($batchSize)
                ->get();

            foreach ($datosEstudiantes as $comensal) {
                $estatusEstudiante = 0;
                $carreras = DB::connection('mysql_second')
                    ->table('carreras_est')
                    ->where('ConexEst', $comensal->cedula)
                    ->select('Status', 'CodCar')
                    ->get();

                foreach ($carreras as $carrera) {
                    if ($carrera->Status === 'A') {
                        $estatusEstudiante = 1;
                        break;
                    }
                }

                if ($estatusEstudiante) {
                    Comensale::updateOrCreate(
                        ['cedula' => $comensal->cedula],
                        [
                            'nombres' => $comensal->nombres,
                            'apellidos' => $comensal->apellidos,
                            'nacionalidad' => $comensal->nacionalidad,
                            'sexo' => strtoupper(substr(trim($comensal->sexo), 0, 1)) === 'F' ? 'F' : 'M',
                            'tipo_comensal' => 'ESTUDIANTE',
                            'estatus' => $estatusEstudiante,
                        ]
                    );
                }

                $processedInChunk++;
                $cumulativeProcessed = min($offset + $processedInChunk, $totalRecords);
                $this->setSincronizarDataProgress($progressKey, $cumulativeProcessed, $totalRecords, "Sincronizando estudiantes (paso {$step})");
            }

            $this->setSincronizarDataProgress($progressKey, min($offset + $processedInChunk, $totalRecords), $totalRecords, "Sincronización de estudiantes paso {$step} completada");

            $mensaje = "Paso {$step} de sincronización de estudiantes completado correctamente.";
            $estatus = Response::HTTP_OK;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));

        } catch (\Throwable $th) {
            $this->setSincronizarDataError($progressKey, $th);

            $mensaje = Helpers::getMensajeError($th, ', ¡Error interno al intentar sincronizar los estudiantes!');
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    private function executeSincronizarEmpleados(Request $request)
    {
        $progressKey = $this->getSincronizarProgressKey('empleados');

        try {
            $totalRecords = DB::connection('mysql_third')
                ->table('personal')
                ->where('per_status', 1)
                ->whereNotIn('nom_nombre', ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'])
                ->count();
            $batchSize = max(1, intval(ceil($totalRecords / 3)));
            $step = intval($request->input('step', 0));

            if ($step < 1 || $step > 3) {
                $this->initSincronizarDataProgress($progressKey, $totalRecords, 'Preparado', 'Sincronización de empleados preparada en 3 pasos.');

                $mensaje = 'Sincronización de empleados preparada. Ejecuta cada tramo con los botones 1/3, 2/3 y 3/3.';
                $estatus = Response::HTTP_OK;

                if ($request->expectsJson()) {
                    return response()->json([
                        'mensaje' => $mensaje,
                        'estatus' => $estatus,
                        'total' => $totalRecords,
                        'batchSize' => $batchSize,
                        'steps' => 3,
                    ], $estatus);
                }

                return back()->with(compact('mensaje', 'estatus'));
            }

            $offset = ($step - 1) * $batchSize;
            $processedInChunk = 0;
            $this->initSincronizarDataProgress($progressKey, $totalRecords, 'Ejecutando', "Ejecutando paso {$step} de empleados...");

            $datosEmpleados = DB::connection('mysql_third')
                ->table('personal')
                ->select('per_nombres', 'per_apellidos', 'per_nacionalidad', 'per_cedula', 'per_sexo', 'per_status', 'nom_nombre')
                ->where('per_status', 1)
                ->whereNotIn('nom_nombre', ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'])
                ->offset($offset)
                ->limit($batchSize)
                ->get();

            foreach ($datosEmpleados as $empleado) {
                if ($empleado->per_status == 1) {
                    Comensale::updateOrCreate(
                        ['cedula' => $empleado->per_cedula],
                        [
                            'nombres' => $empleado->per_nombres,
                            'apellidos' => $empleado->per_apellidos,
                            'nacionalidad' => 'V',
                            'sexo' => $empleado->per_sexo === 1 ? 'M' : 'F',
                            'tipo_comensal' => 'EMPLEADO',
                            'sub_tipo' => $empleado->nom_nombre,
                            'estatus' => 1,
                        ]
                    );
                }

                $processedInChunk++;
                $cumulativeProcessed = min($offset + $processedInChunk, $totalRecords);
                $this->setSincronizarDataProgress($progressKey, $cumulativeProcessed, $totalRecords, "Sincronizando empleados (paso {$step})");
            }

            $this->setSincronizarDataProgress($progressKey, min($offset + $processedInChunk, $totalRecords), $totalRecords, "Sincronización de empleados paso {$step} completada");

            $mensaje = "Paso {$step} de sincronización de empleados completado correctamente.";
            $estatus = Response::HTTP_OK;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));

            $mensaje = 'Sincronización de empleados completada correctamente.';
            $estatus = Response::HTTP_OK;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $this->setSincronizarDataError($progressKey, $th);

            $mensaje = Helpers::getMensajeError($th, ', ¡Error interno al intentar sincronizar los empleados!');
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    private function executeSincronizarGlobal(Request $request)
    {
        $progressKey = $this->getSincronizarProgressKey('global');

        try {
            $totalEstudiantes = DB::connection('mysql_second')
                ->table('estudiantes')
                ->count();

            $totalEmpleados = DB::connection('mysql_third')
                ->table('personal')
                ->count();

            $totalRecords = $totalEstudiantes + $totalEmpleados;
            $batchSizeEstudiantes = max(1, intval(ceil($totalEstudiantes / 3)));
            $batchSizeEmpleados = max(1, intval(ceil($totalEmpleados / 3)));
            $processed = 0;

            $this->initSincronizarDataProgress($progressKey, $totalRecords, 'Preparando', 'Iniciando sincronización completa...');

            for ($batch = 0; $batch < 3; $batch++) {
                if ($processed >= $totalEstudiantes) {
                    break;
                }

                $datosEstudiantes = DB::connection('mysql_second')
                    ->table('estudiantes')
                    ->select('nombres', 'apellidos', 'nacionalidad', 'Cedula as cedula', 'Sexo as sexo')
                    ->offset($batch * $batchSizeEstudiantes)
                    ->limit($batchSizeEstudiantes)
                    ->get();

                foreach ($datosEstudiantes as $comensal) {
                    $estatusEstudiante = 0;
                    $carreras = DB::connection('mysql_second')
                        ->table('carreras_est')
                        ->where('ConexEst', $comensal->cedula)
                        ->select('Status', 'CodCar')
                        ->get();

                    foreach ($carreras as $carrera) {
                        if ($carrera->Status === 'A') {
                            $estatusEstudiante = 1;
                            break;
                        }
                    }

                    if ($estatusEstudiante) {
                        Comensale::updateOrCreate(
                            ['cedula' => $comensal->cedula],
                            [
                                'nombres' => $comensal->nombres,
                                'apellidos' => $comensal->apellidos,
                                'nacionalidad' => $comensal->nacionalidad,
                                'sexo' => strtoupper(substr(trim($comensal->sexo), 0, 1)) === 'F' ? 'F' : 'M',
                                'tipo_comensal' => 'ESTUDIANTE',
                                'estatus' => $estatusEstudiante,
                            ]
                        );
                    }

                    $processed++;
                    $this->setSincronizarDataProgress($progressKey, $processed, $totalRecords, 'Sincronizando estudiantes');
                }
            }

            for ($batch = 0; $batch < 3; $batch++) {
                if ($processed - $totalEstudiantes >= $totalEmpleados) {
                    break;
                }

                $datosEmpleados = DB::connection('mysql_third')
                    ->table('personal')
                    ->select('per_nombres', 'per_apellidos', 'per_nacionalidad', 'per_cedula', 'per_sexo', 'per_status', 'nom_nombre')
                    ->offset($batch * $batchSizeEmpleados)
                    ->limit($batchSizeEmpleados)
                    ->get();

                foreach ($datosEmpleados as $empleado) {
                    if ($empleado->per_status == 1) {
                        Comensale::updateOrCreate(
                            ['cedula' => $empleado->per_cedula],
                            [
                                'nombres' => $empleado->per_nombres,
                                'apellidos' => $empleado->per_apellidos,
                                'nacionalidad' => $empleado->per_nacionalidad == 'venezolano' ? 'V' : 'E',
                                'sexo' => $empleado->per_sexo === 1 ? 'M' : 'F',
                                'tipo_comensal' => $empleado->nom_nombre,
                                'estatus' => 1,
                            ]
                        );
                    }

                    $processed++;
                    $this->setSincronizarDataProgress($progressKey, $processed, $totalRecords, 'Sincronizando empleados');
                }
            }

            $this->setSincronizarDataProgress($progressKey, $totalRecords, $totalRecords, 'Sincronización completa');

            $mensaje = 'Sincronización completa realizada correctamente.';
            $estatus = Response::HTTP_OK;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $this->setSincronizarDataError($progressKey, $th);

            $mensaje = Helpers::getMensajeError($th, ', ¡Error interno al intentar sincronizar los comensales!');
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => $mensaje, 'estatus' => $estatus], $estatus);
            }

            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    private function getSincronizarProgressKey(string $type)
    {
        $userId = auth()->id() ?: 'cron';
        return "sincronizar_data_{$type}_progress_{$userId}";
    }

    private function initSincronizarDataProgress(string $progressKey, int $totalRecords, string $status, string $message)
    {
        Cache::put($progressKey, [
            'percent' => 0,
            'status' => $status,
            'message' => $message,
            'processed' => 0,
            'total' => $totalRecords,
        ], now()->addMinutes(30));
    }

    private function setSincronizarDataError(string $progressKey, \Throwable $th)
    {
        Cache::put($progressKey, [
            'percent' => 100,
            'status' => 'Error',
            'message' => Helpers::getMensajeError($th, 'Error interno de sincronización'),
            'processed' => 0,
            'total' => 0,
        ], now()->addMinutes(10));
    }

    public function sincronizarDataPage()
    {
        return view('admin.configuracion.sincronizar-data');
    }

    public function sincronizarDataStatus(Request $request)
    {
        $type = $request->input('type', 'global');
        $progressKey = $this->getSincronizarProgressKey($type);
        $progress = Cache::get($progressKey, [
            'percent' => 0,
            'status' => 'No iniciado',
            'message' => 'Sincronización no iniciada',
            'processed' => 0,
            'total' => 0,
        ]);

        return response()->json($progress);
    }

    private function setSincronizarDataProgress(string $progressKey, int $processed, int $totalRecords, string $status)
    {
        $percent = $totalRecords > 0 ? intval(round(($processed / $totalRecords) * 100)) : 0;
        Cache::put($progressKey, [
            'percent' => $percent,
            'status' => $status,
            'message' => "{$status}: {$processed} / {$totalRecords}",
            'processed' => $processed,
            'total' => $totalRecords,
        ], now()->addMinutes(30));
    }

    /**
     * Importar comensales desde un archivo Excel (xlsx, xls, csv)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // Validación del archivo: fuera del try para que Laravel maneje redirección/errores de validación
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt'
        ]);

        try {
            $file = $request->file('file');

            $import = new ComensalesImport();
            Excel::import($import, $file);

            $errors = $import->getErrors();
            if (count($errors)) {
                $mensaje = "Importación finalizada con errores. Filas omitidas: " . count($errors);
                $estatus = Response::HTTP_OK;
                return back()->with(compact('mensaje', 'estatus'))->with('import_errores', $errors);
            }

            $mensaje = "Importación finalizada correctamente.";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar importar los comensales!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Descargar plantilla CSV para importación
     */
    public function downloadTemplate()
    {
        try {
            $path = storage_path('app/templates/comensales_template.csv');
            if (!file_exists($path)) {
                abort(404);
            }

            return response()->download($path, 'comensales_template.csv', [
                'Content-Type' => 'text/csv',
            ]);
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", Error al descargar la plantilla");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Descargar plantilla en formato XLSX (solo encabezados)
     */
    public function downloadTemplateXlsx()
    {
        try {
            return Excel::download(new ComensalesTemplateExport, 'comensales_template.xlsx');
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", Error al descargar la plantilla xlsx");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Exportar comensales registrados con la misma estructura de la plantilla
     */
    public function export()
    {
        try {
            return Excel::download(new ComensalesExport, 'comensales.xlsx');
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", Error al exportar comensales");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    // Toggle functionality removed — managed via edit/update or other flows now.

    /**
     * Metodo que permite registrar comensales
     *
     * @param  \App\Http\Requests\StoreComensaleRequest  $request
     * @return route(admin.comensales.index)
     */
    public function store(StoreComensaleRequest $request)
    {
        try {
            // Validando cedula 
            $estatusCreate = 0;

            // Validamos si se envio una foto
            if (isset($request->file)) {
                $request['foto'] = Helpers::setFile($request);
            }

            $request['sub_tipo'] = $request->input('sub_tipo', '');

            // registramos el estudiante
            $estatusCreate = Comensale::create($request->all());

            $mensaje =  $estatusCreate   ? "Estudiante registrado correctamente"
                : "No se pudo registrar verifique los datos.";
            $estatus = $estatusCreate ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar registrar un comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComensaleRequest  $request
     * @param  \App\Models\Comensale  $comensale
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComensaleRequest $request, Comensale $comensale)
    {
        try {
            if (!$request->estatus) {
                $request['estatus'] = 0;
            }

            // Validamos si se envio una foto
            if (isset($request->file)) {
                // Eliminamos la imagen anterior
                $fotoActual = explode('/', $comensale->foto);
                if ($fotoActual[count($fotoActual) - 1] != 'avatar.png') {
                    Helpers::removeFile($comensale->foto);
                }

                // Insertamos la nueva imagen o archivo
                $request['foto'] = Helpers::setFile($request);
            } else {
                $request['foto'] = $comensale->foto;
            }

            // Actualizamos los datos de comensales
            $comensale->update($request->all());

            $mensaje = "Los Datos del comensal se guardaron correctamente";
            $estatus = Response::HTTP_OK;

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            //throw $th;
            $mensaje = Helpers::getMensajeError($th, ", Error interno al intentar editar los datos del comensal");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comensale  $comensale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comensale $comensale, Request $request)
    {
        try {
            $mensaje = '';
            if ($request->input('checkbox')) {
                $comensale->delete();
                $mensaje = "El comensal {$comensale->nombre}, fue eliminado permanentemente.";
            } else {
                $comensale->update(["estatus" => 0]);
                $mensaje = "El comensal {$comensale->nombre}, fue desactivado correctamente.";
            }

            $estatus = 200;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error interno al intentar desactivar el comensal");
            $estatus = 301;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }
}
