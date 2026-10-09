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
            $query = Comensale::query();

            // Filtro por texto (cédula, nombres o apellidos)
            if ($request->filled('filtro')) {
                $filtro = trim($request->filtro);
                $query->where(function ($q) use ($filtro) {
                    $q->where('cedula', 'like', "%{$filtro}%")
                        ->orWhere('nombres', 'like', "%{$filtro}%")
                        ->orWhere('apellidos', 'like', "%{$filtro}%");
                });
            }

            // Filtro por tipo de comensal
            if ($request->filled('tipo') && $request->tipo !== 'TODOS') {
                $query->where('tipo_comensal', $request->tipo);
            }

            // Filtro por estatus (1: Activo, 0: Inactivo)
            if ($request->filled('estatus') && $request->estatus !== 'TODOS') {
                $query->where('estatus', (int)$request->estatus);
            }

            $comensales = $query->orderBy('id', 'desc')->paginate(12);
            $respuesta = $this->data->respuesta;

            // Estadísticas rápidas para tarjetas de información
            $stats = [
                'total' => Comensale::count(),
                'estudiantes' => Comensale::where('tipo_comensal', 'like', 'ESTUDIANTE%')->count(),
                'empleados' => Comensale::where(function ($q) {
                    $q->where('tipo_comensal', 'EMPLEADO')
                      ->orWhere('tipo_comensal', 'PROFESOR')
                      ->orWhere('tipo_comensal', 'ADMINISTRATIVO')
                      ->orWhere('tipo_comensal', 'OBRERO');
                })->count(),
                'activos' => Comensale::where('estatus', 1)->count(),
                'inactivos' => Comensale::where('estatus', 0)->count(),
            ];

            return view('admin.comensales.index', compact('comensales', 'request', 'respuesta', 'stats'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar los comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
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

    /**
     * Muestra la vista principal del módulo de sincronización con DUX y TEREPAIMA.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function sincronizarDataPage(Request $request)
    {
        $respuesta = $this->data->respuesta;

        // Estado conexión DUX (mysql_second)
        $duxStatus = [
            'connected' => false,
            'host' => config('database.connections.mysql_second.host'),
            'database' => config('database.connections.mysql_second.database'),
            'total_remoto' => 0,
            'activos_remoto' => 0,
            'error' => null,
        ];

        try {
            DB::connection('mysql_second')->getPdo();
            $duxStatus['connected'] = true;
            $duxStatus['total_remoto'] = DB::connection('mysql_second')->table('estudiantes')->count();
            $duxStatus['activos_remoto'] = DB::connection('mysql_second')
                ->table('estudiantes as e')
                ->join('carreras_est as c', 'c.ConexEst', '=', 'e.Cedula')
                ->where('c.Status', 'A')
                ->distinct()
                ->count('e.Cedula');
        } catch (\Throwable $e) {
            $duxStatus['error'] = $e->getMessage();
        }

        // Estado conexión TEREPAIMA (mysql_third)
        $terepaimaStatus = [
            'connected' => false,
            'host' => config('database.connections.mysql_third.host'),
            'database' => config('database.connections.mysql_third.database'),
            'total_remoto' => 0,
            'activos_remoto' => 0,
            'error' => null,
        ];

        try {
            DB::connection('mysql_third')->getPdo();
            $terepaimaStatus['connected'] = true;
            $schema = DB::connection('mysql_third')->getSchemaBuilder();
            if ($schema->hasTable('personal')) {
                $excludeNames = ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'];
                $terepaimaStatus['total_remoto'] = DB::connection('mysql_third')->table('personal')->whereNotIn('nom_nombre', $excludeNames)->count();
                $terepaimaStatus['activos_remoto'] = DB::connection('mysql_third')->table('personal')->whereNotIn('nom_nombre', $excludeNames)->where('nom_status', 1)->count();
            } elseif ($schema->hasTable('rrhh_personal')) {
                $terepaimaStatus['total_remoto'] = DB::connection('mysql_third')->table('rrhh_personal')->count();
                $terepaimaStatus['activos_remoto'] = DB::connection('mysql_third')->table('rrhh_personal')->where('per_status', 1)->count();
            }
        } catch (\Throwable $e) {
            $terepaimaStatus['error'] = $e->getMessage();
        }

        // Estadísticas locales COMESIS
        $localStats = [
            'estudiantes_activos' => Comensale::where('tipo_comensal', 'ESTUDIANTE')->where('estatus', 1)->count(),
            'estudiantes_total' => Comensale::where('tipo_comensal', 'ESTUDIANTE')->count(),
            'empleados_activos' => Comensale::where('tipo_comensal', 'EMPLEADO')->where('estatus', 1)->count(),
            'empleados_total' => Comensale::where('tipo_comensal', 'EMPLEADO')->count(),
        ];

        return view('admin.configuracion.sincronizar', compact('respuesta', 'duxStatus', 'terepaimaStatus', 'localStats'));
    }

    /**
     * Ejecuta la sincronización individual de estudiantes con DUX.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sincronizarEstudiantes(Request $request)
    {
        $resultado = Comensale::executeSincronizarEstudiantes();
        $mensaje = $resultado['mensaje'];
        $estatus = $resultado['success'] ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return back()->with(compact('mensaje', 'estatus'))->with('sync_estudiantes_result', $resultado);
    }

    /**
     * Ejecuta la sincronización individual de empleados con TEREPAIMA.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sincronizarEmpleados(Request $request)
    {
        $resultado = Comensale::executeSincronizarEmpleados();
        $mensaje = $resultado['mensaje'];
        $estatus = $resultado['success'] ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return back()->with(compact('mensaje', 'estatus'))->with('sync_empleados_result', $resultado);
    }

    /**
     * Ejecuta la sincronización masiva o por tipo.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sincronizarData(Request $request)
    {
        $tipo = $request->input('tipo', 'todos');

        if ($tipo === 'estudiantes') {
            return $this->sincronizarEstudiantes($request);
        }

        if ($tipo === 'empleados') {
            return $this->sincronizarEmpleados($request);
        }

        // Sincronizar ambos
        $resEstudiantes = Comensale::executeSincronizarEstudiantes();
        $resEmpleados = Comensale::executeSincronizarEmpleados();

        $exito = $resEstudiantes['success'] && $resEmpleados['success'];
        $mensaje = "Sincronización completa: Estudiantes ({$resEstudiantes['sincronizados']} sincronizados) | Empleados ({$resEmpleados['sincronizados']} sincronizados).";
        $estatus = $exito ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT;

        return back()->with(compact('mensaje', 'estatus'))
            ->with('sync_estudiantes_result', $resEstudiantes)
            ->with('sync_empleados_result', $resEmpleados);
    }

    /**
     * Retorna el estado en JSON para AJAX/monitoreo.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sincronizarDataStatus(Request $request)
    {
        try {
            $estudiantesCount = Comensale::where('tipo_comensal', 'ESTUDIANTE')->count();
            $empleadosCount = Comensale::where('tipo_comensal', 'EMPLEADO')->count();

            return response()->json([
                'success' => true,
                'local' => [
                    'estudiantes' => $estudiantesCount,
                    'empleados' => $empleadosCount,
                    'total' => $estudiantesCount + $empleadosCount,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
