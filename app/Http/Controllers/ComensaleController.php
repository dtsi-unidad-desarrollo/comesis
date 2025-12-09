<?php

namespace App\Http\Controllers;

use App\Models\Comensale;
use App\Http\Requests\StoreComensaleRequest;
use App\Http\Requests\UpdateComensaleRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    
    public function sincronizarData()
    {
        try {


            $datos = DB::connection('mysql_second')->table('estudiantes')->select('nombres', 'apellidos', 'nacionalidad', 'Cedula', 'Sexo')->get();

            foreach ($datos as $key => $comensal) {
                $estatusEstudiante = 0;
                $carreras = DB::connection('mysql_second')->table('carreras_est')->where('ConexEst',  $comensal->Cedula)->select('Status', 'CodCar')->get();

                if (count($carreras)) {
                    foreach ($carreras as $key => $carrera) {
                        if ($carrera->Status == "A") {
                            $estatusEstudiante = 1;
                            break;
                        }
                    }
                }


                $siExiste = Comensale::where('cedula', $comensal->Cedula)->first();
                if ($siExiste) {
                    // actualizamos los datos
                    $siExiste->update([
                        "nombres" => $comensal->nombres,
                        "apellidos" => $comensal->apellidos,
                        'nacionalidad' => $comensal->nacionalidad,
                        'cedula' => $comensal->Cedula,
                        'tipo' => 'ESTUDIANTE',
                        'estatus' => $estatusEstudiante
                    ]);
                } else {
                    // registramos en el sistema
                    Comensale::create([
                        "nombres" => $comensal->nombres,
                        "apellidos" => $comensal->apellidos,
                        'nacionalidad' => $comensal->nacionalidad,
                        'cedula' => $comensal->Cedula,
                        'tipo' => 'ESTUDIANTE',
                        'estatus' => $estatusEstudiante
                    ]);
                }
            }

            $mensaje = "Datos sincronizados correctamente!";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar sincronizar los comensales!");
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
    public function destroy(Comensale $comensale)
    {
        try {
            $comensale->update(["estatus" => 0]);
            $mensaje = "El comensal {$comensale->nombre}, fue desactivado correctamente.";
            $estatus = 200;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error interno al intentar desactivar el comensal");
            $estatus = 301;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }
}
